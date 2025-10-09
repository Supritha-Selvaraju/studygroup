<?php
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    $host = getenv('DB_HOST') ?: 'studygroup-mysql.mysql.database.azure.com';
    $dbname = getenv('DB_NAME') ?: 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S@studygroup-mysql';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    $mysqli = mysqli_init();

    // ✅ Explicitly set SSL CA path (Azure public certificate)
mysqli_ssl_set(
    $mysqli,
    NULL,  // key
    NULL,  // cert
    __DIR__ . '/BaltimoreCyberTrustRoot.crt.pem', // CA certificate path
    NULL,  // cipher
    NULL   // passphrase
);

    // ✅ Force SSL connection using modern TLS (1.2+)
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
    $mysqli = new mysqli('studygroup-mysql', 'root', 'root', 'studygroup_db');
    if ($mysqli->connect_error) die('Local DB Connection failed: ' . $mysqli->connect_error);
    $mysqli->set_charset('utf8mb4');
}
?>
