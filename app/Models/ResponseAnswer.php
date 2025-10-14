<?php
// app/Models/ResponseAnswer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponseAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_id',
        'rating',
        'comment'
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function getRatingText(): string
    {
        return match($this->rating) {
            1 => 'Sangat Tidak Puas',
            2 => 'Tidak Puas',
            3 => 'Cukup Puas',
            4 => 'Puas',
            5 => 'Sangat Puas',
            default => 'Tidak Diketahui'
        };
    }

    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }
}