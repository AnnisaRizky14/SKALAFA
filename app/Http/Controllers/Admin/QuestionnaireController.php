<?php
// app/Http/Controllers/Admin/QuestionnaireController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Questionnaire;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $query = Questionnaire::with(['faculty', 'subCategory', 'questions', 'responses']);

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
        $faculties = Faculty::active()->ordered()->get();
        $subCategories = SubCategory::active()->ordered()->get();
        return view('admin.questionnaires.create', compact('faculties', 'subCategories'));
    }

    public function store(Request $request)
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
        $faculties = Faculty::active()->ordered()->get();
        $subCategories = SubCategory::active()->ordered()->get();
        return view('admin.questionnaires.edit', compact('questionnaire', 'faculties', 'subCategories'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
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

        $questionnaire->update($validated);

        return redirect()->route('admin.questionnaires.index')
            ->with('success', 'Kuisioner berhasil diperbarui.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        if ($questionnaire->responses()->exists()) {
            return redirect()->route('admin.questionnaires.index')
                ->with('error', 'Kuisioner tidak dapat dihapus karena sudah ada respon.');
        }

        $questionnaire->delete();

        return redirect()->route('admin.questionnaires.index')
            ->with('success', 'Kuisioner berhasil dihapus.');
    }

    public function toggleStatus(Questionnaire $questionnaire)
    {
        $questionnaire->update([
            'is_active' => !$questionnaire->is_active
        ]);

        $status = $questionnaire->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Kuisioner berhasil {$status}.");
    }

    public function preview(Questionnaire $questionnaire)
    {
        $questionnaire->load(['faculty', 'questions.subCategory']);
        $questionsGrouped = $questionnaire->getQuestionsGroupedBySubCategory();
        $subcategories = \App\Models\SubCategory::whereIn('id', $questionsGrouped->keys())->ordered()->get();

        return view('admin.questionnaires.preview', compact('questionnaire', 'questionsGrouped', 'subcategories'));
    }
}