<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Faculty;
use App\Models\Questionnaire;
use App\Models\ResponseAnswer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $accessibleFacultyIds = $user->getAccessibleFacultyIds();

        // Statistik umum - filter berdasarkan faculty yang dapat diakses
        $totalResponses = Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->count();

        $totalFaculties = Faculty::whereIn('id', $accessibleFacultyIds)->count();
        $totalQuestionnaires = Questionnaire::active()->whereIn('faculty_id', $accessibleFacultyIds)->count();

        // Rating rata-rata keseluruhan - filter berdasarkan faculty yang dapat diakses
        $averageRating = ResponseAnswer::whereHas('response.questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->avg('rating') ?? 0;

        // Data untuk chart rating per kuisioner - hanya kuisioner dari faculty yang dapat diakses
        $questionnaireRatings = Questionnaire::whereIn('faculty_id', $accessibleFacultyIds)
            ->with(['responses.answers'])
            ->get()
            ->map(function ($questionnaire) {
                $responses = $questionnaire->responses;
                $avgRating = $responses->avg(function ($response) {
                    return $response->getAverageRating();
                });

                return [
                    'title' => $questionnaire->title,
                    'rating' => round($avgRating, 1),
                    'responses' => $responses->count(),
                ];
            })->filter(function ($questionnaire) {
                return $questionnaire['responses'] > 0;
            })->values();

        // Data untuk chart rating per fakultas - hanya untuk super admin
        $facultyRatings = Faculty::whereIn('id', $accessibleFacultyIds)
            ->with(['questionnaires.responses.answers'])
            ->get()
            ->map(function ($faculty) {
                $responses = $faculty->questionnaires->flatMap->responses;
                $avgRating = $responses->avg(function ($response) {
                    return $response->getAverageRating();
                });

                return [
                    'name' => $faculty->name,
                    'rating' => round($avgRating, 1),
                    'responses' => $responses->count(),
                ];
            })->filter(function ($faculty) {
                return $faculty['responses'] > 0;
            })->values();

        // Distribusi tingkat kepuasan
        $satisfactionLevels = [
            'Sangat Puas' => 0,
            'Puas' => 0,
            'Cukup Puas' => 0,
            'Tidak Puas' => 0,
            'Sangat Tidak Puas' => 0,
        ];

        // Hitung distribusi kepuasan berdasarkan rating rata-rata per respon - filter berdasarkan faculty yang dapat diakses
        foreach (Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->with('answers')->get() as $response) {
            $avgRating = $response->getAverageRating();
            if ($avgRating >= 4.5) {
                $satisfactionLevels['Sangat Puas']++;
            } elseif ($avgRating >= 3.5) {
                $satisfactionLevels['Puas']++;
            } elseif ($avgRating >= 2.5) {
                $satisfactionLevels['Cukup Puas']++;
            } elseif ($avgRating >= 1.5) {
                $satisfactionLevels['Tidak Puas']++;
            } else {
                $satisfactionLevels['Sangat Tidak Puas']++;
            }
        }

        // Tren respon bulanan (6 bulan terakhir) - filter berdasarkan faculty yang dapat diakses
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
                $query->whereIn('faculty_id', $accessibleFacultyIds);
            })->whereYear('completed_at', $date->year)
              ->whereMonth('completed_at', $date->month)
              ->count();
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Top questionnaires berdasarkan jumlah respon - hanya untuk faculty yang dapat diakses
        $topQuestionnaires = Questionnaire::whereIn('faculty_id', $accessibleFacultyIds)
                                        ->withCount('responses')
                                        ->orderBy('responses_count', 'desc')
                                        ->take(5)
                                        ->get();

        return view('admin.analytics.index', compact(
            'totalResponses',
            'totalFaculties',
            'totalQuestionnaires',
            'averageRating',
            'questionnaireRatings',
            'facultyRatings',
            'satisfactionLevels',
            'monthlyTrends',
            'topQuestionnaires'
        ));
    }

    public function export()
    {
        $user = auth()->user();
        $accessibleFacultyIds = $user->getAccessibleFacultyIds();

        // Statistik umum - filter berdasarkan faculty yang dapat diakses
        $totalResponses = Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->count();

        $totalFaculties = Faculty::whereIn('id', $accessibleFacultyIds)->count();
        $totalQuestionnaires = Questionnaire::active()->whereIn('faculty_id', $accessibleFacultyIds)->count();

        // Rating rata-rata keseluruhan - filter berdasarkan faculty yang dapat diakses
        $averageRating = ResponseAnswer::whereHas('response.questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->avg('rating') ?? 0;

        // Data untuk chart rating per kuisioner - hanya kuisioner dari faculty yang dapat diakses
        $questionnaireRatings = Questionnaire::whereIn('faculty_id', $accessibleFacultyIds)
            ->with(['responses.answers'])
            ->get()
            ->map(function ($questionnaire) {
                $responses = $questionnaire->responses;
                $avgRating = $responses->avg(function ($response) {
                    return $response->getAverageRating();
                });

                return [
                    'title' => $questionnaire->title,
                    'rating' => round($avgRating, 1),
                    'responses' => $responses->count(),
                ];
            })->filter(function ($questionnaire) {
                return $questionnaire['responses'] > 0;
            })->values();

        // Data untuk chart rating per fakultas - hanya untuk faculty yang dapat diakses
        $facultyRatings = Faculty::whereIn('id', $accessibleFacultyIds)
            ->with(['questionnaires.responses.answers'])
            ->get()
            ->map(function ($faculty) {
                $responses = $faculty->questionnaires->flatMap->responses;
                $avgRating = $responses->avg(function ($response) {
                    return $response->getAverageRating();
                });

                return [
                    'name' => $faculty->name,
                    'rating' => round($avgRating, 1),
                    'responses' => $responses->count(),
                ];
            })->filter(function ($faculty) {
                return $faculty['responses'] > 0;
            })->values();

        // Distribusi tingkat kepuasan
        $satisfactionLevels = [
            'Sangat Puas' => 0,
            'Puas' => 0,
            'Cukup Puas' => 0,
            'Tidak Puas' => 0,
            'Sangat Tidak Puas' => 0,
        ];

        // Hitung distribusi kepuasan berdasarkan rating rata-rata per respon - filter berdasarkan faculty yang dapat diakses
        foreach (Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
            $query->whereIn('faculty_id', $accessibleFacultyIds);
        })->with('answers')->get() as $response) {
            $avgRating = $response->getAverageRating();
            if ($avgRating >= 4.5) {
                $satisfactionLevels['Sangat Puas']++;
            } elseif ($avgRating >= 3.5) {
                $satisfactionLevels['Puas']++;
            } elseif ($avgRating >= 2.5) {
                $satisfactionLevels['Cukup Puas']++;
            } elseif ($avgRating >= 1.5) {
                $satisfactionLevels['Tidak Puas']++;
            } else {
                $satisfactionLevels['Sangat Tidak Puas']++;
            }
        }

        // Tren respon bulanan (6 bulan terakhir) - filter berdasarkan faculty yang dapat diakses
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Response::whereHas('questionnaire', function ($query) use ($accessibleFacultyIds) {
                $query->whereIn('faculty_id', $accessibleFacultyIds);
            })->whereYear('completed_at', $date->year)
              ->whereMonth('completed_at', $date->month)
              ->count();
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Top questionnaires berdasarkan jumlah respon - hanya untuk faculty yang dapat diakses
        $topQuestionnaires = Questionnaire::whereIn('faculty_id', $accessibleFacultyIds)
                                        ->withCount('responses')
                                        ->orderBy('responses_count', 'desc')
                                        ->take(5)
                                        ->get();

        // Buat data untuk export
        $exportData = [
            'totalResponses' => $totalResponses,
            'totalFaculties' => $totalFaculties,
            'totalQuestionnaires' => $totalQuestionnaires,
            'averageRating' => $averageRating,
            'questionnaireRatings' => $questionnaireRatings,
            'facultyRatings' => $facultyRatings,
            'satisfactionLevels' => $satisfactionLevels,
            'monthlyTrends' => $monthlyTrends,
            'topQuestionnaires' => $topQuestionnaires,
        ];

        $filename = 'laporan_analitik_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new \App\Exports\AnalyticsExport($exportData, $user->isSuperAdmin()), $filename);
    }
}
