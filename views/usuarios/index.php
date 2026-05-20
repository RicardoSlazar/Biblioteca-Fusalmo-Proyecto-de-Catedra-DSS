<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .badge-activo { background-color: #28a745; }
        .badge-inactivo { background-color: #dc3545; }
        .badge-admin { background-color: #007bff; }
        .badge-bibliotecario { background-color: #17a2b8; }
        .badge-usuario { background-color: #6c757d; }
    </style>
</head>
<body>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people"></i> Gestión de Usuarios</h1>
        <a href="/index.php?page=register" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Usuario
        </a>
    </div>

    <!-- Mensajes de éxito/error -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= Security::escape($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?= Security::escape($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                            No hay usuarios registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= Security::escape($usuario['id']) ?></td>
                            <td><strong><?= Security::escape($usuario['nombre']) ?></strong></td>
                            <td><?= Security::escape($usuario['correo']) ?></td>
                            <td><?= Security::escape($usuario['telefono'] ?: '-') ?></td>
                            <td>
                                <span class="badge badge-<?= $usuario['rol'] ?>">
                                    <?= ucfirst($usuario['rol']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $usuario['estado'] ?>">
                                    <?= ucfirst($usuario['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <small><?= date('d/m/Y', strtotime($usuario['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Editar -->
                                    <a href="/index.php?page=usuarios&action=edit&id=<?= $usuario['id'] ?>"
                                       class="btn btn-warning" title="Editar usuario">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Cambiar contraseña -->
                                    <a href="/index.php?page=usuarios&action=change-password&id=<?= $usuario['id'] ?>"
                                       class="btn btn-info" title="Cambiar contraseña">
                                        <i class="bi bi-key"></i>
                                    </a>

                                    <!-- Cambiar estado -->
                                    <form method="POST" action="/index.php?page=usuarios&action=toggle-status" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('¿Cambiar estado del usuario?');">
                                        <?= Security::csrfField() ?>
                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                        <button type="submit" class="btn btn-secondary"
                                                title="Cambiar estado (<?= $usuario['estado'] === 'activo' ? 'Inactivar' : 'Activar' ?>)">
                                            <i class="bi bi-toggle-<?= $usuario['estado'] === 'activo' ? 'on' : 'off' ?>"></i>
                                        </button>
                                    </form>

                                    <!-- Eliminar -->
                                    <?php if ($usuario['id'] !== $_SESSION['user_id']): ?>
                                        <form method="POST" action="/index.php?page=usuarios&action=delete"
                                              style="display: inline;"
                                              onsubmit="return confirm('⚠️ ¿Estás seguro de eliminar este usuario? Esta acción es irreversible.');">
                                            <?= Security::csrfField() ?>
                                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                            <button type="submit" class="btn btn-danger" title="Eliminar usuario">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-danger" disabled title="No puedes eliminar tu propia cuenta">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
