<?php
require 'config.php';
if (!$mysqli) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

// Fetch all departments ordered alphabetically
$result = $mysqli->query("SELECT department_id, name FROM departments ORDER BY name ASC");

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
