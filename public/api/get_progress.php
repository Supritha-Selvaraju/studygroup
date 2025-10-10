<?php
require '../config.php';
session_start();
header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { http_response_code(401); exit; }

// Fetch unique academic notes for the user.
// The SQL already fetches distinct notes by note_id. The duplication seen 
// on the frontend means multiple unique records share the same title/content.
$sql = "
SELECT 
    an.note_id, 
    an.note_title, 
    an.note_content, 
    an.created_at, 
    s.subject_name 
FROM academic_notes an
LEFT JOIN subjects s ON an.subject_id = s.subject_id
WHERE an.user_id = ?
ORDER BY an.created_at DESC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();

$notes = [];
while ($row = $res->fetch_assoc()) $notes[] = $row;
echo json_encode($notes);
