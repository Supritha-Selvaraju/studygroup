<?php
$mysqli = new mysqli(
    getenv('DB_HOST'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME'),
    3306,
    NULL,
    MYSQLI_CLIENT_SSL
);

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
} else {
    echo "✅ Connected to Azure MySQL with SSL successfully!";
    echo "<br>SSL Cipher: " . $mysqli->get_ssl_cipher();
}
?>
