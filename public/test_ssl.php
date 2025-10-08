<?php
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
