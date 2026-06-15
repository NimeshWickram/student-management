<?php

namespace App\Exports;

use App\Models\QuizSubmission;
use App\Models\Quiz;

class ResultExport
{
    protected $teacherId;
    protected $quizId;
    protected $grade;
    protected $search;
    protected $scoreRange;

    public function __construct($teacherId, $filters = [])
    {
        $this->teacherId = $teacherId;
        $this->quizId = $filters['quiz_id'] ?? null;
        $this->grade = $filters['grade'] ?? null;
        $this->search = $filters['search'] ?? null;
        $this->scoreRange = $filters['score_range'] ?? null;
    }

    /**
     * Build the query for submissions with active filters.
     */
    protected function query()
    {
        $quizIds = Quiz::where('teacher_id', $this->teacherId)->pluck('id');

        $query = QuizSubmission::whereIn('quiz_id', $quizIds)
            ->with(['student', 'quiz.subject']);

        if ($this->quizId) {
            $query->where('quiz_id', $this->quizId);
        }

        if ($this->grade) {
            $grade = $this->grade;
            $query->whereHas('quiz', function ($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        if ($this->scoreRange) {
            $range = $this->scoreRange;
            if ($range === 'below_35') {
                $query->where('score', '<', 35);
            } elseif ($range === 'above_35') {
                $query->where('score', '>=', 35);
            } elseif ($range === 'above_50') {
                $query->where('score', '>=', 50);
            } elseif ($range === 'above_65') {
                $query->where('score', '>=', 65);
            } elseif ($range === 'above_75') {
                $query->where('score', '>=', 75);
            }
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get records.
     */
    public function getRecords()
    {
        return $this->query()->get();
    }

    /**
     * Download Excel file.
     */
    public function downloadExcel()
    {
        $submissions = $this->query()->get();
        $filename = 'quiz_results_' . date('Y-m-d_His') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Quiz Submissions</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body><table border="1">';
        $html .= '<tr style="background-color:#4f46e5;color:#ffffff;font-weight:bold;font-size:13px">';
        $html .= '<th>#</th><th>Student Name</th><th>Student Email</th><th>Quiz Title</th><th>Subject</th><th>Grade</th><th>Score</th><th>Completed At</th>';
        $html .= '</tr>';

        foreach ($submissions as $index => $sub) {
            $bg = $index % 2 === 0 ? '#ffffff' : '#f4f4f5';
            $studentName = $sub->student->full_name ?? '—';
            $studentEmail = $sub->student->email ?? '—';
            $quizTitle = $sub->quiz->title ?? '—';
            $subject = $sub->quiz->subject->name ?? '—';
            $grade = $sub->quiz->grade ?? '—';
            $score = $sub->score . '%';
            $date = $sub->created_at->format('Y-m-d H:i');

            $html .= "<tr style=\"background-color:{$bg};font-size:12px\">";
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . e($studentName) . '</td>';
            $html .= '<td>' . e($studentEmail) . '</td>';
            $html .= '<td>' . e($quizTitle) . '</td>';
            $html .= '<td>' . e($subject) . '</td>';
            $html .= '<td>Grade ' . e($grade) . '</td>';
            $html .= '<td>' . e($score) . '</td>';
            $html .= '<td>' . $date . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ];

        return response($html, 200, $headers);
    }
}
