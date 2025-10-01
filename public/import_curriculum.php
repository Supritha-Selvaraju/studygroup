<?php
require 'config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = __DIR__ . '/Curriculum_Semesterwise.xlsx';

try {
    $spreadsheet = IOFactory::load($inputFileName);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    die('Error loading file: ' . $e->getMessage());
}

// Use only the first sheet
$sheet = $spreadsheet->getSheet(0);
$rows = $sheet->toArray();

// Skip header row
for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];

    $departmentName = trim($row[0]);
    $year = (int)$row[1];
    $semester = (int)$row[2];
    $subject_code = trim($row[3]);
    $subject_name = trim($row[4]);
    $subject_type = trim($row[5]);

    // Skip rows with missing essential data
    if (empty($departmentName) || empty($subject_code) || empty($subject_name)) continue;

    // Insert department if not exists
    $stmt = $mysqli->prepare("INSERT IGNORE INTO departments (name) VALUES (?)");
    $stmt->bind_param("s", $departmentName);
    $stmt->execute();
    $stmt->close();

    // Get department_id
    $res = $mysqli->query("SELECT department_id FROM departments WHERE name='" . $mysqli->real_escape_string($departmentName) . "'");
    $department_id = $res->fetch_assoc()['department_id'];

    // Insert subject
    $stmt = $mysqli->prepare("
        INSERT IGNORE INTO subjects
        (department_id, year, semester, subject_code, subject_name, subject_type)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiisss", $department_id, $year, $semester, $subject_code, $subject_name, $subject_type);
    $stmt->execute();
    $stmt->close();
}

echo "Import completed successfully!\n";
