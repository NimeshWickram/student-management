<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'teacher_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'subject',
        'password',
        'tenant_id',
        'salutation',
        'gender',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            if (empty($teacher->password)) {
                $teacher->password = bcrypt('teacher123');
            }
            if (empty($teacher->teacher_id)) {
                $last = static::max('id') ?? 0;
                $teacher->teacher_id = 'TCH-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
