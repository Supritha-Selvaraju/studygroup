<?php
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'supritha_php';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    $mysqli = mysqli_init();

    // ✅ Do NOT use mysqli_ssl_set() on Azure App Service — it can force TLS 1.0
    // Just enforce SSL through the client flag
    if (!mysqli_real_connect(
        $mysqli,
        $host,
        $username,
        $password,
        $dbname,
        3306,
        NULL,
        MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
    )) {
        die('Azure DB Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    $mysqli->set_charset('utf8mb4');
} else {
    $mysqli = new mysqli('studygroup-mysql', 'root', 'root', 'studygroup_db');
    if ($mysqli->connect_error) die('Local DB Connection failed: ' . $mysqli->connect_error);
    $mysqli->set_charset('utf8mb4');
}
?>
