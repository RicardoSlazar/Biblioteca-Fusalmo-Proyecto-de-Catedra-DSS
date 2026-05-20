<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-pen"></i> Gestión de Autores</h1>
        <a href="/index.php?page=autores&action=create" class="btn btn-primary">
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
                <tr><th>ID</th><th>Nombre</th><th>Nacionalidad</th><th>Libros</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($autores as $aut): ?>
                    <tr>
                        <td><?= $aut['id'] ?></td>
                        <td><strong><?= Security::escape($aut['nombre']) ?></strong></td>
                        <td><?= Security::escape($aut['nacionalidad'] ?? '-') ?></td>
                        <td><span class="badge bg-info"><?= $this->autorModel->getLibrosCount($aut['id']) ?></span></td>
                        <td>
                            <a href="/index.php?page=autores&action=edit&id=<?= $aut['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                            <form method="POST" action="/index.php?page=autores&action=delete" style="display:inline;" onsubmit="return confirm('¿Eliminar?');">
                                <?= Security::csrfField() ?>
                                <input type="hidden" name="id" value="<?= $aut['id'] ?>">
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
