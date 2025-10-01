<?php
require 'config.php';

// Allow CORS + handle preflight
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['email'], $data['password'], $data['firstName'], $data['lastName'], $data['rollNumber'], $data['department'], $data['year'])) {
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $name = trim($data['firstName'] . ' ' . $data['lastName']);
    $roll_no = trim($data['rollNumber']);
    $department_id = (int) $data['department'];
    $year = (int) $data['year'];

    // Validate department
    $stmt = $mysqli->prepare("SELECT department_id FROM departments WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid department"]);
        exit;
    }
    $stmt->close();

    // Check for duplicate email or roll number
    $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ? OR roll_no = ?");
    $stmt->bind_param("ss", $email, $roll_no);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "User already registered"]);
        exit;
    }
    $stmt->close();

    // Insert new user
    $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash, name, roll_no, department_id, year) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $email, $password, $name, $roll_no, $department_id, $year);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
?>
