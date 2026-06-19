<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'course',
        'grade',
        'password',
        'tenant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Boot logic for automatically setting the default password.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->password)) {
                $student->password = bcrypt('student123');
            }
            if (empty($student->student_id)) {
                $last = static::max('id') ?? 0;
                $student->student_id = 'STU-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Submissions relationship.
     */
    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }

    /**
     * Get the student's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the tenant that owns the student.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
