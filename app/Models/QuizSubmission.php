<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'quiz_id',
        'score',
        'total_questions',
        'correct_answers',
        'answers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answers' => 'array',
    ];

    /**
     * Get the student that took the quiz.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the quiz that was taken.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
