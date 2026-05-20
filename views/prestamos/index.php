<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-check"></i> Gestión de Préstamos</h1>
        <div>
            <a href="/index.php?page=prestamos&action=create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Préstamo
            </a>
            <a href="/index.php?page=prestamos&action=vencidos" class="btn btn-danger">
                <i class="bi bi-exclamation-circle"></i> Vencidos
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= Security::escape($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha Préstamo</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= Security::escape($p['usuario']) ?></td>
                        <td><?= Security::escape($p['libro']) ?></td>
                        <td><?= date('d/m/Y', strtotime($p['fecha_prestamo'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($p['fecha_devolucion_esperada'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $p['estado'] === 'activo' ? 'info' : 'success' ?>">
                                <?= ucfirst($p['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($p['estado'] === 'activo'): ?>
                                <form method="POST" action="/index.php?page=prestamos&action=devolucion" style="display:inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Devolver</button>
                                </form>
                                <form method="POST" action="/index.php?page=prestamos&action=renovar" style="display:inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn btn-info btn-sm">Renovar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
