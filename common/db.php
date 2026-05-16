<?php
// Database helper for Docker/Env configuration
function get_dbh()
{
    $host = getenv('DB_HOST') ?: 'db';
    $name = getenv('DB_NAME') ?: 'shop';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $charset = getenv('DB_CHARSET') ?: 'utf8';

    $dsn = "mysql:dbname={$name};host={$host};charset={$charset}";
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

?>
