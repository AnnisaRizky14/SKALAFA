<?php
// app/Http/Controllers/Admin/QuestionnaireController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Questionnaire;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Questionnaire::with(['faculty', 'subCategory', 'questions', 'responses']);

        // Filter by accessible faculties for faculty admin
        if ($user->isFacultyAdmin()) {
            $query->whereIn('faculty_id', $user->getAccessibleFacultyIds());
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('faculty', function($facultyQuery) use ($search) {
                      $facultyQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subCategory', function($subCategoryQuery) use ($search) {
                      $subCategoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by faculty
        if ($request->filled('faculty')) {
            $query->where('faculty_id', $request->faculty);
        }

        $questionnaires = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.questionnaires.index', compact('questionnaires'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get faculties based on user access
        if ($user->isSuperAdmin()) {
            $faculties = Faculty::active()->ordered()->get();
        } else {
            $faculties = Faculty::active()->ordered()
                ->whereIn('id', $user->getAccessibleFacultyIds())
                ->get();
        }
        
        $subCategories = SubCategory::active()->ordered()->get();
        return view('admin.questionnaires.create', compact('faculties', 'subCategories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'estimated_duration' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Check if faculty admin is trying to create for their faculty
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($validated['faculty_id'])) {
            return redirect()->back()
                ->withErrors(['faculty_id' => 'Anda tidak memiliki akses ke fakultas ini.'])
                ->withInput();
        }

        Questionnaire::create($validated);

        return redirect()->route('admin.questionnaires.index')
            ->with('success', 'Kuisioner berhasil dibuat.');
    }

    public function show(Questionnaire $questionnaire)
    {
        $questionnaire->load(['faculty', 'questions.subCategory', 'responses']);
        
        return view('admin.questionnaires.show', compact('questionnaire'));
    }

    public function edit(Questionnaire $questionnaire)
    {
        $user = auth()->user();
        
        // Check if faculty admin can access this questionnaire
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($questionnaire->faculty_id)) {
            abort(403, 'Anda tidak memiliki akses ke kuisioner ini.');
        }
        
        // Get faculties based on user access
        if ($user->isSuperAdmin()) {
            $faculties = Faculty::active()->ordered()->get();
        } else {
            $faculties = Faculty::active()->ordered()
                ->whereIn('id', $user->getAccessibleFacultyIds())
                ->get();
        }
        
        $subCategories = SubCategory::active()->ordered()->get();
        return view('admin.questionnaires.edit', compact('questionnaire', 'faculties', 'subCategories'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $user = auth()->user();
        
        // Check if faculty admin can access this questionnaire
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($questionnaire->faculty_id)) {
            abort(403, 'Anda tidak memiliki akses ke kuisioner ini.');
        }
        
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'estimated_duration' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Check if faculty admin is trying to change to different faculty
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($validated['faculty_id'])) {
            return redirect()->back()
                ->withErrors(['faculty_id' => 'Anda tidak memiliki akses ke fakultas ini.'])
                ->withInput();
        }

        $questionnaire->update($validated);

        return redirect()->route('admin.questionnaires.index')
            ->with('success', 'Kuisioner berhasil diperbarui.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $user = auth()->user();
        
        // Check if faculty admin can access this questionnaire
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($questionnaire->faculty_id)) {
            abort(403, 'Anda tidak memiliki akses ke kuisioner ini.');
        }
        
        if ($questionnaire->responses()->exists()) {
            return redirect()->route('admin.questionnaires.index')
                ->with('error', 'Kuisioner tidak dapat dihapus karena sudah ada respon.');
        }

        // Create notification before deletion
        \App\Models\Notification::createQuestionnaireDeletionNotification($questionnaire);

        $questionnaire->delete();

        return redirect()->route('admin.questionnaires.index')
            ->with('success', 'Kuisioner berhasil dihapus.');
    }

    public function toggleStatus(Request $request, \App\Models\Questionnaire $questionnaire)
{
    try {
        // balikkan status aktif
        $questionnaire->is_active = ! $questionnaire->is_active;
        $questionnaire->save();

        // hitung teks & class badge
        if ($questionnaire->is_active) {
            if ($questionnaire->isAvailable()) {
                $statusText  = 'Aktif & Tersedia';
                $statusClass = 'bg-green-100 text-green-800';
            } else {
                $statusText  = 'Aktif & Tidak Tersedia';
                $statusClass = 'bg-yellow-100 text-yellow-800';
            }
        } else {
            $statusText  = 'Tidak Aktif';
            $statusClass = 'bg-red-100 text-red-800';
        }

        return response()->json([
            'success'      => true,
            'message'      => 'Status kuisioner berhasil diperbarui.',
            'is_active'    => (bool) $questionnaire->is_active,
            'status_text'  => $statusText,
            'status_class' => $statusClass,
        ]);
    } catch (\Throwable $e) {
        // optional: catat error
        // Log::error('Toggle status gagal', ['error' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan di server saat mengubah status kuisioner.',
        ], 500);
    }
}

    public function preview(Questionnaire $questionnaire)
    {
        $questionnaire->load(['faculty', 'questions.subCategory']);
        $questionsGrouped = $questionnaire->getQuestionsGroupedBySubCategory();
        $subcategories = \App\Models\SubCategory::whereIn('id', $questionsGrouped->keys())->ordered()->get();

        return view('admin.questionnaires.preview', compact('questionnaire', 'questionsGrouped', 'subcategories'));
    }
}