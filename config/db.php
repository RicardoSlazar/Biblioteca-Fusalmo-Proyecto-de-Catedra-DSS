<?php
/**
 * Configuración de conexión a la base de datos
 * Biblioteca Fusalmo — DSS 404 G03T
 */

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'biblioteca_fusalmo';
$dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';

// Compatibilidad con proveedores que entregan una URL única de conexión.
$databaseUrl = getenv('DATABASE_URL');
if ($databaseUrl) {
    $parsedUrl = parse_url($databaseUrl);

    if ($parsedUrl !== false) {
        $dbHost = $parsedUrl['host'] ?? $dbHost;
        $dbPort = isset($parsedUrl['port']) ? (string) $parsedUrl['port'] : $dbPort;
        $dbUser = $parsedUrl['user'] ?? $dbUser;
        $dbPass = $parsedUrl['pass'] ?? $dbPass;

        if (!empty($parsedUrl['path'])) {
            $dbName = ltrim($parsedUrl['path'], '/');
        }
    }
}

define('DB_HOST', $dbHost);
define('DB_PORT', $dbPort);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);
define('DB_CHARSET', $dbCharset);

class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {

        if (self::$connection === null) {

            $dsn = "mysql:host=" . DB_HOST .
                   ";port=" . DB_PORT .
                   ";dbname=" . DB_NAME .
                   ";charset=" . DB_CHARSET;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {

                self::$connection = new PDO(
                    $dsn,
                    DB_USER,
                    DB_PASS,
                    $options
                );

            } catch (PDOException $e) {

                error_log("DB Connection Error: " . $e->getMessage());

                die(json_encode([
                    'error' => 'Error de conexión a la base de datos.'
                ]));
            }
        }

        return self::$connection;
    }
}