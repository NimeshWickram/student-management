<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'credits',
        'tenant_id',
    ];

    /**
     * Get the quizzes for the subject.
     */
    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the tenant that owns the subject.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
