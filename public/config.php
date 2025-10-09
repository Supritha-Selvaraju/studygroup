<?php
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    // === Azure Connection (SSL Enabled) ===
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S@studygroup-mysql';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    $ssl_ca = __DIR__ . '/public/certs/DigiCertGlobalRootCA.crt.pem';
    $mysqli = mysqli_init();
    mysqli_ssl_set($mysqli, NULL, NULL, $ssl_ca, NULL, NULL);

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

    // ✅ Force a known charset to avoid "unknown charset" error
    if (!$mysqli->set_charset("utf8mb4")) {
        $mysqli->set_charset("utf8");
    }

} else {
    // === Local Docker Connection (NO SSL) ===
    $host = 'studygroup-mysql';
    $dbname = 'studygroup_db';
    $username = 'root';
    $password = 'root';

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Local DB Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");
}
?>
