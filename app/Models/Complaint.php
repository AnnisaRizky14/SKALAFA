<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'email',
        'status',
        'admin_notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            // Create notification when complaint is created
            \App\Models\Notification::createComplaintNotification($model);
        });
    }
}
