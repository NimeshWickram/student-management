<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class SpreadsheetReader
{
    /**
     * Read any supported spreadsheet file (CSV, XLS, XLSX) and return
     * an array of rows. Each row is a simple indexed array of cell values.
     *
     * @param  string  $filePath   Absolute path to the uploaded file.
     * @param  string  $extension  File extension (csv, xls, xlsx, txt).
     * @return array   Array of rows, where each row is an indexed array.
     */
    public static function read(string $filePath, string $extension): array
    {
        $ext = strtolower($extension);

        if (in_array($ext, ['csv', 'txt'])) {
            return self::readCsv($filePath);
        }

        return self::readSpreadsheet($filePath);
    }

    /**
     * Read a CSV/TXT file using native PHP.
     */
    protected static function readCsv(string $filePath): array
    {
        $rows = [];
        $file = fopen($filePath, 'r');
        if (!$file) {
            return $rows;
        }

        // Skip BOM if present
        $bom = fread($file, 3);
        if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            rewind($file);
        }

        while (($row = fgetcsv($file)) !== false) {
            $rows[] = $row;
        }

        fclose($file);
        return $rows;
    }

    /**
     * Read an XLS/XLSX file using PhpSpreadsheet.
     */
    protected static function readSpreadsheet(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        foreach ($worksheet->toArray(null, true, true, false) as $row) {
            // Convert null cells to empty strings
            $rows[] = array_map(function ($cell) {
                return $cell !== null ? (string) $cell : '';
            }, $row);
        }

        return $rows;
    }
}
