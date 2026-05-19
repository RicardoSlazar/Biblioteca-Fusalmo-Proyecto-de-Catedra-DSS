<?php
include("../config/conexion.php");

$sql = "SELECT * FROM libros";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Libros</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>

<body>

<div class="container mt-5">

    <h1 class="mb-4 text-center">Gestión de Libros</h1>

    <a href="crear.php" class="btn btn-primary mb-3">
        Agregar Libro
    </a>

    <table class="table table-bordered table-hover">

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

        <?php while($fila = $resultado->fetch_assoc()) { ?>

            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= $fila['titulo'] ?></td>
                <td><?= $fila['autor'] ?></td>
                <td><?= $fila['categoria'] ?></td>
                <td><?= $fila['isbn'] ?></td>
                <td><?= $fila['cantidad'] ?></td>
                <td><?= $fila['estado'] ?></td>

                <td>

                    <a href="editar.php?id=<?= $fila['id'] ?>"
                    class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <a href="eliminar.php?id=<?= $fila['id'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('¿Deseas eliminar este libro?')">
                        Eliminar
                    </a>

                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</body>
</html>