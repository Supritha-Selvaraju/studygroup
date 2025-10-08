<?php
$host = 'studygroup-mysql.mysql.database.azure.com';
$db   = 'studygroup_db';
$user = 'Supritha_S@studygroup-mysql';
$pass = 'Julie@2004';

$mysqli = new mysqli($host, $user, $pass, $db, 3306);

if ($mysqli->connect_error) {
    die("DB Connect Error: " . $mysqli->connect_error);
}

echo "Connected successfully!";
