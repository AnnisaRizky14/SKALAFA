<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'reference_id',
        'title',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    // Static methods for creating notifications
    public static function createSurveyNotification($response)
    {
        return static::create([
            'type' => 'survey_response',
            'reference_id' => $response->id,
            'title' => 'Respon Survei Baru',
            'message' => "Survei '{$response->questionnaire->title}' telah diisi oleh {$response->faculty->short_name}",
        ]);
    }

    public static function createComplaintNotification($complaint)
    {
        return static::create([
            'type' => 'complaint',
            'reference_id' => $complaint->id,
            'title' => 'Pengaduan Baru',
            'message' => "Pengaduan baru: {$complaint->title}",
        ]);
    }

    // Relationships
    public function getRelatedModel()
    {
        switch ($this->type) {
            case 'survey_response':
                return Response::with(['questionnaire', 'faculty'])->find($this->reference_id);
            case 'complaint':
                return Complaint::find($this->reference_id);
            default:
                return null;
        }
    }
}
