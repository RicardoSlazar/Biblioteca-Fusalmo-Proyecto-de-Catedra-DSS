<?php

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db = getenv('DB_NAME') ?: 'biblioteca_fusalmo';
$port = (int) (getenv('DB_PORT') ?: 3306);

$databaseUrl = getenv('DATABASE_URL');
if ($databaseUrl) {
    $parsedUrl = parse_url($databaseUrl);

    if ($parsedUrl !== false) {
        $host = $parsedUrl['host'] ?? $host;
        $user = $parsedUrl['user'] ?? $user;
        $pass = $parsedUrl['pass'] ?? $pass;
        $port = isset($parsedUrl['port']) ? (int) $parsedUrl['port'] : $port;

        if (!empty($parsedUrl['path'])) {
            $db = ltrim($parsedUrl['path'], '/');
        }
    }
}

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>