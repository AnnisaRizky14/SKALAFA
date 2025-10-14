<?php
// app/Http/Controllers/User/SurveyController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\ResponseAnswer;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    public function dashboard()
    {
        $faculties = Faculty::active()->ordered()->get();
        return view('survey.dashboard', compact('faculties'));
    }

    public function faculties()
    {
        $faculties = Faculty::active()->ordered()->get();
        return view('survey.faculties', compact('faculties'));
    }

    public function questionnaires(Faculty $faculty)
    {
        $questionnaires = $faculty->questionnaires()
            ->available()
            ->with('questions')
            ->get();

        if ($questionnaires->isEmpty()) {
            return redirect()->route('survey.faculties')
                ->with('error', 'Tidak ada kuisioner yang tersedia untuk fakultas ini saat ini.');
        }

        return view('survey.questionnaires', compact('faculty', 'questionnaires'));
    }

    public function start(Questionnaire $questionnaire)
    {
        if (!$questionnaire->isAvailable()) {
            return redirect()->route('survey.faculties')
                ->with('error', 'Kuisioner tidak tersedia saat ini.');
        }

        // Get questions grouped by subcategory
        $questionsGrouped = $questionnaire->getQuestionsGroupedBySubCategory();
        $subcategories = SubCategory::whereIn('id', $questionsGrouped->keys())->ordered()->get();

        // Check if there's an existing incomplete response in session
        $token = session('survey_token');
        $response = null;
        
        if ($token) {
            $response = Response::where('response_token', $token)
                ->where('questionnaire_id', $questionnaire->id)
                ->where('is_completed', false)
                ->first();
        }

        // Create new response if none exists
        if (!$response) {
            $token = Response::generateToken();
            $response = Response::create([
                'questionnaire_id' => $questionnaire->id,
                'faculty_id' => $questionnaire->faculty_id,
                'response_token' => $token,
                'session_id' => session()->getId(),
                'started_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session(['survey_token' => $token]);
        }

        return view('survey.questions', compact(
            'questionnaire', 
            'questionsGrouped', 
            'subcategories', 
            'response'
        ));
    }

    public function submitAnswer(Request $request)
    {
        $validated = $request->validate([
            'response_token' => 'required|exists:responses,response_token',
            'question_id' => 'required|exists:questions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $response = Response::where('response_token', $validated['response_token'])->first();

        if (!$response || $response->is_completed) {
            return response()->json(['error' => 'Invalid response token'], 400);
        }

        // Update or create answer
        ResponseAnswer::updateOrCreate(
            [
                'response_id' => $response->id,
                'question_id' => $validated['question_id']
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]
        );

        return response()->json(['success' => true]);
    }

    public function submit(Request $request)
    {
        Log::info('Survey submit started', ['data' => $request->all()]);

        $validated = $request->validate([
            'response_token' => 'required|exists:responses,response_token',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.rating' => 'required|integer|min:1|max:5',
            'answers.*.comment' => 'nullable|string|max:1000'
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            $response = Response::where('response_token', $validated['response_token'])->first();

            if (!$response || $response->is_completed) {
                Log::warning('Invalid response token or already completed', ['token' => $validated['response_token']]);
                return redirect()->route('survey.dashboard')
                    ->with('error', 'Sesi survei tidak valid atau sudah selesai.');
            }

            Log::info('Saving answers', ['response_id' => $response->id, 'answers_count' => count($validated['answers'])]);

            // Save all answers
            foreach ($validated['answers'] as $answerData) {
                ResponseAnswer::updateOrCreate(
                    [
                        'response_id' => $response->id,
                        'question_id' => $answerData['question_id']
                    ],
                    [
                        'rating' => $answerData['rating'],
                        'comment' => $answerData['comment'] ?? null
                    ]
                );
            }

            // Mark response as completed
            $response->update([
                'is_completed' => true,
                'completed_at' => now()
            ]);

            DB::commit();

            Log::info('Survey completed successfully', ['response_id' => $response->id]);

            // Clear session
            session()->forget('survey_token');

            return redirect()->route('survey.thank-you');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving survey', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }

    public function thankYou()
    {
        return view('survey.thank-you');
    }

    public function getProgress(Request $request)
    {
        $token = $request->input('token');
        $response = Response::where('response_token', $token)->first();

        if (!$response) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $progress = $response->getProgressPercentage();
        
        return response()->json([
            'progress' => $progress,
            'answered_questions' => $response->answers()->count(),
            'total_questions' => $response->questionnaire->getTotalQuestions()
        ]);
    }
}