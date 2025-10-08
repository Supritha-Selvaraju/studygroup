<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', '/home/LogFiles/php_errors.log');

// Your existing code follows
$host = 'studygroup-mysql.mysql.database.azure.com';
$user = 'Supritha_S@studygroup-mysql';
$pass = 'Julie@2004';
$db = 'studygroup_db';
$ssl_ca = 'C:\\home\\site\\wwwroot\\public\\certs\\DigiCertGlobalRootCA.crt.pem';

$mysqli = mysqli_init();
mysqli_ssl_set($mysqli, NULL, NULL, $ssl_ca, NULL, NULL);
if (!mysqli_real_connect($mysqli, $host, $user, $pass, $db, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die('SSL DB Connect Error (' . mysqli_connect_errno() . '): ' . mysqli_connect_error());
}
echo "Connected with SSL!";
?>
