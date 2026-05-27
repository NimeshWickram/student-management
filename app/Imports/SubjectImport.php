<?php

namespace App\Imports;

use App\Models\Subject;

class SubjectImport
{
    protected $imported = 0;
    protected $skipped = 0;
    protected $errors = [];
    protected $tenantId;

    public function __construct($tenantId = null)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Import subjects from a CSV/Excel file.
     *
     * Expected columns: Name, Code, Description, Credits
     * The first row is treated as headers and skipped.
     */
    public function import($filePath, $extension = 'csv')
    {
        try {
            $rows = SpreadsheetReader::read($filePath, $extension);
        } catch (\Exception $e) {
            $this->errors[] = 'Could not parse the file: ' . $e->getMessage();
            return $this;
        }

        if (empty($rows)) {
            $this->errors[] = 'The file appears to be empty.';
            return $this;
        }

        // Extract header
        $header = array_shift($rows);

        $header = array_map(function ($h) {
            return strtolower(trim(str_replace(['_', '-'], ' ', (string) $h)));
        }, $header);

        $rowNumber = 1;
        foreach ($rows as $row) {
            $rowNumber++;
            if (empty(array_filter($row))) continue;

            $data = $this->mapRow($header, $row);

            if (!$data) {
                $this->errors[] = "Row {$rowNumber}: Could not map columns. Ensure headers include: Name, Code, Credits.";
                $this->skipped++;
                continue;
            }

            if (empty($data['name']) || empty($data['code'])) {
                $this->errors[] = "Row {$rowNumber}: Missing required fields (name or code).";
                $this->skipped++;
                continue;
            }

            // Skip duplicate codes
            if (Subject::where('code', $data['code'])->exists()) {
                $this->skipped++;
                continue;
            }

            // Default credits if not provided
            if (empty($data['credits'])) {
                $data['credits'] = 3;
            }

            // Assign tenant_id
            $data['tenant_id'] = $this->tenantId;

            try {
                Subject::create($data);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                $this->skipped++;
            }
        }

        return $this;
    }

    protected function mapRow(array $header, array $row): ?array
    {
        $mapping = [
            'name'          => 'name',
            'subject name'  => 'name',
            'subject'       => 'name',
            'title'         => 'name',
            'code'          => 'code',
            'subject code'  => 'code',
            'course code'   => 'code',
            'description'   => 'description',
            'desc'          => 'description',
            'credits'       => 'credits',
            'credit'        => 'credits',
            'credit hours'  => 'credits',
        ];

        $data = [];
        $mapped = false;

        foreach ($header as $index => $col) {
            $col = strtolower(trim($col));
            if ($col === '#' || $col === 'no' || $col === 'number' || $col === 'id') continue;
            if (strpos($col, 'date') !== false || strpos($col, 'added') !== false || strpos($col, 'created') !== false) continue;

            if (isset($mapping[$col]) && isset($row[$index])) {
                $data[$mapping[$col]] = trim($row[$index]);
                $mapped = true;
            }
        }

        if (!$mapped && count($row) >= 3) {
            $offset = is_numeric(trim($row[0])) ? 1 : 0;
            $data = [
                'name'        => trim($row[$offset] ?? ''),
                'code'        => trim($row[$offset + 1] ?? ''),
                'description' => trim($row[$offset + 2] ?? ''),
                'credits'     => trim($row[$offset + 3] ?? '3'),
            ];
        }

        return !empty($data) ? $data : null;
    }

    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int { return $this->skipped; }
    public function getErrors(): array { return $this->errors; }
}
