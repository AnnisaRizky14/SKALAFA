<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'faculty_id',
        'response_token',
        'session_id',
        'participant_info',
        'is_completed',
        'started_at',
        'completed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'participant_info' => 'array',
        'is_completed'     => 'boolean',
        'started_at'       => 'datetime',
        'completed_at'     => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->response_token)) {
                $model->response_token = Str::random(32);
            }
        });

        static::created(function ($model) {
            // Create notification when response is completed
            if ($model->is_completed) {
                \App\Models\Notification::createSurveyNotification($model);
            }
        });

        static::updated(function ($model) {
            // Create notification when response becomes completed
            if ($model->is_completed && $model->wasChanged('is_completed')) {
                \App\Models\Notification::createSurveyNotification($model);
            }
        });
    }

    // ----------------- Relations -----------------
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ResponseAnswer::class);
    }

    // ----------------- Scopes -----------------
    /** Respon yang benar-benar selesai */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true)
                     ->whereNotNull('completed_at');
    }

    /** Di bulan berjalan berdasarkan completed_at */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereYear('completed_at', now()->year)
                     ->whereMonth('completed_at', now()->month);
    }

    /** Di minggu berjalan berdasarkan completed_at */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('completed_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /** Utility: antara tanggal (default pakai completed_at) */
    public function scopeBetween(Builder $query, $start, $end, string $column = 'completed_at'): Builder
    {
        return $query->whereBetween($column, [$start, $end]);
    }

    // ----------------- Helpers -----------------
    public function getAverageRating(): float
    {
        return round($this->answers()->avg('rating') ?? 0, 2);
    }

    public function getProgressPercentage(): float
    {
        $totalQuestions    = $this->questionnaire->questions()->count();
        $answeredQuestions = $this->answers()->count();

        return $totalQuestions > 0
            ? round(($answeredQuestions / $totalQuestions) * 100, 2)
            : 0.0;
    }

    public function getDurationInMinutes(): int
    {
        if (!$this->started_at || !$this->completed_at) {
            return 0;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    public function getSatisfactionLevel(): string
    {
        $avg = $this->getAverageRating();

        if ($avg >= 4.5) return 'Sangat Puas';
        if ($avg >= 3.5) return 'Puas';
        if ($avg >= 2.5) return 'Cukup Puas';
        if ($avg >= 1.5) return 'Tidak Puas';
        return 'Sangat Tidak Puas';
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('response_token', $token)->exists());

        return $token;
    }
}
