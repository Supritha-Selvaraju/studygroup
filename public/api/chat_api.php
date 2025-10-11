<?php
// chat_api.php
session_start();
require_once "db_connect.php";
$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Fetch messages and resources
  $group_id = intval($_GET['group_id']);
  // Messages (with upvote count)
  $sql = "SELECT m.*, u.name as user_name,
             (SELECT COUNT(*) FROM upvotes u2 WHERE u2.message_id=m.message_id) as upvotes,
             (SELECT 1 FROM upvotes u3 WHERE u3.message_id=m.message_id AND u3.user_id=?) as voted
          FROM messages m
          JOIN users u ON u.user_id=m.user_id
          WHERE m.group_id = ?
          ORDER BY m.timestamp ASC
          LIMIT 100";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $user_id, $group_id);
  $stmt->execute();
  $rows = $stmt->get_result();
  $messages = [];
  while ($msg = $rows->fetch_assoc()) {
    $msg['voted'] = !!$msg['voted'];
    $messages[] = $msg;
  }
  // Materials
  $res = $conn->query("SELECT material_id, file_path, description, uploaded_at FROM materials WHERE group_id=$group_id ORDER BY uploaded_at DESC LIMIT 10");
  $materials = [];
  foreach ($res as $mat) {
    $mat['file_name'] = basename($mat['file_path']);
    $materials[] = $mat;
  }
  echo json_encode(['messages' => $messages, 'materials' => $materials]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Upload file or send message or upvote
  $group_id = intval($_POST['group_id'] ?? 0);
  if (isset($_POST['action']) && $_POST['action'] == "upvote") {
    $message_id = intval($_POST['message_id']);
    // One upvote per user per message
    $conn->query("INSERT IGNORE INTO upvotes (message_id,user_id) VALUES ($message_id, $user_id)");
    echo json_encode(['success'=>true]);
    exit;
  }
  // Resource upload (simulate; implement actual file checks in real deployment)
  if (isset($_FILES['material']) && $_FILES['material']['error'] == 0) {
    $target_dir = "uploads/materials/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0775, true);
    $filename = time().'_'.$_FILES['material']['name'];
    $target_file = $target_dir . $filename;
    move_uploaded_file($_FILES['material']['tmp_name'], $target_file);
    $desc = $conn->real_escape_string(basename($_FILES['material']['name']));
    $conn->query("INSERT INTO materials (group_id, uploaded_by, file_path, description) VALUES ($group_id, $user_id, '$target_file', '$desc')");
    echo json_encode(['success'=>true]);
    exit;
  }
  // Send chat message
  $msg_txt = trim($_POST['messageInput'] ?? '');
  if ($msg_txt) {
    $stmt = $conn->prepare("INSERT INTO messages (group_id, user_id, message_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $group_id, $user_id, $msg_txt);
    $stmt->execute();
    echo json_encode(['success'=>true]);
    exit;
  }
}
?>
