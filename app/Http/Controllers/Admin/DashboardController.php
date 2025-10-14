<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Notification;
use App\Models\Questionnaire;
use App\Models\Response;
use App\Models\ResponseAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
// Tambahkan di DashboardController jika belum ada
public function index()
{
    $stats = [
        'total_responses' => Response::completed()->count(),
        'responses_this_month' => Response::completed()->thisMonth()->count(),
        'active_questionnaires' => Questionnaire::active()->count(),
        'average_satisfaction' => $this->getAverageSatisfaction(),
    ];

    $chartsData = [
        'satisfaction_by_faculty' => $this->getSatisfactionByFaculty(),
        'monthly_responses' => $this->getMonthlyResponses(),
        'satisfaction_distribution' => $this->getSatisfactionDistribution(),
        'subcategory_ratings' => $this->getSubcategoryRatings(),
    ];

    // Ensure all keys exist even if empty
    foreach (['satisfaction_by_faculty', 'monthly_responses', 'satisfaction_distribution', 'subcategory_ratings'] as $key) {
        if (empty($chartsData[$key])) {
            if ($key === 'satisfaction_distribution') {
                $chartsData[$key] = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            } else {
                $chartsData[$key] = [];
            }
        }
    }

    $recentResponses = Response::with(['questionnaire', 'faculty'])
        ->completed()
        ->latest()
        ->take(5)
        ->get();

    $notifications = Notification::latest()
        ->take(10)
        ->get();

    $unreadNotificationsCount = Notification::unread()->count();

    return view('admin.dashboard', compact('stats', 'chartsData', 'recentResponses', 'notifications', 'unreadNotificationsCount'));
}

    private function getAverageSatisfaction()
    {
        $average = ResponseAnswer::avg('rating');
        return round($average ?? 0, 2);
    }

    private function getSatisfactionByFaculty()
    {
        $facultiesData = DB::table('faculties')
            ->leftJoin('responses', 'faculties.id', '=', 'responses.faculty_id')
            ->leftJoin('response_answers', 'responses.id', '=', 'response_answers.response_id')
            ->select(
                'faculties.id',
                'faculties.name',
                'faculties.short_name',
                'faculties.color',
                DB::raw('COUNT(DISTINCT responses.id) as total_responses'),
                DB::raw('AVG(response_answers.rating) as average_rating')
            )
            ->where('responses.is_completed', true)
            ->whereNotNull('responses.completed_at')
            ->groupBy('faculties.id', 'faculties.name', 'faculties.short_name', 'faculties.color')
            ->orderBy('faculties.name')
            ->get();

        $data = [];
        foreach ($facultiesData as $faculty) {
            $data[] = [
                'faculty' => $faculty->short_name ?? $faculty->name,
                'average_rating' => round($faculty->average_rating ?? 0, 2),
                'total_responses' => (int) $faculty->total_responses,
                'color' => $faculty->color ?? '#003f7f'
            ];
        }

        // Add faculties with no responses
        $allFaculties = Faculty::select('id', 'name', 'short_name', 'color')->get();
        $facultyNamesWithData = collect($data)->pluck('faculty')->toArray();
        foreach ($allFaculties as $faculty) {
            if (!in_array($faculty->short_name ?? $faculty->name, $facultyNamesWithData)) {
                $data[] = [
                    'faculty' => $faculty->short_name ?? $faculty->name,
                    'average_rating' => 0,
                    'total_responses' => 0,
                    'color' => $faculty->color ?? '#003f7f'
                ];
            }
        }

        return $data;
    }

    private function getMonthlyResponses()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Response::completed()
                ->whereYear('completed_at', $date->year)
                ->whereMonth('completed_at', $date->month)
                ->count();

            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        return $data;
    }

    private function getSatisfactionDistribution()
    {
        $distribution = ResponseAnswer::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($distribution[$i])) {
                $distribution[$i] = 0;
            }
        }

        ksort($distribution);
        return $distribution;
    }

    private function getSubcategoryRatings()
    {
        $subcategories = DB::table('sub_categories')
            ->leftJoin('questions', 'sub_categories.id', '=', 'questions.sub_category_id')
            ->leftJoin('response_answers', 'questions.id', '=', 'response_answers.question_id')
            ->leftJoin('responses', 'response_answers.response_id', '=', 'responses.id')
            ->select('sub_categories.name', 'sub_categories.order', DB::raw('AVG(response_answers.rating) as average_rating'))
            ->where('responses.is_completed', true)
            ->whereNotNull('responses.completed_at')
            ->groupBy('sub_categories.id', 'sub_categories.name', 'sub_categories.order')
            ->orderBy('sub_categories.order')
            ->get();

        $data = [];
        foreach ($subcategories as $subcategory) {
            $data[] = [
                'name' => $subcategory->name,
                'average_rating' => round($subcategory->average_rating ?? 0, 2)
            ];
        }

        // Add subcategories with no ratings
        $allSubcategories = DB::table('sub_categories')->select('name', 'order')->orderBy('order')->get();
        $subcategoryNamesWithData = collect($data)->pluck('name')->toArray();
        foreach ($allSubcategories as $subcategory) {
            if (!in_array($subcategory->name, $subcategoryNamesWithData)) {
                $data[] = [
                    'name' => $subcategory->name,
                    'average_rating' => 0
                ];
            }
        }

        return $data;
    }

    public function markNotificationRead(Notification $notification)
    {
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        Notification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
