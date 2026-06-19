<?php

namespace App\Exports;

use App\Models\Subject;

class SubjectExport
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
        $query = Subject::query();

        if ($this->tenantId) {
            $query->where('tenant_id', $this->tenantId);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('credits', 'LIKE', "%{$search}%");
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
        $subjects = $this->query()->get();
        $filename = 'subjects_' . date('Y-m-d_His') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Subjects</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body><table border="1">';
        $html .= '<tr style="background-color:#0a0a0a;color:#ffffff;font-weight:bold;font-size:13px">';
        $html .= '<th>#</th><th>Name</th><th>Code</th><th>Description</th><th>Credits</th><th>Added Date</th>';
        $html .= '</tr>';

        foreach ($subjects as $index => $subject) {
            $bg = $index % 2 === 0 ? '#ffffff' : '#f5f5f5';
            $html .= "<tr style=\"background-color:{$bg};font-size:12px\">";
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . e($subject->name) . '</td>';
            $html .= '<td>' . e($subject->code) . '</td>';
            $html .= '<td>' . e($subject->description) . '</td>';
            $html .= '<td>' . $subject->credits . '</td>';
            $html .= '<td>' . $subject->created_at->format('d M Y') . '</td>';
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
