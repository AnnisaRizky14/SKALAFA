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
        // Statistik umum
        $totalResponses = Response::count();
        $totalFaculties = Faculty::count();
        $totalQuestionnaires = Questionnaire::active()->count();

        // Rating rata-rata keseluruhan (sesuai dengan DashboardController)
        $averageRating = ResponseAnswer::avg('rating') ?? 0;

        // Data untuk chart rating per fakultas
        $facultyRatings = Faculty::with(['questionnaires.responses.answers'])->get()->map(function ($faculty) {
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

        // Hitung distribusi kepuasan berdasarkan rating rata-rata per respon
        foreach (Response::with('answers')->get() as $response) {
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

        // Tren respon bulanan (6 bulan terakhir)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Response::whereYear('completed_at', $date->year)
                            ->whereMonth('completed_at', $date->month)
                            ->count();
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Top questionnaires berdasarkan jumlah respon
        $topQuestionnaires = Questionnaire::withCount('responses')
                                        ->orderBy('responses_count', 'desc')
                                        ->take(5)
                                        ->get();

        return view('admin.analytics.index', compact(
            'totalResponses',
            'totalFaculties',
            'totalQuestionnaires',
            'averageRating',
            'facultyRatings',
            'satisfactionLevels',
            'monthlyTrends',
            'topQuestionnaires'
        ));
    }

    public function export()
    {
        // Statistik umum
        $totalResponses = Response::count();
        $totalFaculties = Faculty::count();
        $totalQuestionnaires = Questionnaire::active()->count();

        // Rating rata-rata keseluruhan
        $averageRating = ResponseAnswer::avg('rating') ?? 0;

        // Data untuk chart rating per fakultas
        $facultyRatings = Faculty::with(['questionnaires.responses.answers'])->get()->map(function ($faculty) {
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

        // Hitung distribusi kepuasan berdasarkan rating rata-rata per respon
        foreach (Response::with('answers')->get() as $response) {
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

        // Tren respon bulanan (6 bulan terakhir)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Response::whereYear('completed_at', $date->year)
                            ->whereMonth('completed_at', $date->month)
                            ->count();
            $monthlyTrends[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Top questionnaires berdasarkan jumlah respon
        $topQuestionnaires = Questionnaire::withCount('responses')
                                        ->orderBy('responses_count', 'desc')
                                        ->take(5)
                                        ->get();

        // Buat data untuk export
        $exportData = [
            'totalResponses' => $totalResponses,
            'totalFaculties' => $totalFaculties,
            'totalQuestionnaires' => $totalQuestionnaires,
            'averageRating' => $averageRating,
            'facultyRatings' => $facultyRatings,
            'satisfactionLevels' => $satisfactionLevels,
            'monthlyTrends' => $monthlyTrends,
            'topQuestionnaires' => $topQuestionnaires,
        ];

        $filename = 'laporan_analitik_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new \App\Exports\AnalyticsExport($exportData), $filename);
    }
}
