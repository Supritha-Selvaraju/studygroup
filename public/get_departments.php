<?php
require 'config.php';

header("Content-Type: application/json");

// Fetch all departments ordered alphabetically
$result = $mysqli->query("SELECT department_id, name FROM departments ORDER BY name ASC");

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
