<?php
/**
 * index.php — Front Controller
 * Biblioteca Fusalmo — DSS 404 G03T
 * 
 * Punto de entrada único. Enruta las peticiones al controlador correspondiente.
 */

// ── Inicialización ────────────────────────────────────────────────────────────
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/session.php';

Session::start();
Security::setSecurityHeaders();

// ── Enrutamiento ──────────────────────────────────────────────────────────────
$page   = Security::sanitizeString($_GET['page']   ?? 'login');
$action = Security::sanitizeString($_GET['action'] ?? '');

// Páginas públicas (sin autenticación)
$publicPages = ['login', 'recuperar'];

if (!in_array($page, $publicPages) && !Session::isLoggedIn()) {
    header('Location: /index.php?page=login');
    exit();
}

// ── Despacho de rutas ─────────────────────────────────────────────────────────
switch ($page) {

    // ── Autenticación ──
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'process') {
            $ctrl->processLogin();
        } else {
            $ctrl->showLogin();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->logout();
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'process') {
            $ctrl->processRegister();
        } else {
            $ctrl->showRegister();
        }
        break;

    // ── Dashboard ──
    case 'dashboard':
        Session::requireLogin();
        require_once __DIR__ . '/views/dashboard/index.php';
        break;

    // ── Módulos (solo autenticados) ──
    case 'libros':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/LibroController.php';
        $ctrl = new LibroController();
        $ctrl->index();
        break;

    case 'usuarios':
        Session::requireRole(['admin']);
        require_once __DIR__ . '/controllers/UsuarioController.php';
        $ctrl = new UsuarioController();
        $ctrl->index();
        break;

    case 'prestamos':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/PrestamoController.php';
        $ctrl = new PrestamoController();
        $ctrl->index();
        break;

    case 'devoluciones':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/DevolucionController.php';
        $ctrl = new DevolucionController();
        $ctrl->index();
        break;

    case 'reportes':
        Session::requireRole(['admin']);
        require_once __DIR__ . '/controllers/ReporteController.php';
        $ctrl = new ReporteController();
        $ctrl->index();
        break;

    // ── 404 ──
    default:
        http_response_code(404);
        echo '<h1>Página no encontrada</h1><a href="/index.php?page=dashboard">Volver al inicio</a>';
        break;
}
