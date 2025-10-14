<?php
// app/Models/SubCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Get average rating for this subcategory across all questionnaires
    public function getAverageRating($questionnaireId = null)
    {
        $query = $this->questions();
        
        if ($questionnaireId) {
            $query->where('questionnaire_id', $questionnaireId);
        }

        $questions = $query->with('answers')->get();
        
        if ($questions->isEmpty()) {
            return 0;
        }

        $totalRating = 0;
        $totalAnswers = 0;

        foreach ($questions as $question) {
            foreach ($question->answers as $answer) {
                $totalRating += $answer->rating;
                $totalAnswers++;
            }
        }

        return $totalAnswers > 0 ? round($totalRating / $totalAnswers, 2) : 0;
    }
}