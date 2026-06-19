<?php

namespace App\Imports;

use App\Models\Student;

class StudentImport
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
     * Import students from a CSV/Excel file.
     *
     * Expected columns: First Name, Last Name, Email, Phone Number, Course, Grade
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

        // Normalize headers (trim and lowercase)
        $header = array_map(function ($h) {
            return strtolower(trim(str_replace(['_', '-'], ' ', (string) $h)));
        }, $header);

        $rowNumber = 1;
        foreach ($rows as $row) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $data = $this->mapRow($header, $row);

            if (!$data) {
                $this->errors[] = "Row {$rowNumber}: Could not map columns. Ensure headers include: First Name, Last Name, Email, Phone Number, Course.";
                $this->skipped++;
                continue;
            }

            // Validate required fields
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $this->errors[] = "Row {$rowNumber}: Missing required fields (first name, last name, or email).";
                $this->skipped++;
                continue;
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "Row {$rowNumber}: Invalid email format '{$data['email']}'.";
                $this->skipped++;
                continue;
            }

            // Validate phone number format
            if (!empty($data['phone_number']) && !preg_match('/^(0[0-9]{9}|[1-9][0-9]{8})$/', $data['phone_number'])) {
                $this->errors[] = "Row {$rowNumber}: Invalid phone number format '{$data['phone_number']}'. Must be 10 digits if starting with 0, or 9 digits otherwise.";
                $this->skipped++;
                continue;
            }

            // Skip duplicates
            if (Student::where('email', $data['email'])->exists()) {
                $this->skipped++;
                continue;
            }

            // Assign tenant_id and normalize grade
            $data['tenant_id'] = $this->tenantId;
            if (empty($data['grade'])) {
                $data['grade'] = 'Grade 1';
            } else {
                $gradeVal = strtolower(trim($data['grade']));
                preg_match('/(1[0-1]|[1-9])/', $gradeVal, $matches);
                if (!empty($matches[0])) {
                    $data['grade'] = 'Grade ' . $matches[0];
                } else {
                    $data['grade'] = 'Grade 1';
                }
            }

            try {
                Student::create($data);
                $this->imported++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
                $this->skipped++;
            }
        }

        return $this;
    }

    /**
     * Map a CSV row to student fields based on header positions.
     */
    protected function mapRow(array $header, array $row): ?array
    {
        $mapping = [
            'first name' => 'first_name',
            'first_name' => 'first_name',
            'firstname'  => 'first_name',
            'last name'  => 'last_name',
            'last_name'  => 'last_name',
            'lastname'   => 'last_name',
            'email'      => 'email',
            'email address' => 'email',
            'phone'      => 'phone_number',
            'phone number' => 'phone_number',
            'phone_number' => 'phone_number',
            'phonenumber'  => 'phone_number',
            'mobile'     => 'phone_number',
            'course'     => 'course',
            'program'    => 'course',
            'grade'      => 'grade',
            'class'      => 'grade',
        ];

        $data = [];
        $mapped = false;

        foreach ($header as $index => $col) {
            $col = strtolower(trim($col));
            // Remove '#' column
            if ($col === '#' || $col === 'no' || $col === 'number' || $col === 'id') {
                continue;
            }
            // Skip date columns
            if (strpos($col, 'date') !== false || strpos($col, 'registered') !== false || strpos($col, 'created') !== false) {
                continue;
            }

            if (isset($mapping[$col]) && isset($row[$index])) {
                $data[$mapping[$col]] = trim($row[$index]);
                $mapped = true;
            }
        }

        // If no mapping found, try positional (skip first column if it's a number)
        if (!$mapped && count($row) >= 5) {
            $offset = is_numeric(trim($row[0])) ? 1 : 0;
            $data = [
                'first_name'   => trim($row[$offset] ?? ''),
                'last_name'    => trim($row[$offset + 1] ?? ''),
                'email'        => trim($row[$offset + 2] ?? ''),
                'phone_number' => trim($row[$offset + 3] ?? ''),
                'course'       => trim($row[$offset + 4] ?? ''),
                'grade'        => trim($row[$offset + 5] ?? 'Grade 1'),
            ];
        }

        return !empty($data) ? $data : null;
    }

    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int { return $this->skipped; }
    public function getErrors(): array { return $this->errors; }
}
