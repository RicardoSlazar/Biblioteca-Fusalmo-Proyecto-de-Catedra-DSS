<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Autor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">Editar Autor</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['aut_errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0"><li><?= implode('<li>', $_SESSION['aut_errors']) ?></ul>
                        </div>
                        <?php unset($_SESSION['aut_errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/index.php?page=autores&action=process-edit">
                        <?= Security::csrfField() ?>
                        <input type="hidden" name="id" value="<?= $autor['id'] ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= Security::escape($autor['nombre']) ?>" required minlength="3">
                        </div>
                        <div class="mb-3">
                            <label for="nacionalidad" class="form-label">Nacionalidad</label>
                            <input type="text" id="nacionalidad" name="nacionalidad" class="form-control" value="<?= Security::escape($autor['nacionalidad'] ?? '') ?>">
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/index.php?page=autores" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
