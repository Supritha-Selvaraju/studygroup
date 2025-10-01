<?php
require 'config.php';

session_start();
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['email'])) {
    $email = trim($data['email']);  // sanitize input

    if ($stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($uid);
            $stmt->fetch();
            $_SESSION['user_id'] = $uid;
            $_SESSION['email'] = $email;  // optional, for consistency
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "User not found in StudyGroup DB"
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database query failed"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
}
?>
