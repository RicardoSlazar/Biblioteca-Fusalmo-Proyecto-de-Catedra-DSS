<?php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php?page=login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Libro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="../assets/js/validaciones.js"></script>
</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Agregar Libro</h2>

    <form action="guardar.php" method="POST" onsubmit="return validarFormulario()">

        <div class="mb-3">
            <label>Título</label>

            <input type="text"
            name="titulo"
            id="titulo"
            class="form-control">
        </div>

        <div class="mb-3">
            <label>Autor</label>

            <input type="text"
            name="autor"
            id="autor"
            class="form-control">
        </div>

        <div class="mb-3">
            <label>Categoría</label>

            <input type="text"
            name="categoria"
            id="categoria"
            class="form-control">
        </div>

        <div class="mb-3">
            <label>ISBN</label>

            <input type="text"
            name="isbn"
            id="isbn"
            class="form-control">
        </div>

        <div class="mb-3">
            <label>Cantidad</label>

            <input type="number"
            name="cantidad"
            id="cantidad"
            class="form-control">
        </div>

        <button type="submit" class="btn btn-success">
            Guardar
        </button>

        <a href="index.php" class="btn btn-secondary">
            Regresar
        </a>

    </form>

</div>

</body>
</html>