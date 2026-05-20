<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <h1><i class="bi bi-search"></i> Resultados de Búsqueda</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/index.php" class="row g-3">
                <input type="hidden" name="page" value="libros">
                <input type="hidden" name="action" value="search">
                <div class="col-md-10">
                    <input type="text" name="q" class="form-control" placeholder="Buscar por título o ISBN..." value="<?= Security::escape($query) ?>" autofocus>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($query)): ?>
        <p class="text-muted">Ingresa un término de búsqueda.</p>
    <?php elseif (empty($libros)): ?>
        <div class="alert alert-info">
            No se encontraron libros para "<strong><?= Security::escape($query) ?></strong>"
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>ISBN</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= Security::escape($libro['titulo']) ?></td>
                            <td><?= Security::escape($libro['autor'] ?? '-') ?></td>
                            <td><?= Security::escape($libro['categoria'] ?? '-') ?></td>
                            <td><code><?= Security::escape($libro['isbn']) ?></code></td>
                            <td><?= $libro['cantidad'] ?></td>
                            <td>
                                <span class="badge badge-<?= $libro['estado'] ?>">
                                    <?= ucfirst($libro['estado']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="/index.php?page=libros" class="btn btn-secondary">Volver a Libros</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
