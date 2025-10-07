<?php
require 'config.php';

$result = $mysqli->query("SELECT COUNT(*) AS total FROM departments");
$row = $result->fetch_assoc();
echo "Departments in DB: " . $row['total'];
?>