<?php
require 'config.php';
header("Content-Type: application/json");
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['email'], $data['password'])) {
    $email = trim($data['email']);
    $password = $data['password'];

    $stmt = $mysqli->prepare("SELECT user_id, password_hash FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $password_hash);
    
    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email not registered"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
?>
