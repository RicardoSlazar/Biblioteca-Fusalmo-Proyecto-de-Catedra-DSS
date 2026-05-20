<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Vencidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-exclamation-circle"></i> Préstamos Vencidos</h1>
        <a href="/index.php?page=prestamos" class="btn btn-secondary">Volver</a>
    </div>

    <div class="alert alert-warning">
        <i class="bi bi-info-circle"></i> Se aplicará multa de 50,000 pesos por cada préstamo vencido al registrar devolución.
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-danger">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Vencimiento</th>
                    <th>Atraso (días)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vencidos)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            ✅ No hay préstamos vencidos
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vencidos as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><strong><?= Security::escape($v['usuario']) ?></strong></td>
                            <td><?= Security::escape($v['libro']) ?></td>
                            <td><?= date('d/m/Y', strtotime($v['fecha_devolucion_esperada'])) ?></td>
                            <td>
                                <span class="badge bg-danger"><?= $v['dias_atraso'] ?> días</span>
                            </td>
                            <td>
                                <form method="POST" action="/index.php?page=prestamos&action=devolucion" style="display:inline;">
                                    <?= Security::csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Registrar Devolución</button>
                                </form>
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
