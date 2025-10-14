<?php
// app/Http/Controllers/Admin/QuestionnaireController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::with(['faculty', 'questions', 'responses'])
            ->latest()
            ->paginate(10);

        return view('admin.questionnaires.index', compact('questionnaires'));
    }

    public function create()
    {
        $faculties = Faculty::active()->ordered()->get();
        return view('admin.questionnaires.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
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
        return view('admin.questionnaires.edit', compact('questionnaire', 'faculties'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
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