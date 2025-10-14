<?php
// app/Models/Questionnaire.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'sub_category_id',
        'title',
        'description',
        'instructions',
        'estimated_duration',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function completedResponses(): HasMany
    {
        return $this->hasMany(Response::class)->where('is_completed', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->where('start_date', '<=', $now)
                          ->orWhereNull('start_date');
                    })
                    ->where(function($q) use ($now) {
                        $q->where('end_date', '>=', $now)
                          ->orWhereNull('end_date');
                    });
    }

    public function getQuestionsGroupedBySubCategory()
    {
        return $this->questions()
                    ->with('subCategory')
                    ->orderBy('sub_category_id')
                    ->orderBy('order')
                    ->get()
                    ->groupBy('sub_category_id');
    }

    public function getAverageRating()
    {
        $responses = $this->completedResponses()->with('answers')->get();
        
        if ($responses->isEmpty()) {
            return 0;
        }

        $totalRating = 0;
        $totalAnswers = 0;

        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                $totalRating += $answer->rating;
                $totalAnswers++;
            }
        }

        return $totalAnswers > 0 ? round($totalRating / $totalAnswers, 2) : 0;
    }

    public function getCompletionRate()
    {
        $totalResponses = $this->responses()->count();
        $completedResponses = $this->completedResponses()->count();

        return $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 2) : 0;
    }

    public function getTotalQuestions()
    {
        return $this->questions()->count();
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now()->toDate();

        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }
}