<?php

$env = getenv('APP_ENV') ?: 'local';

if ($env === 'azure') {

    // Fetch Azure environment variables
    $host     = getenv('DB_HOST');
    $dbname   = getenv('DB_NAME');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');

    // Basic validation
    if (!$host || !$dbname || !$username || !$password) {
        die("Azure DB ERROR: Missing one or more DB environment variables.");
    }

    // Initialize mysqli
    $mysqli = mysqli_init();

    // Disable strict SSL server verification (Azure allows this)
    $mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

    // Set SSL CA certificate (required for Azure MySQL)
    $ca_path = __DIR__ . "/BaltimoreCyberTrustRoot.crt.pem";
    if (!file_exists($ca_path)) {
        die("Azure DB ERROR: CA Certificate not found at $ca_path");
    }

    $mysqli->ssl_set(
        NULL,         // key
        NULL,         // cert
        $ca_path,     // CA cert (required)
        NULL,
        NULL
    );

    // Connect to Azure MySQL
    if (
        !$mysqli->real_connect(
            $host,
            $username,
            $password,
            $dbname,
            3306,
            NULL,
            MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
        )
    ) {
        die("Azure DB Connect Error (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
    }

} else {

    // LOCAL DEVELOPMENT DATABASE
    $mysqli = new mysqli("localhost", "root", "", "studygroup", 3307);

    if ($mysqli->connect_errno) {
        die("Local DB Connect Error (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
}

$mysqli->set_charset('utf8mb4');
