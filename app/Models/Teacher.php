<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

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
        'subject',
        'tenant_id',
    ];

    /**
     * Get the quizzes for the teacher.
     */
    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the tenant that owns the teacher.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
