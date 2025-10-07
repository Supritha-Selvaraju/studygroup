<?php
// Detect environment
$env = getenv('APP_ENV') ?: 'local';

$mysqli = null;

if ($env === 'azure') {
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';
    $ssl_ca = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

    if (!file_exists($ssl_ca)) {
        die(json_encode(["status" => "error", "message" => "SSL certificate not found at $ssl_ca"]));
    }

    $mysqli = mysqli_init();
    mysqli_ssl_set($mysqli, NULL, NULL, $ssl_ca, NULL, NULL);

    if (!mysqli_real_connect($mysqli, $host, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
        die(json_encode([
            "status" => "error",
            "message" => "Azure DB Connect Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error()
        ]));
    }

    $mysqli->set_charset("utf8");

} else {
    // Local Docker
    $host = 'studygroup-mysql';
    $dbname = 'studygroup_db';
    $username = 'root';
    $password = 'root';

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die(json_encode([
            "status" => "error",
            "message" => "Local DB Connection failed: " . $mysqli->connect_error
        ]));
    }

    $mysqli->set_charset("utf8");
}
?>
