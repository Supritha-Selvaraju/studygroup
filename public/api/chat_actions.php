<?php
header('Content-Type: application/json');
require_once('../config.php'); // Include your database connection setup

// Mock User Session and Group Context
// In a real application, user_id is retrieved from $_SESSION
$current_user_id = 1; // Assuming a user is logged in (replace with $_SESSION['user_id']) 
$group_id = $_GET['group_id'] ?? null; // Group ID is required for most actions

// --- Utility Functions ---

/**
 * Executes a SELECT query and returns the result as an array.
 * @param mysqli $mysqli The database connection object.
 * @param string $sql The SQL query string.
 * @return array
 */
function fetchData($mysqli, $sql) {
    $result = $mysqli->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }
    return $data;
}

/**
 * Prepares and sends a JSON response.
 * @param array $data The data array to encode.
 * @param int $status HTTP status code.
 */
function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

// --- Main Request Handler ---

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if (!$group_id) {
        sendResponse(['status' => 'error', 'message' => 'Group ID is missing.'], 400);
    }

    switch ($action) {
        case 'fetch_messages':
            // Fetch messages and the count of upvotes for each message
            $sql = "
                SELECT
                    m.message_id,
                    m.user_id,
                    u.name AS user_name,
                    m.message_text,
                    m.timestamp,
                    (SELECT COUNT(*) FROM upvotes WHERE message_id = m.message_id) AS upvote_count
                FROM messages m
                JOIN users u ON m.user_id = u.user_id
                WHERE m.group_id = {$group_id}
                ORDER BY m.timestamp ASC;
            ";
            $messages = fetchData($mysqli, $sql);
            sendResponse(['status' => 'success', 'data' => $messages]);
            break;

        case 'fetch_resources':
            // Fetch resources (materials) for the group
            $sql = "
                SELECT
                    m.material_id,
                    m.file_path,
                    m.file_type,
                    m.description,
                    u.name AS uploaded_by,
                    m.uploaded_at
                FROM materials m
                JOIN users u ON m.uploaded_by = u.user_id
                WHERE m.group_id = {$group_id}
                ORDER BY m.uploaded_at DESC;
            ";
            $resources = fetchData($mysqli, $sql);
            sendResponse(['status' => 'success', 'data' => $resources]);
            break;

        case 'fetch_group_info':
            // Fetch group name and next session details (from study_sessions)
            $sql = "
                SELECT
                    sg.group_name,
                    s.subject_code
                FROM study_groups sg
                JOIN subjects s ON sg.subject_id = s.subject_id
                WHERE sg.group_id = {$group_id};
            ";
            $info = fetchData($mysqli, $sql);

            $sql_session = "
                SELECT session_link, scheduled_time
                FROM study_sessions
                WHERE group_id = {$group_id}
                ORDER BY scheduled_time DESC
                LIMIT 1;
            ";
            $session = fetchData($mysqli, $sql_session);

            sendResponse([
                'status' => 'success', 
                'group_info' => $info[0] ?? null,
                'session' => $session[0] ?? null
            ]);
            break;

        default:
            sendResponse(['status' => 'error', 'message' => 'Invalid GET action.'], 400);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $data['action'] ?? '';

    if (!$group_id) {
        sendResponse(['status' => 'error', 'message' => 'Group ID is missing.'], 400);
    }

    switch ($action) {
        case 'send_message':
            $message_text = $mysqli->real_escape_string($data['message_text'] ?? '');
            
            if (empty($message_text)) {
                sendResponse(['status' => 'error', 'message' => 'Message text cannot be empty.'], 400);
            }

            $sql = "INSERT INTO messages (group_id, user_id, message_text) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iis", $group_id, $current_user_id, $message_text);

            if ($stmt->execute()) {
                sendResponse(['status' => 'success', 'message' => 'Message sent.', 'message_id' => $mysqli->insert_id]);
            } else {
                sendResponse(['status' => 'error', 'message' => 'Failed to send message: ' . $stmt->error], 500);
            }
            $stmt->close();
            break;

        case 'toggle_upvote':
            $message_id = $data['message_id'] ?? 0;
            
            if ($message_id <= 0) {
                sendResponse(['status' => 'error', 'message' => 'Invalid message ID.'], 400);
            }

            // 1. Check if the user has already upvoted
            $sql_check = "SELECT * FROM upvotes WHERE message_id = ? AND user_id = ?";
            $stmt_check = $mysqli->prepare($sql_check);
            $stmt_check->bind_param("ii", $message_id, $current_user_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows > 0) {
                // 2. If voted, DELETE the upvote (unvote)
                $sql_action = "DELETE FROM upvotes WHERE message_id = ? AND user_id = ?";
                $message = 'Upvote removed.';
            } else {
                // 3. If not voted, INSERT the upvote
                $sql_action = "INSERT INTO upvotes (message_id, user_id) VALUES (?, ?)";
                $message = 'Message upvoted.';
            }
            $stmt_check->close();

            $stmt_action = $mysqli->prepare($sql_action);
            $stmt_action->bind_param("ii", $message_id, $current_user_id);

            if ($stmt_action->execute()) {
                sendResponse(['status' => 'success', 'message' => $message]);
            } else {
                sendResponse(['status' => 'error', 'message' => 'Failed to process upvote: ' . $stmt_action->error], 500);
            }
            $stmt_action->close();
            break;
            
        case 'upload_resource':
            // NOTE: In a real app, file upload requires handling $_FILES and multipart/form-data.
            // This mocks only handling metadata for simplicity in a JSON-based API.
            $file_path = $mysqli->real_escape_string($data['file_path'] ?? 'path/to/default.pdf');
            $file_type = $mysqli->real_escape_string($data['file_type'] ?? 'PDF');
            $description = $mysqli->real_escape_string($data['description'] ?? '');

            if (empty($file_path) || empty($description)) {
                sendResponse(['status' => 'error', 'message' => 'File path and description are required.'], 400);
            }

            $sql = "INSERT INTO materials (group_id, uploaded_by, file_path, file_type, description) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iiss", $group_id, $current_user_id, $file_path, $file_type, $description);

            if ($stmt->execute()) {
                sendResponse(['status' => 'success', 'message' => 'Resource uploaded.', 'material_id' => $mysqli->insert_id]);
            } else {
                sendResponse(['status' => 'error', 'message' => 'Failed to upload resource: ' . $stmt->error], 500);
            }
            $stmt->close();
            break;

        default:
            sendResponse(['status' => 'error', 'message' => 'Invalid POST action.'], 400);
    }
} else {
    sendResponse(['status' => 'error', 'message' => 'Method not allowed.'], 405);
}

$mysqli->close();
?>