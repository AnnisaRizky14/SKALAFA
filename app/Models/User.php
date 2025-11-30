<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'faculty_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User belongs to Faculty
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Check if user is super admin (can access all faculties)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->faculty_id === null;
    }

    /**
     * Check if user is faculty admin (can only access their faculty)
     */
    public function isFacultyAdmin(): bool
    {
        return $this->role === 'faculty_admin' && $this->faculty_id !== null;
    }

    /**
     * Check if user is any type of admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'faculty_admin']);
    }

    /**
     * Check if user can access specific faculty data
     */
    public function canAccessFaculty(int $facultyId): bool
    {
        // Super admin can access all faculties
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Faculty admin can only access their own faculty
        if ($this->isFacultyAdmin()) {
            return $this->faculty_id === $facultyId;
        }

        return false;
    }

    /**
     * Get accessible faculty IDs for this user
     */
    public function getAccessibleFacultyIds(): array
    {
        // Super admin can access all faculties
        if ($this->isSuperAdmin()) {
            return Faculty::pluck('id')->toArray();
        }

        // Faculty admin can only access their own faculty
        if ($this->isFacultyAdmin() && $this->faculty_id) {
            return [$this->faculty_id];
        }

        return [];
    }

    /**
     * Scope: Filter users by faculty
     */
    public function scopeForFaculty($query, int $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    /**
     * Scope: Get only super admins
     */
    public function scopeSuperAdmins($query)
    {
        return $query->where('role', 'admin')->whereNull('faculty_id');
    }

    /**
     * Scope: Get only faculty admins
     */
    public function scopeFacultyAdmins($query)
    {
        return $query->where('role', 'faculty_admin')->whereNotNull('faculty_id');
    }
}
