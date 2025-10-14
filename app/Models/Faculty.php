<?php
// app/Models/Faculty.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'logo',
        'color',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function activeQuestionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class)->where('is_active', true);
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('images/faculties/' . $this->logo) : asset('images/default-faculty.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Get satisfaction statistics
    public function getSatisfactionStats()
    {
        $responses = $this->responses()->with('answers')->where('is_completed', true)->get();

        if ($responses->isEmpty()) {
            return [
                'total_responses' => 0,
                'average_rating' => 0,
                'average_satisfaction' => 0,
                'satisfaction_level' => 'Belum ada data'
            ];
        }

        $totalRating = 0;
        $totalAnswers = 0;

        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                $totalRating += $answer->rating;
                $totalAnswers++;
            }
        }

        $averageRating = $totalAnswers > 0 ? $totalRating / $totalAnswers : 0;

        return [
            'total_responses' => $responses->count(),
            'average_rating' => round($averageRating, 1),
            'average_satisfaction' => round($averageRating * 20, 1), // Convert to percentage
            'satisfaction_level' => $this->getSatisfactionLevel($averageRating)
        ];
    }

    private function getSatisfactionLevel($rating): string
    {
        if ($rating >= 4.5) return 'Sangat Puas';
        if ($rating >= 3.5) return 'Puas';
        if ($rating >= 2.5) return 'Cukup Puas';
        if ($rating >= 1.5) return 'Tidak Puas';
        return 'Sangat Tidak Puas';
    }
}