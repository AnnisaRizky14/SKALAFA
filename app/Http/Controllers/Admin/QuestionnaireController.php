<?php
// app/Http/Controllers/Admin/QuestionnaireController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    /**
     * Prepare basic questionnaire data and store it in session, then redirect to setup page.
     */
    public function prepare(Request $request)
    {
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

        // store base data in session for the next step
        session(['questionnaire.create' => $validated]);

        return redirect()->route('admin.questionnaires.setup');
    }

    /**
     * Show the setup page where admin can add subsections and questions.
     */
    public function setup()
    {
        $data = session('questionnaire.create');
        if (! $data) {
            return redirect()->route('admin.questionnaires.create')
                ->with('error', 'Silakan isi data dasar kuisioner terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $faculties = Faculty::active()->ordered()->get();
        } else {
            $faculties = Faculty::active()->ordered()
                ->whereIn('id', $user->getAccessibleFacultyIds())
                ->get();
        }

        $subCategories = SubCategory::active()->ordered()->get();

        return view('admin.questionnaires.setup', compact('data', 'faculties', 'subCategories'));
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
            'subsections' => 'required|array|min:1',
            'subsections.*.sub_category_id' => 'required|exists:sub_categories,id',
            'subsections.*.questions' => 'required|array|min:1',
            'subsections.*.questions.*.question_text' => 'required|string|max:1000',
            'subsections.*.questions.*.order' => 'required|integer|min:1',
            'subsections.*.questions.*.is_required' => 'boolean',
        ]);

        // Check if faculty admin is trying to create for their faculty
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($validated['faculty_id'])) {
            return redirect()->back()
                ->withErrors(['faculty_id' => 'Anda tidak memiliki akses ke fakultas ini.'])
                ->withInput();
        }

        // Create questionnaire
        $questionnaire = Questionnaire::create([
            'faculty_id' => $validated['faculty_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructions' => $validated['instructions'],
            'estimated_duration' => $validated['estimated_duration'],
            'is_active' => $validated['is_active'] ?? false,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Create questions for each subsection
        foreach ($validated['subsections'] as $subsectionData) {
            foreach ($subsectionData['questions'] as $questionData) {
                Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'sub_category_id' => $subsectionData['sub_category_id'],
                    'question_text' => $questionData['question_text'],
                    'order' => $questionData['order'],
                    'is_required' => $questionData['is_required'] ?? false,
                ]);
            }
        }

        // clear temporary session data
        session()->forget('questionnaire.create');

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
            'subsections' => 'nullable|array',
            'subsections.*.sub_category_id' => 'required_with:subsections|exists:sub_categories,id',
            'subsections.*.questions' => 'required_with:subsections|array|min:1',
            'subsections.*.questions.*.question_text' => 'required_with:subsections|string|max:1000',
            'subsections.*.questions.*.order' => 'required_with:subsections|integer|min:1',
            'subsections.*.questions.*.is_required' => 'boolean',
        ]);

        // Check if faculty admin is trying to change to different faculty
        if ($user->isFacultyAdmin() && !$user->canAccessFaculty($validated['faculty_id'])) {
            return redirect()->back()
                ->withErrors(['faculty_id' => 'Anda tidak memiliki akses ke fakultas ini.'])
                ->withInput();
        }

        // Update questionnaire and replace questions inside a transaction
        DB::transaction(function() use ($questionnaire, $validated) {
            $questionnaire->update([
                'faculty_id' => $validated['faculty_id'],
                'sub_category_id' => $validated['sub_category_id'] ?? null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
                'estimated_duration' => $validated['estimated_duration'],
                'is_active' => $validated['is_active'] ?? false,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
            ]);

            // If subsections provided, remove existing questions and recreate
            if (!empty($validated['subsections'])) {
                // delete all existing questions for this questionnaire
                \App\Models\Question::where('questionnaire_id', $questionnaire->id)->delete();

                foreach ($validated['subsections'] as $subsectionData) {
                    foreach ($subsectionData['questions'] as $questionData) {
                        \App\Models\Question::create([
                            'questionnaire_id' => $questionnaire->id,
                            'sub_category_id' => $subsectionData['sub_category_id'],
                            'question_text' => $questionData['question_text'],
                            'order' => $questionData['order'],
                            'is_required' => $questionData['is_required'] ?? false,
                        ]);
                    }
                }
            }
        });

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