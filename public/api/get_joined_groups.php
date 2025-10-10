<?php
require '../config.php';
session_start();
header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { http_response_code(401); exit; }

// List joined groups with next event and member count
$sql = "
SELECT 
    sg.group_id, sg.group_name,
    COUNT(gm2.user_id) as member_count,
    (SELECT MIN(scheduled_time) FROM study_sessions WHERE group_id = sg.group_id AND scheduled_time > NOW()) as next_event
FROM group_members gm
JOIN study_groups sg ON gm.group_id = sg.group_id
LEFT JOIN group_members gm2 ON gm2.group_id = sg.group_id
WHERE gm.user_id = ?
GROUP BY sg.group_id, sg.group_name -- Added sg.group_name to GROUP BY clause for strict SQL mode compliance
ORDER BY sg.group_name ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();

$groups = [];
while ($row = $res->fetch_assoc()) $groups[] = $row;
echo json_encode($groups);
