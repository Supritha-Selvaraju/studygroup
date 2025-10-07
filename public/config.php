<?php
// Detect environment (default = local)
// Set APP_ENV in Azure App Service → Configuration → Application settings
$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {
    // -------------------------
    // Azure App Service Settings
    // -------------------------
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S';   // use App Settings for secrets
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    $ssl_ca = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem'; // upload CA cert here

    $mysqli = mysqli_init();
    mysqli_ssl_set($mysqli, NULL, NULL, $ssl_ca, NULL, NULL);

    if (!mysqli_real_connect($mysqli, $host, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
        die('Azure DB Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }
} else {
    // -------------------------
    // Local Docker Settings
    // -------------------------
    $host = 'studygroup-mysql';
    $dbname = 'studygroup_db';
    $username = 'root';
    $password = 'root';

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Local DB Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8");
}
?>
