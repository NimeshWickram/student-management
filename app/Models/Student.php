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
                $student->password = bcrypt('student123'); // Default password for students
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
     * Get the tenant that owns the student.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
