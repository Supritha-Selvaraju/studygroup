<?php
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    // ✅ Get all values from Azure App Settings
    $host = getenv('DB_HOST') ?: 'studygroup-mysql.mysql.database.azure.com';
    $dbname = getenv('DB_NAME') ?: 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S@studygroup-mysql';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';
    $sslmode = getenv('DB_SSL_MODE') ?: 'Required';

    $mysqli = mysqli_init();

    // ✅ Use SSL (Azure MySQL enforces SSL by default)
    mysqli_ssl_set($mysqli, NULL, NULL, NULL, NULL, NULL);

    if (!mysqli_real_connect(
        $mysqli,
        $host,
        $username,
        $password,
        $dbname,
        3306,
        NULL,
        MYSQLI_CLIENT_SSL
    )) {
        die('Azure DB Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    $mysqli->set_charset('utf8mb4');
} else {
    // ✅ Local Development Setup
    $mysqli = new mysqli('127.0.0.1', 'root', 'root', 'studygroup_db');
    if ($mysqli->connect_error) {
        die('Local DB Connection failed: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
}
?>
