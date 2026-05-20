<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-tag"></i> Gestión de Categorías</h1>
        <a href="/index.php?page=categorias&action=create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar
        </a>
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
                <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Libros</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><strong><?= Security::escape($cat['nombre']) ?></strong></td>
                        <td><?= Security::escape($cat['descripcion'] ?? '-') ?></td>
                        <td><span class="badge bg-info"><?= $this->categoriaModel->getLibrosCount($cat['id']) ?></span></td>
                        <td>
                            <a href="/index.php?page=categorias&action=edit&id=<?= $cat['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                            <form method="POST" action="/index.php?page=categorias&action=delete" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                                <?= Security::csrfField() ?>
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
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
