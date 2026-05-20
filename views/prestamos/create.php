<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Préstamo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Nuevo Préstamo</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['prest_errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0"><li><?= implode('<li>', $_SESSION['prest_errors']) ?></ul>
                        </div>
                        <?php unset($_SESSION['prest_errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/index.php?page=prestamos&action=process-create">
                        <?= Security::csrfField() ?>
                        <div class="mb-3">
                            <label for="usuario_id" class="form-label">Usuario</label>
                            <select id="usuario_id" name="usuario_id" class="form-select" required>
                                <option value="">-- Seleccionar usuario --</option>
                                <?php foreach ($usuarios as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= Security::escape($u['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="libro_id" class="form-label">Libro</label>
                            <select id="libro_id" name="libro_id" class="form-select" required>
                                <option value="">-- Seleccionar libro --</option>
                                <?php foreach ($libros as $l): ?>
                                    <option value="<?= $l['id'] ?>"><?= Security::escape($l['titulo']) ?> (<?= $l['cantidad'] ?> disponibles)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/index.php?page=prestamos" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
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
