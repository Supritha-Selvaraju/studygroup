<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

if ($mysqli) {
    echo json_encode(["status" => "success", "message" => "DB Connected"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
}
?>
