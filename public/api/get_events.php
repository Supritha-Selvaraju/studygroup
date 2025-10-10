<?php
require '../config.php';
session_start();
header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { http_response_code(401); exit; }

// Fetch events in user's groups (upcoming only)
$sql = "
SELECT ss.session_id, sg.group_name, ss.session_link, ss.scheduled_time
FROM study_sessions ss
JOIN group_members gm ON gm.group_id = ss.group_id
JOIN study_groups sg ON sg.group_id = ss.group_id
WHERE gm.user_id = ? AND ss.scheduled_time > NOW()
ORDER BY ss.scheduled_time ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();

$events = [];
while ($row = $res->fetch_assoc()) {
    $events[] = [
        'session_id' => $row['session_id'],
        'title' => $row['group_name'],
        'datetime' => $row['scheduled_time'],
        'link' => $row['session_link'],
        'is_member' => true // Assuming user can see this event only if they are a member
    ];
}
echo json_encode($events);
