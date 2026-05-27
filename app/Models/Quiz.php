<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'title',
        'quiz_type',
        'manual_content',
        'pdf_file_path',
        'tenant_id',
        'grade',
    ];

    /**
     * Get the teacher that owns the quiz.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject that owns the quiz.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the tenant that owns the quiz.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
