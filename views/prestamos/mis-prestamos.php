<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container-fluid mt-4">
    <h1><i class="bi bi-calendar-check"></i> Mis Préstamos Activos</h1>

    <?php if ($multas > 0): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i> <strong>Debes pagar una multa de $<?= number_format($multas, 0) ?></strong>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Libro</th>
                    <th>Fecha Préstamo</th>
                    <th>Devolución Esperada</th>
                    <th>Atraso</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prestamos)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            ℹ️ No tienes préstamos activos
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prestamos as $p): ?>
                        <tr>
                            <td><?= Security::escape($p['titulo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($p['fecha_prestamo'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($p['fecha_devolucion_esperada'])) ?></td>
                            <td>
                                <?php if ($p['dias_atraso'] > 0): ?>
                                    <span class="badge bg-danger"><?= $p['dias_atraso'] ?> días atraso</span>
                                <?php elseif ($p['dias_atraso'] < 0): ?>
                                    <span class="badge bg-success"><?= abs($p['dias_atraso']) ?> días restantes</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Vence hoy</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['multa'] > 0): ?>
                                    <span class="badge bg-danger">Multa: $<?= number_format($p['multa'], 0) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-info">Activo</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="/index.php?page=dashboard" class="btn btn-secondary">Volver</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
