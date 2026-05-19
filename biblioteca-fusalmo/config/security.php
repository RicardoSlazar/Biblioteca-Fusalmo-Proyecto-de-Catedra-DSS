<?php
/**
 * Seguridad — Biblioteca Fusalmo
 * Protección CSRF, XSS y utilidades de sanitización
 */

class Security {

    // ─── CSRF ─────────────────────────────────────────────────────────────────

    /**
     * Genera y almacena un token CSRF en la sesión.
     */
    public static function generateCSRFToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Devuelve el campo oculto HTML con el token CSRF.
     */
    public static function csrfField(): string {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Valida que el token CSRF recibido coincida con el de la sesión.
     * Termina la ejecución si no es válido.
     */
    public static function validateCSRF(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die(json_encode(['error' => 'Token CSRF inválido. Acceso denegado.']));
        }
    }

    // ─── XSS ──────────────────────────────────────────────────────────────────

    /**
     * Escapa caracteres HTML para prevenir XSS.
     */
    public static function escape(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Sanitiza un string eliminando etiquetas y espacios innecesarios.
     */
    public static function sanitizeString(string $input): string {
        return trim(strip_tags($input));
    }

    /**
     * Sanitiza y valida un correo electrónico.
     */
    public static function sanitizeEmail(string $email): string|false {
        $email = trim(strtolower($email));
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    /**
     * Sanitiza un entero.
     */
    public static function sanitizeInt(mixed $value): int|false {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    // ─── Contraseñas ──────────────────────────────────────────────────────────

    /**
     * Hashea una contraseña usando bcrypt.
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verifica una contraseña contra su hash.
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    // ─── Headers de seguridad ─────────────────────────────────────────────────

    /**
     * Aplica headers HTTP de seguridad.
     */
    public static function setSecurityHeaders(): void {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data:");
    }
}
