<?php
// app/Http/Controllers/Admin/ResponseController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Faculty;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function index(Request $request)
    {
        $query = Response::with(['questionnaire', 'faculty', 'answers.question'])
            ->completed()
            ->latest();

        // Filter by faculty
        if ($request->faculty_id) {
            $query->where('faculty_id', $request->faculty_id);
        }

        // Filter by questionnaire
        if ($request->questionnaire_id) {
            $query->where('questionnaire_id', $request->questionnaire_id);
        }

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('completed_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('completed_at', '<=', $request->end_date);
        }

        // Filter by rating range
        if ($request->min_rating || $request->max_rating) {
            $query->whereHas('answers', function($q) use ($request) {
                if ($request->min_rating) {
                    $q->havingRaw('AVG(rating) >= ?', [$request->min_rating]);
                }
                if ($request->max_rating) {
                    $q->havingRaw('AVG(rating) <= ?', [$request->max_rating]);
                }
            });
        }

        $responses = $query->paginate(20);
        
        // For filter dropdowns
        $faculties = Faculty::active()->ordered()->get();
        $questionnaires = Questionnaire::active()->with('faculty')->get();

        return view('admin.responses.index', compact('responses', 'faculties', 'questionnaires'));
    }

    public function show(Response $response)
    {
        $response->load([
            'questionnaire.faculty',
            'answers.question.subCategory'
        ]);

        // Group answers by subcategory
        $answersGrouped = $response->answers->groupBy('question.sub_category_id');
        $subcategories = \App\Models\SubCategory::whereIn('id', $answersGrouped->keys())->ordered()->get();

        return view('admin.responses.show', compact('response', 'answersGrouped', 'subcategories'));
    }

    public function destroy(Response $response)
    {
        $response->delete();

        return redirect()->route('admin.responses.index')
            ->with('success', 'Respon berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'response_ids' => 'required|array',
            'response_ids.*' => 'exists:responses,id'
        ]);

        $count = Response::whereIn('id', $validated['response_ids'])->delete();

        return redirect()->route('admin.responses.index')
            ->with('success', "{$count} respon berhasil dihapus.");
    }

    public function export(Request $request)
    {
        // This would implement Excel/PDF export
        // For now, return JSON data
        $responses = Response::with(['questionnaire', 'faculty', 'answers.question'])
            ->completed()
            ->get();

        $data = $responses->map(function($response) {
            return [
                'id' => $response->id,
                'faculty' => $response->faculty->name,
                'questionnaire' => $response->questionnaire->title,
                'completed_at' => $response->completed_at->format('Y-m-d H:i:s'),
                'average_rating' => $response->getAverageRating(),
                'satisfaction_level' => $response->getSatisfactionLevel(),
                'duration_minutes' => $response->getDurationInMinutes(),
                'total_answers' => $response->answers->count(),
            ];
        });

        return response()->json($data);
    }

    public function statistics()
    {
        $stats = [
            'total_responses' => Response::completed()->count(),
            'responses_today' => Response::completed()->whereDate('completed_at', today())->count(),
            'responses_this_week' => Response::completed()->thisWeek()->count(),
            'responses_this_month' => Response::completed()->thisMonth()->count(),
            'average_satisfaction' => round(Response::completed()->with('answers')->get()->avg(function($response) {
                return $response->getAverageRating();
            }), 2),
            'completion_rate' => $this->getCompletionRate(),
            'average_duration' => $this->getAverageDuration(),
        ];

        return response()->json($stats);
    }

    private function getCompletionRate()
    {
        $totalStarted = Response::count();
        $totalCompleted = Response::completed()->count();

        return $totalStarted > 0 ? round(($totalCompleted / $totalStarted) * 100, 2) : 0;
    }

    private function getAverageDuration()
    {
        $completedResponses = Response::completed()
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->get();

        if ($completedResponses->isEmpty()) {
            return 0;
        }

        $totalMinutes = $completedResponses->sum(function($response) {
            return $response->getDurationInMinutes();
        });

        return round($totalMinutes / $completedResponses->count(), 1);
    }
}