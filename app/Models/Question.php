<?php
// app/Models/Question.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'sub_category_id',
        'question_text',
        'order',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean'
    ];

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getAverageRating()
    {
        $answers = $this->answers;
        
        if ($answers->isEmpty()) {
            return 0;
        }

        $totalRating = $answers->sum('rating');
        return round($totalRating / $answers->count(), 2);
    }

    public function getRatingDistribution()
    {
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        $answers = $this->answers;
        foreach ($answers as $answer) {
            $distribution[$answer->rating]++;
        }

        return $distribution;
    }

    public function getTotalResponses()
    {
        return $this->answers()->count();
    }
}