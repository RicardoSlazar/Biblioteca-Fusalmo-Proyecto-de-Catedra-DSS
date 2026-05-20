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

    // ── LIBROS ──
    case 'libros':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/LibroController.php';
        $ctrl = new LibroController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match ($action) {
                'process-create' => $ctrl->processCreate(),
                'process-edit' => $ctrl->processEdit(),
                'delete' => $ctrl->delete(),
                default => $ctrl->index()
            };
        } else {
            match ($action) {
                'create' => $ctrl->showCreate(),
                'edit' => $ctrl->showEdit(),
                'search' => $ctrl->search(),
                default => $ctrl->index()
            };
        }
        break;

    // ── CATEGORÍAS ──
    case 'categorias':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/CategoriaController.php';
        $ctrl = new CategoriaController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match ($action) {
                'process-create' => $ctrl->processCreate(),
                'process-edit' => $ctrl->processEdit(),
                'delete' => $ctrl->delete(),
                default => $ctrl->index()
            };
        } else {
            match ($action) {
                'create' => $ctrl->showCreate(),
                'edit' => $ctrl->showEdit(),
                default => $ctrl->index()
            };
        }
        break;

    // ── AUTORES ──
    case 'autores':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/AutorController.php';
        $ctrl = new AutorController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match ($action) {
                'process-create' => $ctrl->processCreate(),
                'process-edit' => $ctrl->processEdit(),
                'delete' => $ctrl->delete(),
                default => $ctrl->index()
            };
        } else {
            match ($action) {
                'create' => $ctrl->showCreate(),
                'edit' => $ctrl->showEdit(),
                default => $ctrl->index()
            };
        }
        break;

    // ── PRÉSTAMOS ──
    case 'prestamos':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/PrestamoController.php';
        $ctrl = new PrestamoController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match ($action) {
                'process-create' => $ctrl->processCreate(),
                'devolucion' => $ctrl->procesarDevolucion(),
                'renovar' => $ctrl->renovar(),
                default => $ctrl->index()
            };
        } else {
            match ($action) {
                'create' => $ctrl->showCreate(),
                'vencidos' => $ctrl->vencidos(),
                'mis-prestamos' => $ctrl->misPrestarmos(),
                default => $ctrl->index()
            };
        }
        break;

    // ── USUARIOS ──
        Session::requireRole(['admin']);
        require_once __DIR__ . '/controllers/UsuarioController.php';
        $ctrl = new UsuarioController();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match ($action) {
                'process-edit' => $ctrl->processEdit(),
                'process-change-password' => $ctrl->processChangePassword(),
                'toggle-status' => $ctrl->toggleStatus(),
                'delete' => $ctrl->delete(),
                default => $ctrl->index()
            };
        } else {
            match ($action) {
                'edit' => $ctrl->showEdit(),
                'change-password' => $ctrl->showChangePassword(),
                default => $ctrl->index()
            };
        }
        break;

    case 'prestamos':
        Session::requireRole(['admin', 'bibliotecario']);
        require_once __DIR__ . '/controllers/PrestamoController.php';
        $ctrl = new PrestamoController();
        $ctrl->index();
        break;

    // ── 404 ──
    default:
        http_response_code(404);
        echo '<h1>Página no encontrada</h1><a href="/index.php?page=dashboard">Volver al inicio</a>';
        break;
}
