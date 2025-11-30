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
public function index()
{
    $user = auth()->user();
    $accessibleFacultyIds = $user->getAccessibleFacultyIds();
    
    // Build queries with faculty filtering
    $responsesQuery = Response::completed();
    $questionnairesQuery = Questionnaire::active();
    
    // Apply faculty filter for faculty admins
    if ($user->isFacultyAdmin()) {
        $responsesQuery->whereIn('faculty_id', $accessibleFacultyIds);
        $questionnairesQuery->whereIn('faculty_id', $accessibleFacultyIds);
    }
    
    $stats = [
        'total_responses' => $responsesQuery->count(),
        'responses_this_month' => (clone $responsesQuery)->thisMonth()->count(),
        'active_questionnaires' => $questionnairesQuery->count(),
        'average_satisfaction' => $this->getAverageSatisfaction($accessibleFacultyIds),
    ];

    $chartsData = [
        'satisfaction_by_faculty' => $this->getSatisfactionByFaculty($accessibleFacultyIds),
        'monthly_responses' => $this->getMonthlyResponses($accessibleFacultyIds),
        'satisfaction_distribution' => $this->getSatisfactionDistribution($accessibleFacultyIds),
        'subcategory_ratings' => $this->getSubcategoryRatings($accessibleFacultyIds),
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

    $recentResponsesQuery = Response::with(['questionnaire', 'faculty'])
        ->completed()
        ->latest();
    
    // Apply faculty filter for faculty admins
    if ($user->isFacultyAdmin()) {
        $recentResponsesQuery->whereIn('faculty_id', $accessibleFacultyIds);
    }
    
    $recentResponses = $recentResponsesQuery->take(5)->get();

    $notifications = Notification::latest()
        ->take(10)
        ->get();

    $unreadNotificationsCount = Notification::unread()->count();

    return view('admin.dashboard', compact('stats', 'chartsData', 'recentResponses', 'notifications', 'unreadNotificationsCount'));
}

    private function getAverageSatisfaction($accessibleFacultyIds = null)
    {
        $query = ResponseAnswer::query();
        
        // Filter by faculty if provided
        if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
            $query->whereHas('response', function($q) use ($accessibleFacultyIds) {
                $q->whereIn('faculty_id', $accessibleFacultyIds);
            });
        }
        
        $average = $query->avg('rating');
        return round($average ?? 0, 2);
    }

    private function getSatisfactionByFaculty($accessibleFacultyIds = null)
    {
        $query = DB::table('faculties')
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
            ->where(function($q) {
                $q->where('responses.is_completed', true)
                  ->whereNotNull('responses.completed_at')
                  ->orWhereNull('responses.id');
            })
            ->groupBy('faculties.id', 'faculties.name', 'faculties.short_name', 'faculties.color')
            ->orderBy('faculties.name');
        
        // Filter by accessible faculties
        if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
            $query->whereIn('faculties.id', $accessibleFacultyIds);
        }
        
        $facultiesData = $query->get();

        $data = [];
        foreach ($facultiesData as $faculty) {
            $data[] = [
                'faculty' => $faculty->short_name ?? $faculty->name,
                'average_rating' => round($faculty->average_rating ?? 0, 2),
                'total_responses' => (int) $faculty->total_responses,
                'color' => $faculty->color ?? '#003f7f'
            ];
        }

        // Add faculties with no responses (only accessible ones)
        $allFacultiesQuery = Faculty::select('id', 'name', 'short_name', 'color');
        if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
            $allFacultiesQuery->whereIn('id', $accessibleFacultyIds);
        }
        $allFaculties = $allFacultiesQuery->get();
        
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

    private function getMonthlyResponses($accessibleFacultyIds = null)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $query = Response::completed()
                ->whereYear('completed_at', $date->year)
                ->whereMonth('completed_at', $date->month);
            
            // Filter by faculty if provided
            if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
                $query->whereIn('faculty_id', $accessibleFacultyIds);
            }
            
            $count = $query->count();

            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        return $data;
    }

    private function getSatisfactionDistribution($accessibleFacultyIds = null)
    {
        $query = ResponseAnswer::select('rating', DB::raw('count(*) as count'));
        
        // Filter by faculty if provided
        if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
            $query->whereHas('response', function($q) use ($accessibleFacultyIds) {
                $q->whereIn('faculty_id', $accessibleFacultyIds);
            });
        }
        
        $distribution = $query->groupBy('rating')
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

    private function getSubcategoryRatings($accessibleFacultyIds = null)
    {
        $query = DB::table('sub_categories')
            ->leftJoin('questions', 'sub_categories.id', '=', 'questions.sub_category_id')
            ->leftJoin('response_answers', 'questions.id', '=', 'response_answers.question_id')
            ->leftJoin('responses', 'response_answers.response_id', '=', 'responses.id')
            ->select('sub_categories.name', 'sub_categories.order', DB::raw('AVG(response_answers.rating) as average_rating'))
            ->where(function($q) {
                $q->where('responses.is_completed', true)
                  ->whereNotNull('responses.completed_at')
                  ->orWhereNull('responses.id');
            })
            ->groupBy('sub_categories.id', 'sub_categories.name', 'sub_categories.order')
            ->orderBy('sub_categories.order');
        
        // Filter by faculty if provided
        if ($accessibleFacultyIds !== null && !empty($accessibleFacultyIds)) {
            $query->where(function($q) use ($accessibleFacultyIds) {
                $q->whereIn('responses.faculty_id', $accessibleFacultyIds)
                  ->orWhereNull('responses.id');
            });
        }
        
        $subcategories = $query->get();

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
