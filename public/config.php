<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $ssl_ca = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

    if (!file_exists($ssl_ca)) {
        die("❌ SSL certificate not found at: $ssl_ca");
    }

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
        die('❌ Azure DB Connect Error (' . mysqli_connect_errno() . '): ' . mysqli_connect_error());
    }

    $mysqli->set_charset("utf8mb4");
    // Comment out this line after successful testing
    echo "✅ Connected securely to Azure MySQL Database.";
} else {
    $host = 'studygroup-mysql';
    $dbname = 'studygroup_db';
    $username = 'root';
    $password = 'root';

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("❌ Local DB Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");
    echo "✅ Connected to Local MySQL Database.";
}
?>
