<?php
require '../config.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { http_response_code(401); exit; }
header('Content-Type: application/json');

// Get profile and stats together
$sql = "
SELECT 
    u.name, 
    u.email, 
    u.year, 
    d.name as department,
    (SELECT COUNT(*) FROM group_members gm WHERE gm.user_id = u.user_id) as groups_count,
    (
        SELECT COUNT(DISTINCT s.subject_id) 
        FROM subjects s 
        JOIN study_groups sg2 ON sg2.subject_id = s.subject_id 
        JOIN group_members gm2 ON gm2.group_id = sg2.group_id 
        WHERE gm2.user_id = u.user_id
    ) as courses_count,
    (
        SELECT COUNT(*) 
        FROM study_sessions ss 
        JOIN group_members gm3 ON gm3.group_id = ss.group_id 
        WHERE gm3.user_id = u.user_id AND ss.scheduled_time > NOW()
    ) as events_count, 
    NULL as avg_feedback 
FROM users u 
JOIN departments d ON d.department_id = u.department_id 
WHERE u.user_id = ?
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);

$stmt->execute();

$res = $stmt->get_result();

echo json_encode($res->fetch_assoc());
