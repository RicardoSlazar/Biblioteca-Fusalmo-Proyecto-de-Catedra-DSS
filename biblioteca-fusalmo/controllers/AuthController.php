<?php
/**
 * Controlador de Autenticación — Biblioteca Fusalmo
 * Maneja Login, Logout y Registro con validación completa
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    private Usuario $usuarioModel;

    public function __construct() {
        Session::start();
        $this->usuarioModel = new Usuario();
    }

    // ─── LOGIN ────────────────────────────────────────────────────────────────

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin(): void {
        if (Session::isLoggedIn()) {
            header('Location: /index.php?page=dashboard');
            exit();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Procesa el formulario de inicio de sesión.
     */
    public function processLogin(): void {
        Security::validateCSRF();

        $credential = Security::sanitizeString($_POST['credential'] ?? '');
        $password   = $_POST['password'] ?? '';

        // Validación básica
        $errors = [];
        if (empty($credential)) $errors[] = 'Ingresa tu usuario o correo electrónico.';
        if (empty($password))   $errors[] = 'Ingresa tu contraseña.';

        if (!empty($errors)) {
            $_SESSION['auth_errors']    = $errors;
            $_SESSION['auth_credential']= Security::escape($credential);
            header('Location: /index.php?page=login');
            exit();
        }

        // Rate limiting simple por IP (en producción usar Redis)
        $this->checkRateLimit();

        // Verificar credenciales
        $user = $this->usuarioModel->findByCredential($credential);

        if (!$user || !Security::verifyPassword($password, $user['contrasena'])) {
            $this->incrementLoginAttempts();
            $_SESSION['auth_errors'] = ['Credenciales incorrectas. Verifica tu usuario y contraseña.'];
            $_SESSION['auth_credential'] = Security::escape($credential);
            header('Location: /index.php?page=login');
            exit();
        }

        // Login exitoso
        $this->resetLoginAttempts();
        Session::login($user);

        header('Location: /index.php?page=dashboard');
        exit();
    }

    // ─── LOGOUT ───────────────────────────────────────────────────────────────

    public function logout(): void {
        Security::validateCSRF();
        Session::logout();
        header('Location: /index.php?page=login&msg=sesion_cerrada');
        exit();
    }

    // ─── REGISTRO ─────────────────────────────────────────────────────────────

    /**
     * Muestra el formulario de registro (solo admin puede acceder).
     */
    public function showRegister(): void {
        Session::requireRole(['admin']);
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function processRegister(): void {
        Session::requireRole(['admin']);
        Security::validateCSRF();

        $nombre   = Security::sanitizeString($_POST['nombre'] ?? '');
        $email    = Security::sanitizeEmail($_POST['correo'] ?? '');
        $telefono = Security::sanitizeString($_POST['telefono'] ?? '');
        $rol      = Security::sanitizeString($_POST['rol'] ?? 'usuario');
        $password = $_POST['contrasena'] ?? '';
        $confirm  = $_POST['confirmar'] ?? '';

        $errors = $this->validateRegisterData($nombre, $email, $password, $confirm, $rol);

        if (!empty($errors)) {
            $_SESSION['reg_errors'] = $errors;
            $_SESSION['reg_data']   = compact('nombre', 'email', 'telefono', 'rol');
            header('Location: /index.php?page=register');
            exit();
        }

        if ($this->usuarioModel->emailExists($email)) {
            $_SESSION['reg_errors'] = ['El correo electrónico ya está registrado.'];
            header('Location: /index.php?page=register');
            exit();
        }

        $created = $this->usuarioModel->create([
            'nombre'    => $nombre,
            'correo'    => $email,
            'contrasena'=> $password,
            'telefono'  => $telefono,
            'rol'       => $rol,
        ]);

        if ($created) {
            header('Location: /index.php?page=usuarios&msg=usuario_creado');
        } else {
            $_SESSION['reg_errors'] = ['Error al crear el usuario. Intenta nuevamente.'];
            header('Location: /index.php?page=register');
        }
        exit();
    }

    // ─── Helpers privados ─────────────────────────────────────────────────────

    private function validateRegisterData(
        string $nombre, mixed $email,
        string $password, string $confirm, string $rol
    ): array {
        $errors = [];
        $rolesPermitidos = ['admin', 'bibliotecario', 'usuario'];

        if (strlen($nombre) < 3)    $errors[] = 'El nombre debe tener al menos 3 caracteres.';
        if (!$email)                $errors[] = 'Correo electrónico inválido.';
        if (strlen($password) < 8)  $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        if ($password !== $confirm)  $errors[] = 'Las contraseñas no coinciden.';
        if (!in_array($rol, $rolesPermitidos)) $errors[] = 'Rol de usuario inválido.';

        // Validar fortaleza de contraseña
        if (!preg_match('/[A-Z]/', $password)) $errors[] = 'La contraseña debe contener al menos una mayúscula.';
        if (!preg_match('/[0-9]/', $password)) $errors[] = 'La contraseña debe contener al menos un número.';

        return $errors;
    }

    /** Verifica si la IP excedió el límite de intentos fallidos */
    private function checkRateLimit(): void {
        $ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key     = 'login_attempts_' . md5($ip);
        $attempts= $_SESSION[$key] ?? 0;

        if ($attempts >= 5) {
            $lockKey  = 'login_locked_' . md5($ip);
            $lockedAt = $_SESSION[$lockKey] ?? 0;
            if (time() - $lockedAt < 300) { // 5 minutos de bloqueo
                $_SESSION['auth_errors'] = ['Demasiados intentos fallidos. Espera 5 minutos antes de volver a intentarlo.'];
                header('Location: /index.php?page=login');
                exit();
            }
        }
    }

    private function incrementLoginAttempts(): void {
        $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'login_attempts_' . md5($ip);
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;

        if ($_SESSION[$key] >= 5) {
            $_SESSION['login_locked_' . md5($ip)] = time();
        }
    }

    private function resetLoginAttempts(): void {
        $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'login_attempts_' . md5($ip);
        unset($_SESSION[$key]);
    }
}
