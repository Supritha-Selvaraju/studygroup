<?php
include 'db.php';
session_start();
$student_id = $_SESSION['student_id'] ?? 1;

$sql = "SELECT * FROM students WHERE id = $student_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$groups_joined = $conn->query("SELECT * FROM groups WHERE id IN (SELECT group_id FROM group_members WHERE student_id = $student_id)");
$groups_created = $conn->query("SELECT * FROM groups WHERE created_by = $student_id");
?>

