<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Response::with(['questionnaire', 'faculty', 'answers.question'])
            ->completed()
            ->latest();

        // Apply same filters as in the controller
        if ($this->request && $this->request->faculty_id) {
            $query->where('faculty_id', $this->request->faculty_id);
        }

        if ($this->request && $this->request->questionnaire_id) {
            $query->where('questionnaire_id', $this->request->questionnaire_id);
        }

        if ($this->request && $this->request->start_date) {
            $query->whereDate('completed_at', '>=', $this->request->start_date);
        }

        if ($this->request && $this->request->end_date) {
            $query->whereDate('completed_at', '<=', $this->request->end_date);
        }

        if ($this->request && ($this->request->min_rating || $this->request->max_rating)) {
            $having = '1=1';
            $params = [];
            if ($this->request->min_rating) {
                $having .= ' AND AVG(response_answers.rating) >= ?';
                $params[] = $this->request->min_rating;
            }
            if ($this->request->max_rating) {
                $having .= ' AND AVG(response_answers.rating) <= ?';
                $params[] = $this->request->max_rating;
            }

            $query->whereExists(function ($subQuery) use ($having, $params) {
                $subQuery->select(DB::raw(1))
                         ->from('response_answers')
                         ->whereColumn('response_answers.response_id', 'responses.id')
                         ->groupBy('response_answers.response_id')
                         ->havingRaw($having, $params);
            });
        }

        if ($this->request && $this->request->search) {
            $search = $this->request->search;
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

        return $query->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            'ID Respon',
            'Fakultas',
            'Kuisioner',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Rating Rata-rata',
            'Tingkat Kepuasan',
            'Durasi (Menit)',
            'Jumlah Jawaban',
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($response): array
    {
        return [
            $response->id,
            $response->faculty->name,
            $response->questionnaire->title,
            $response->started_at ? $response->started_at->format('d/m/Y H:i') : '-',
            $response->completed_at ? $response->completed_at->format('d/m/Y H:i') : '-',
            number_format($response->getAverageRating(), 2),
            $response->getSatisfactionLevel(),
            $response->getDurationInMinutes(),
            $response->answers->count(),
        ];
    }
}
