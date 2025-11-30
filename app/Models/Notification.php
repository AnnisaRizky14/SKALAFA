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

    public static function createQuestionnaireStatusNotification($questionnaire, $isActivated)
    {
        $action = $isActivated ? 'diaktifkan' : 'dinonaktifkan';
        return static::create([
            'type' => 'questionnaire_status',
            'reference_id' => $questionnaire->id,
            'title' => "Kuisioner {$action}",
            'message' => "Kuisioner '{$questionnaire->title}' telah {$action} oleh admin.",
        ]);
    }

    public static function createQuestionnaireDeletionNotification($questionnaire)
    {
        return static::create([
            'type' => 'questionnaire_deletion',
            'reference_id' => $questionnaire->id,
            'title' => 'Kuisioner Dihapus',
            'message' => "Kuisioner '{$questionnaire->title}' telah dihapus oleh admin.",
        ]);
    }

    // Relationships
    public function response()
    {
        return $this->belongsTo(Response::class, 'reference_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'reference_id');
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'reference_id');
    }

    public function getRelatedModel()
    {
        switch ($this->type) {
            case 'survey_response':
                return Response::with(['questionnaire', 'faculty'])->find($this->reference_id);
            case 'complaint':
                return Complaint::find($this->reference_id);
            case 'questionnaire_status':
            case 'questionnaire_deletion':
                return Questionnaire::with('faculty')->find($this->reference_id);
            default:
                return null;
        }
    }

    // Scope to filter notifications by accessible faculties
    public function scopeForFaculties($query, array $facultyIds)
    {
        return $query->where(function($q) use ($facultyIds) {
            $q->where('type', 'survey_response')
              ->whereHas('response', function($subQ) use ($facultyIds) {
                  $subQ->whereIn('faculty_id', $facultyIds);
              })
              ->orWhere('type', 'questionnaire_status')
              ->whereHas('questionnaire', function($subQ) use ($facultyIds) {
                  $subQ->whereIn('faculty_id', $facultyIds);
              })
              ->orWhere('type', 'questionnaire_deletion')
              ->whereHas('questionnaire', function($subQ) use ($facultyIds) {
                  $subQ->whereIn('faculty_id', $facultyIds);
              });
        });
    }
}
