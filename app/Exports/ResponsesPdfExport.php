<?php

namespace App\Exports;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ResponsesPdfExport
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Generate PDF from responses data
     */
    public function generatePdf()
    {
        $responses = $this->getFilteredResponses();

        $data = [
            'responses' => $responses,
            'title' => 'Laporan Respon Survei',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'filters' => $this->getAppliedFilters()
        ];

        $pdf = Pdf::loadView('exports.responses-pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf;
    }

    /**
     * Get filtered responses based on request parameters
     */
    private function getFilteredResponses()
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
     * Get applied filters for display in PDF
     */
    private function getAppliedFilters()
    {
        $filters = [];

        if ($this->request) {
            if ($this->request->faculty_id) {
                $faculty = \App\Models\Faculty::find($this->request->faculty_id);
                $filters[] = 'Fakultas: ' . ($faculty ? $faculty->name : 'Tidak ditemukan');
            }

            if ($this->request->questionnaire_id) {
                $questionnaire = \App\Models\Questionnaire::find($this->request->questionnaire_id);
                $filters[] = 'Kuisioner: ' . ($questionnaire ? $questionnaire->title : 'Tidak ditemukan');
            }

            if ($this->request->start_date) {
                $filters[] = 'Tanggal Mulai: ' . $this->request->start_date;
            }

            if ($this->request->end_date) {
                $filters[] = 'Tanggal Akhir: ' . $this->request->end_date;
            }

            if ($this->request->min_rating) {
                $filters[] = 'Rating Minimum: ' . $this->request->min_rating;
            }

            if ($this->request->max_rating) {
                $filters[] = 'Rating Maksimum: ' . $this->request->max_rating;
            }

            if ($this->request->search) {
                $filters[] = 'Pencarian: ' . $this->request->search;
            }
        }

        return $filters;
    }
}
