<?php
require '../config.php';
session_start();
header('Content-Type: application/json');
$user_id = $_SESSION['user_id'] ?? 0;

// FIX: If the script is exiting with JSON due to an error, we need to ensure 
// the action logic is only executed once. The following fixes the broken flow 
// where the script would continue executing past the initial action block.

if (!$user_id) { echo json_encode(['status'=>'error','msg'=>'Not logged in']); exit; }

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';
$response = ['status' => 'error', 'msg' => 'Unknown action'];

if ($action == 'join') {
    $subject_id = (int)$data['subject_id'];
    // Find group_id for subject, add user to group_members
    $sgq = $mysqli->prepare("SELECT group_id FROM study_groups WHERE subject_id=?");
    $sgq->bind_param('i',$subject_id); $sgq->execute();
    $g = $sgq->get_result()->fetch_assoc();
    if ($g) {
        $group_id = $g['group_id'];
        // prevent double join
        $check = $mysqli->prepare("SELECT * FROM group_members WHERE group_id=? AND user_id=?");
        $check->bind_param('ii',$group_id,$user_id); $check->execute();
        if ($check->get_result()->num_rows == 0) {
            $stmt = $mysqli->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?,?)");
            $stmt->bind_param('ii',$group_id,$user_id);
            $stmt->execute();
        }
        $response = ['status'=>'success','msg'=>'Joined group.'];
    } else {
        $response = ['status'=>'error','msg'=>'Group not found'];
    }
}
elseif ($action == 'leave') {
    $group_id = (int)$data['group_id'];
    $stmt = $mysqli->prepare("DELETE FROM group_members WHERE group_id=? AND user_id=?");
    $stmt->bind_param('ii', $group_id, $user_id);
    $stmt->execute();
    $response = ['status'=>'success','msg'=>'Left group.'];
}
elseif ($action === 'RSVP') {
    // RSVP actions can be handled here if needed
    $response = ['status'=>'success','msg'=>'RSVP Success'];
}
elseif($action == 'create_group_custom'){
    $group_name = trim($data['group_name'] ?? ''); // Use group_name from frontend
    $subject_id = isset($data['subject_id']) && $data['subject_id'] ? (int)$data['subject_id'] : null;
    
    if(!$group_name) {
        $response = ['status'=>'error','msg'=>'Group name required'];
    } else {
        // Check if such a group name already exists (avoid duplicates)
        $check = $mysqli->prepare("SELECT group_id FROM study_groups WHERE group_name = ?");
        $check->bind_param('s', $group_name); 
        $check->execute();
        if($check->get_result()->num_rows > 0){
            $response = ['status'=>'error','msg'=>'Group name already exists.'];
        } else {
            // Create group
            // subject_id is nullable (INT), bind 'i' or 's' if non-null, or use call_user_func_array for safety if subject_id could be NULL
            // Since $subject_id can be NULL (which is INT in SQL), we simplify to 'is' for string/int.
            
            // The logic from your previous attempt that tried to parse course code from name is complex and potentially flawed. 
            // We rely on the provided $subject_id from the dropdown if available, otherwise it is NULL.
            
            if ($subject_id === null && $data['subject_id']) {
                 // The frontend ensures subject_id is passed as a number or null/empty string. 
                 // If it was provided as non-empty but invalid, it would be caught.
            }
            
            $stmt = $mysqli->prepare("INSERT INTO study_groups (subject_id, group_name, created_by) VALUES (?, ?, ?)");
            $stmt->bind_param('isi', $subject_id, $group_name, $user_id);
            
            if($stmt->execute()){
                $group_id = $stmt->insert_id;
                // Auto-enroll creator
                $enroll = $mysqli->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?,?)");
                $enroll->bind_param('ii', $group_id, $user_id);
                $enroll->execute();
                $response = ['status'=>'success','msg'=>'Group created successfully.'];
            } else {
                $response = ['status'=>'error','msg'=>'Failed to create group in database.'];
            }
        }
    }
}

// add note
elseif($action == 'add_note'){
    $note_title = trim($data['note_title'] ?? '');
    $note_content = trim($data['note_content'] ?? '');
    $subject_id = isset($data['subject_id']) && $data['subject_id'] ? (int)$data['subject_id'] : null;

    if(!$note_title || !$note_content) { 
        $response = ['status'=>'error','msg'=>'Fill all fields'];
    } else {
        $stmt = $mysqli->prepare("INSERT INTO academic_notes (user_id, subject_id, note_title, note_content) VALUES (?,?,?,?)");
        $stmt->bind_param('iiss',$user_id, $subject_id, $note_title, $note_content);
        if($stmt->execute()){
            $response = ['status'=>'success','msg'=>'Note added successfully.'];
        }else{
            $response = ['status'=>'error','msg'=>'Failed to add note to database.'];
        }
    }
}

echo json_encode($response);
