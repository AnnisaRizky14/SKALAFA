<?php
// app/Http/Controllers/Admin/ResponseController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Faculty;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;

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
            $having = '1=1';
            $params = [];
            if ($request->min_rating) {
                $having .= ' AND AVG(response_answers.rating) >= ?';
                $params[] = $request->min_rating;
            }
            if ($request->max_rating) {
                $having .= ' AND AVG(response_answers.rating) <= ?';
                $params[] = $request->max_rating;
            }

            $query->whereExists(function ($subQuery) use ($having, $params) {
                $subQuery->select(DB::raw(1))
                         ->from('response_answers')
                         ->whereColumn('response_answers.response_id', 'responses.id')
                         ->groupBy('response_answers.response_id')
                         ->havingRaw($having, $params);
            });
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('questionnaire', function($subQ) use ($search) {
                      $subQ->where('title', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('faculty', function($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  });
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
        $format = $request->get('format', 'excel');

        switch ($format) {
            case 'pdf':
                $export = new \App\Exports\ResponsesPdfExport($request);
                $filename = 'responses_' . now()->format('Y-m-d_H-i-s') . '.pdf';
                return $export->generatePdf()->download($filename);



            case 'excel':
            default:
                $filename = 'responses_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                return Excel::download(new ResponsesExport($request), $filename);
        }
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