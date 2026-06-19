<?php

namespace App\Exports;

use App\Models\Teacher;

class TeacherExport
{
    protected $search;
    protected $tenantId;

    public function __construct($search = null, $tenantId = null)
    {
        $this->search = $search;
        $this->tenantId = $tenantId;
    }

    protected function query()
    {
        $query = Teacher::query();

        if ($this->tenantId) {
            $query->where('tenant_id', $this->tenantId);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function getRecords()
    {
        return $this->query()->get();
    }

    public function downloadExcel()
    {
        $teachers = $this->query()->get();
        $filename = 'teachers_' . date('Y-m-d_His') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Teachers</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body><table border="1">';
        $html .= '<tr style="background-color:#0a0a0a;color:#ffffff;font-weight:bold;font-size:13px">';
        $html .= '<th>#</th><th>Title (Salutation)</th><th>First Name</th><th>Last Name</th><th>Gender</th><th>Email</th><th>Phone Number</th><th>Subject</th><th>Joined Date</th>';
        $html .= '</tr>';

        foreach ($teachers as $index => $teacher) {
            $bg = $index % 2 === 0 ? '#ffffff' : '#f5f5f5';
            $html .= "<tr style=\"background-color:{$bg};font-size:12px\">";
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . e($teacher->salutation ?? '—') . '</td>';
            $html .= '<td>' . e($teacher->first_name) . '</td>';
            $html .= '<td>' . e($teacher->last_name) . '</td>';
            $html .= '<td>' . e(ucfirst($teacher->gender ?? '—')) . '</td>';
            $html .= '<td>' . e($teacher->email) . '</td>';
            $html .= '<td>' . e($teacher->phone_number) . '</td>';
            $html .= '<td>' . e($teacher->subject) . '</td>';
            $html .= '<td>' . $teacher->created_at->format('d M Y') . '</td>';
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
