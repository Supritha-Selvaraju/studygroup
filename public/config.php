if ($env === 'azure') {

    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');

    $mysqli = mysqli_init();

    // Azure SSL but without server verification
    $mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    $mysqli->ssl_set(NULL, NULL, NULL, NULL, NULL);

    if (!$mysqli->real_connect(
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
}
