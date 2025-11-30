<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class AnalyticsExport implements WithMultipleSheets
{
    protected $data;
    protected $isSuperAdmin;

    public function __construct($data, $isSuperAdmin = true)
    {
        $this->data = $data;
        $this->isSuperAdmin = $isSuperAdmin;
    }

    public function sheets(): array
    {
        return [
            new AnalyticsSummarySheet($this->data),
            new FacultyRatingsSheet($this->data),
            new SatisfactionLevelsSheet($this->data),
            new MonthlyTrendsSheet($this->data),
            new TopQuestionnairesSheet($this->data),
        ];
    }
}

class AnalyticsSummarySheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function headings(): array
    {
        return [
            'Metrik',
            'Nilai',
        ];
    }

    public function collection()
    {
        return collect([
            ['Total Respon', $this->data['totalResponses']],
            ['Total Fakultas', $this->data['totalFaculties']],
            ['Kuisioner Aktif', $this->data['totalQuestionnaires']],
            ['Rating Rata-rata', number_format($this->data['averageRating'], 2) . '/5'],
        ]);
    }
}

class FacultyRatingsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Rating per Fakultas';
    }

    public function headings(): array
    {
        return [
            'Fakultas',
            'Rating Rata-rata',
            'Jumlah Respon',
        ];
    }

    public function collection()
    {
        return collect($this->data['facultyRatings'])->map(function ($faculty) {
            return [
                $faculty['name'],
                number_format($faculty['rating'], 1),
                $faculty['responses'],
            ];
        });
    }
}

class SatisfactionLevelsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Distribusi Kepuasan';
    }

    public function headings(): array
    {
        return [
            'Tingkat Kepuasan',
            'Jumlah Respon',
            'Persentase',
        ];
    }

    public function collection()
    {
        $total = array_sum($this->data['satisfactionLevels']);

        return collect($this->data['satisfactionLevels'])->map(function ($count, $level) use ($total) {
            return [
                $level,
                $count,
                $total > 0 ? number_format(($count / $total) * 100, 2) . '%' : '0%',
            ];
        });
    }
}

class MonthlyTrendsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Tren Bulanan';
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Jumlah Respon',
        ];
    }

    public function collection()
    {
        return collect($this->data['monthlyTrends'])->map(function ($trend) {
            return [
                $trend['month'],
                $trend['count'],
            ];
        });
    }
}

class TopQuestionnairesSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Top Kuisioner';
    }

    public function headings(): array
    {
        return [
            'Judul Kuisioner',
            'Fakultas',
            'Jumlah Respon',
        ];
    }

    public function collection()
    {
        return collect($this->data['topQuestionnaires'])->map(function ($questionnaire) {
            return [
                $questionnaire->title,
                $questionnaire->faculty->name,
                $questionnaire->responses_count,
            ];
        });
    }
}

class QuestionnaireRatingsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Rating per Kuisioner';
    }

    public function headings(): array
    {
        return [
            'Judul Kuisioner',
            'Rating Rata-rata',
            'Jumlah Respon',
        ];
    }

    public function collection()
    {
        return collect($this->data['questionnaireRatings'])->map(function ($questionnaire) {
            return [
                $questionnaire['title'],
                number_format($questionnaire['rating'], 1),
                $questionnaire['responses'],
            ];
        });
    }
}
