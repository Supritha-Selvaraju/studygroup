<?php
include 'db.php';
session_start();
$student_id = $_SESSION['student_id'] ?? 1;

$roll = $_POST['roll'];
$dept = $_POST['dept'];
$sem = $_POST['semester'];
$dob = $_POST['dob'];
$email = $_POST['email'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $photo = basename($_FILES["photo"]["name"]);
    $target = "assets/profile_pics/" . $photo;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target);
    $photo_sql = ", photo='$photo'";
} else {
    $photo_sql = "";
}

$sql = "UPDATE students SET roll_number='$roll', department='$dept', semester='$sem', dob='$dob', email='$email' $photo_sql WHERE id=$student_id";
$conn->query($sql);

header("Location: profile.php");
exit;
?>
