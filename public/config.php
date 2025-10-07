<?php
// -------------------------
// Environment detection
// -------------------------
$env = getenv('APP_ENV') ?: 'local'; // set APP_ENV=azure in Azure configuration

// Enable full error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($env === 'azure') {
    // -------------------------
    // Azure App Service Settings
    // -------------------------
    $host = 'studygroup-mysql.mysql.database.azure.com';
    $dbname = 'studygroup_db';
    $username = getenv('DB_USERNAME') ?: 'Supritha_S';
    $password = getenv('DB_PASSWORD') ?: 'Julie@2004';

    // Absolute path to SSL cert
    $ssl_ca = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

    if (!file_exists($ssl_ca)) {
        die("❌ SSL certificate not found at: $ssl_ca");
    }

    // Initialize and configure MySQLi
    $mysqli = mysqli_init();

    // Try to apply SSL
    if (!mysqli_ssl_set($mysqli, NULL, NULL, $ssl_ca, NULL, NULL)) {
        die("❌ Failed to set SSL parameters");
    }

    // Attempt connection with relaxed certificate verification first
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
        die('❌ Azure DB Connection failed (' . mysqli_connect_errno() . '): ' . mysqli_connect_error());
    }

    echo "✅ Connected securely to Azure MySQL database.";

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
        die("❌ Local DB Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8");
    echo "✅ Connected to Local MySQL database.";
}
?>
