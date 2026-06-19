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
        'subject_id',
        'name',
        'code',
        'description',
        'credits',
        'tenant_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($subject) {
            if (empty($subject->subject_id)) {
                $last = static::max('id') ?? 0;
                $subject->subject_id = 'SUB-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

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
