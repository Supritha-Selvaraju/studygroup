<?php
require '../config.php';
session_start();
header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) { http_response_code(401); exit; }

// Find user's department & year
$stmt = $mysqli->prepare("SELECT department_id, year FROM users WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$dep = $res['department_id'] ?? 0; $yr = $res['year'] ?? 0;

// Fetch subjects for the user's department/year that they have NOT joined a group for yet.
$sql = "
SELECT s.subject_id, s.subject_code, s.subject_name, s.subject_type
FROM subjects s 
WHERE s.department_id = ? AND s.year = ?
AND s.subject_id NOT IN (
    SELECT sg.subject_id FROM study_groups sg
    JOIN group_members gm ON gm.group_id = sg.group_id
    WHERE gm.user_id = ?
)
ORDER BY s.semester, s.subject_name ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('iii', $dep, $yr, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
while ($row = $result->fetch_assoc()) $courses[] = $row;
echo json_encode($courses);
