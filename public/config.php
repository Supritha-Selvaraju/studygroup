<?php
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    $host = getenv('DB_HOST') ?: 'studygroup-mysql.mysql.database.azure.com';
    $dbname = getenv('DB_NAME') ?: 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S@studygroup-mysql';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    $mysqli = mysqli_init();

    // ✅ Force SSL without specifying CA file (Azure App Service has trusted CA store)
    $mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
    $mysqli->ssl_set(NULL, NULL, NULL, NULL, NULL);

    if (!$mysqli->real_connect(
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
    $mysqli = new mysqli('studygroup-mysql', 'root', 'root', 'studygroup_db');
    if ($mysqli->connect_error) die('Local DB Connection failed: ' . $mysqli->connect_error);
    $mysqli->set_charset('utf8mb4');
}
?>
