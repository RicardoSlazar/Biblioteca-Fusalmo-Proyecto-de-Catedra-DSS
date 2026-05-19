<?php
/**
 * Gestión de sesiones — Biblioteca Fusalmo
 * Manejo de autenticación, roles y control de acceso
 */

class Session {

    /**
     * Inicia la sesión con configuración segura.
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }

        // Regenerar el ID de sesión cada 30 minutos para prevenir session fixation
        if (!isset($_SESSION['last_regenerated'])) {
            self::regenerate();
        } elseif (time() - $_SESSION['last_regenerated'] > 1800) {
            self::regenerate();
        }
    }

    /**
     * Regenera el ID de sesión (previene session fixation).
     */
    public static function regenerate(): void {
        session_regenerate_id(true);
        $_SESSION['last_regenerated'] = time();
    }

    /**
     * Almacena los datos del usuario autenticado en la sesión.
     */
    public static function login(array $user): void {
        self::regenerate();
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        $_SESSION['user_role'] = $user['rol'];
        $_SESSION['user_email']= $user['correo'];
        $_SESSION['logged_in'] = true;
    }

    /**
     * Destruye la sesión completamente.
     */
    public static function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Verifica si hay un usuario autenticado.
     */
    public static function isLoggedIn(): bool {
        return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Redirige al login si no hay sesión activa.
     */
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            header('Location: /index.php?page=login');
            exit();
        }
    }

    /**
     * Verifica que el usuario tenga uno de los roles permitidos.
     */
    public static function requireRole(array $allowedRoles): void {
        self::requireLogin();
        if (!in_array($_SESSION['user_role'], $allowedRoles, true)) {
            http_response_code(403);
            header('Location: /index.php?page=dashboard&error=acceso_denegado');
            exit();
        }
    }

    public static function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }
}
