<?php
require 'config.php';
if ($mysqli->connect_error) {
    die("DB Connect Error: " . $mysqli->connect_error);
}
echo "Connected successfully!";
