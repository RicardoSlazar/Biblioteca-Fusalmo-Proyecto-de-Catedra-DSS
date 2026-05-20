<?php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php?page=login');
    exit();
}
include("../config/conexion.php");

$id = $_GET['id'];

$sql = "SELECT * FROM libros WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$libro = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>Editar Libro</h2>

    <form action="actualizar.php" method="POST">

        <input type="hidden" name="id" value="<?= $libro['id'] ?>">

        <div class="mb-3">
            <label>Título</label>

            <input type="text"
            name="titulo"
            class="form-control"
            value="<?= $libro['titulo'] ?>">
        </div>

        <div class="mb-3">
            <label>Autor</label>

            <input type="text"
            name="autor"
            class="form-control"
            value="<?= $libro['autor'] ?>">
        </div>

        <div class="mb-3">
            <label>Categoría</label>

            <input type="text"
            name="categoria"
            class="form-control"
            value="<?= $libro['categoria'] ?>">
        </div>

        <div class="mb-3">
            <label>ISBN</label>

            <input type="text"
            name="isbn"
            class="form-control"
            value="<?= $libro['isbn'] ?>">
        </div>

        <div class="mb-3">
            <label>Cantidad</label>

            <input type="number"
            name="cantidad"
            class="form-control"
            value="<?= $libro['cantidad'] ?>">
        </div>

        <div class="mb-3">

            <label>Estado</label>

            <select name="estado" class="form-control">

                <option value="Disponible"
                <?= $libro['estado'] == 'Disponible' ? 'selected' : '' ?>>
                    Disponible
                </option>

                <option value="Prestado"
                <?= $libro['estado'] == 'Prestado' ? 'selected' : '' ?>>
                    Prestado
                </option>

                <option value="Dañado"
                <?= $libro['estado'] == 'Dañado' ? 'selected' : '' ?>>
                    Dañado
                </option>

            </select>

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a href="index.php" class="btn btn-secondary">
            Regresar
        </a>

    </form>

</div>

</body>
</html>