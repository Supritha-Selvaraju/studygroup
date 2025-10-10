<?php
session_start();
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$response = ['status'=>'error','msg'=>'Unknown user action'];

if (($data['action'] ?? '') == 'logout') {
    session_destroy();
    $response = ['status'=>'success', 'msg'=>'Logged out successfully'];
}

echo json_encode($response);
