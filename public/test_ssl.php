<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

if ($mysqli->ping()) {
    echo "✅ Connected to Azure MySQL with SSL successfully!\n";

    $result = $mysqli->query("SHOW STATUS LIKE 'Ssl_cipher'");
    $row = $result->fetch_assoc();
    echo "SSL Cipher in use: " . $row['Value'] . "\n";
} else {
    echo "❌ Connection failed: " . $mysqli->connect_error;
}
