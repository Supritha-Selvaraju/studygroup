<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: signin.html");
    exit();
}
?>
<h1>Welcome to Dashboard</h1>
