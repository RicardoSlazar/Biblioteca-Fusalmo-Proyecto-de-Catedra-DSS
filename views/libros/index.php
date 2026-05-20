<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .badge-disponible { background-color: #28a745; }
        .badge-reservado { background-color: #ffc107; color: #000; }
        .badge-agotado { background-color: #dc3545; }
    </style>
</head>
<body>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-book"></i> Gestión de Libros</h1>
        <a href="/index.php?page=libros&action=create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar Libro
        </a>
    </div>

    <!-- Mensajes -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= Security::escape($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Buscador -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/index.php" class="row g-3">
                <input type="hidden" name="page" value="libros">
                <input type="hidden" name="action" value="search">
                <div class="col-md-10">
                    <input type="text" name="q" class="form-control" placeholder="Buscar por título o ISBN..." value="<?= Security::escape($_GET['q'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>ISBN</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($libros)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                            No hay libros registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= Security::escape($libro['id']) ?></td>
                            <td><strong><?= Security::escape($libro['titulo']) ?></strong></td>
                            <td><?= Security::escape($libro['autor'] ?? '-') ?></td>
                            <td><?= Security::escape($libro['categoria'] ?? '-') ?></td>
                            <td><code><?= Security::escape($libro['isbn']) ?></code></td>
                            <td>
                                <span class="badge bg-info"><?= $libro['cantidad'] ?></span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $libro['estado'] ?>">
                                    <?= ucfirst($libro['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/index.php?page=libros&action=edit&id=<?= $libro['id'] ?>" 
                                       class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <form method="POST" action="/index.php?page=libros&action=delete" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Eliminar libro?');">
                                            <?= Security::csrfField() ?>
                                            <input type="hidden" name="id" value="<?= $libro['id'] ?>">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
