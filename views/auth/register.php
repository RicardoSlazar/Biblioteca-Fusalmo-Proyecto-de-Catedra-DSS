<?php
require_once __DIR__ . '/../../config/security.php';
Security::setSecurityHeaders();

$errors  = $_SESSION['reg_errors'] ?? [];
$old     = $_SESSION['reg_data'] ?? [];
unset($_SESSION['reg_errors'], $_SESSION['reg_data']);

$csrfField = Security::csrfField();
$userName  = Security::escape(Session::get('user_name') ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario — Biblioteca Fusalmo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #1E3A6E;
            --color-nav:     #1A4FA0;
            --color-success: #27AE60;
            --color-bg:      #F5F7FA;
            --color-alert:   #F4A726;
            --color-error:   #E74C3C;
            --font-title:    'Poppins', sans-serif;
            --font-body:     'Open Sans', sans-serif;
        }

        body { font-family: var(--font-body); background: var(--color-bg); }

        /* Navbar */
        .navbar-fusalmo {
            background: linear-gradient(90deg, var(--color-primary), var(--color-nav));
            padding: 0 24px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .navbar-brand-f {
            font-family: var(--font-title);
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand-f .logo-badge {
            width: 34px; height: 34px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
        }
        .nav-user { color: rgba(255,255,255,0.85); font-size: 13.5px; }

        /* Layout */
        .page-wrapper {
            display: flex;
            min-height: calc(100vh - 58px);
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: var(--color-primary);
            padding: 24px 0;
            flex-shrink: 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 24px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            font-family: var(--font-title);
            transition: background 0.15s, color 0.15s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar a i { width: 18px; text-align: center; }

        /* Main content */
        .main-content { flex: 1; padding: 32px; }

        .page-title {
            font-family: var(--font-title);
            font-size: 22px;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 4px;
        }
        .page-subtitle {
            font-size: 13.5px;
            color: #6b7c93;
            margin-bottom: 28px;
        }

        /* Card */
        .card-f {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(30,58,110,0.08);
            padding: 32px;
            max-width: 620px;
        }

        /* Form */
        .form-label-f {
            font-family: var(--font-title);
            font-size: 13.5px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 6px;
        }
        .form-control-f {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d1dce8;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 14px;
            color: #2c3e50;
            background: #fafbfc;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control-f:focus {
            border-color: var(--color-nav);
            box-shadow: 0 0 0 3px rgba(26,79,160,0.1);
        }
        .form-control-f::placeholder { color: #b0bec5; }

        select.form-control-f { cursor: pointer; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* Password strength */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: #e0e0e0;
            margin-top: 8px;
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s, background 0.3s;
            width: 0;
        }
        .strength-label { font-size: 12px; color: #6b7c93; margin-top: 4px; }

        /* Botones */
        .btn-f {
            padding: 11px 28px;
            border-radius: 8px;
            font-family: var(--font-title);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-f:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-primary-f {
            background: linear-gradient(135deg, var(--color-nav), var(--color-primary));
            color: #fff;
            box-shadow: 0 4px 12px rgba(26,79,160,0.25);
        }
        .btn-cancel-f {
            background: #eef2f7;
            color: #4a5568;
        }

        /* Alerta error */
        .alert-f {
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 13.5px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .alert-error-f {
            background: rgba(231,76,60,0.07);
            border-left: 4px solid var(--color-error);
            color: #c0392b;
        }
        .alert-f ul { margin: 0; padding-left: 16px; }

        /* Badge rol */
        .role-info {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .role-badge {
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        .role-admin { background: rgba(30,58,110,0.1); color: var(--color-primary); }
        .role-bib   { background: rgba(26,79,160,0.1); color: var(--color-nav); }
        .role-user  { background: rgba(39,174,96,0.1); color: #1e8449; }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .sidebar  { display: none; }
            .main-content { padding: 20px; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar-fusalmo">
    <a href="index.php?page=dashboard" class="navbar-brand-f">
        <div class="logo-badge">F</div>
        Biblioteca Fusalmo
    </a>
    <span class="nav-user">
        <i class="fas fa-user-shield" style="margin-right:6px;"></i>
        <?= $userName ?>
        &nbsp;|&nbsp;
        <a href="index.php?page=logout" style="color:rgba(255,255,255,0.7);text-decoration:none;font-size:13px;">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </span>
</nav>

<div class="page-wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="index.php?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="index.php?page=libros"><i class="fas fa-book"></i> Libros</a>
        <a href="index.php?page=usuarios" class="active"><i class="fas fa-users"></i> Usuarios</a>
        <a href="index.php?page=prestamos"><i class="fas fa-hand-holding-heart"></i> Préstamos</a>
        <a href="index.php?page=devoluciones"><i class="fas fa-undo-alt"></i> Devoluciones</a>
        <a href="index.php?page=reportes"><i class="fas fa-chart-bar"></i> Reportes</a>
    </aside>

    <!-- Contenido -->
    <main class="main-content">
        <div class="page-title">Registrar Usuario</div>
        <div class="page-subtitle">Crea una nueva cuenta en el sistema</div>

        <?php if (!empty($errors)): ?>
            <div class="alert-f alert-error-f">
                <i class="fas fa-exclamation-circle" style="margin-top:2px;"></i>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= Security::escape($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card-f">
            <form method="POST" action="index.php?page=register&action=process" novalidate id="regForm">
                <?= $csrfField ?>

                <div class="form-row" style="margin-bottom:20px;">
                    <div>
                        <label class="form-label-f" for="nombre">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-control-f"
                            placeholder="ej. Ana García"
                            value="<?= Security::escape($old['nombre'] ?? '') ?>"
                            required>
                    </div>
                    <div>
                        <label class="form-label-f" for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control-f"
                            placeholder="ej. +503 7000-0000"
                            value="<?= Security::escape($old['telefono'] ?? '') ?>">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="form-label-f" for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control-f"
                        placeholder="ej. ana@fusalmo.org"
                        value="<?= Security::escape($old['email'] ?? '') ?>"
                        required>
                </div>

                <div class="form-row" style="margin-bottom:20px;">
                    <div>
                        <label class="form-label-f" for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control-f"
                            placeholder="••••••••" required autocomplete="new-password">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-label" id="strengthLabel">Ingresa una contraseña</div>
                    </div>
                    <div>
                        <label class="form-label-f" for="confirmar">Confirmar contraseña</label>
                        <input type="password" id="confirmar" name="confirmar" class="form-control-f"
                            placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>

                <div style="margin-bottom:28px;">
                    <label class="form-label-f" for="rol">Rol del usuario</label>
                    <select id="rol" name="rol" class="form-control-f" required>
                        <option value="">— Seleccionar rol —</option>
                        <option value="admin"        <?= ($old['rol'] ?? '') === 'admin'         ? 'selected' : '' ?>>Administrador</option>
                        <option value="bibliotecario"<?= ($old['rol'] ?? '') === 'bibliotecario' ? 'selected' : '' ?>>Bibliotecario</option>
                        <option value="usuario"      <?= ($old['rol'] ?? '') === 'usuario'       ? 'selected' : '' ?>>Usuario</option>
                    </select>
                    <div class="role-info">
                        <span class="role-badge role-admin"><i class="fas fa-shield-alt"></i> Admin: acceso total</span>
                        <span class="role-badge role-bib"><i class="fas fa-book"></i> Bib: gestión libros/préstamos</span>
                        <span class="role-badge role-user"><i class="fas fa-user"></i> Usuario: consultas</span>
                    </div>
                </div>

                <div style="display:flex; gap:12px;">
                    <button type="submit" class="btn-f btn-primary-f">
                        <i class="fas fa-user-plus" style="margin-right:7px;"></i>
                        Guardar usuario
                    </button>
                    <a href="index.php?page=usuarios" class="btn-f btn-cancel-f" style="text-decoration:none;display:inline-flex;align-items:center;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
// ── Indicador de fortaleza de contraseña ──
const passInput = document.getElementById('contrasena');
const fill      = document.getElementById('strengthFill');
const label     = document.getElementById('strengthLabel');

passInput.addEventListener('input', () => {
    const v = passInput.value;
    let score = 0;
    if (v.length >= 8)             score++;
    if (/[A-Z]/.test(v))           score++;
    if (/[0-9]/.test(v))           score++;
    if (/[^A-Za-z0-9]/.test(v))    score++;

    const levels = [
        { pct: '0%',   color: '#e0e0e0', text: 'Ingresa una contraseña' },
        { pct: '25%',  color: '#E74C3C', text: 'Débil' },
        { pct: '50%',  color: '#F4A726', text: 'Regular' },
        { pct: '75%',  color: '#1A4FA0', text: 'Buena' },
        { pct: '100%', color: '#27AE60', text: 'Muy segura' },
    ];
    fill.style.width      = levels[score].pct;
    fill.style.background = levels[score].color;
    label.textContent     = levels[score].text;
    label.style.color     = levels[score].color;
});

// ── Validación básica ──
document.getElementById('regForm').addEventListener('submit', function(e) {
    const pass    = document.getElementById('contrasena').value;
    const confirm = document.getElementById('confirmar').value;
    if (pass !== confirm) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
    }
});
</script>
</body>
</html>
