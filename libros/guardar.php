<?php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php?page=login');
    exit();
}
include("../config/conexion.php");

$titulo = trim($_POST['titulo']);
$autor = trim($_POST['autor']);
$categoria = trim($_POST['categoria']);
$isbn = trim($_POST['isbn']);
$cantidad = trim($_POST['cantidad']);

if (
    empty($titulo) ||
    empty($autor) ||
    empty($categoria) ||
    empty($isbn) ||
    empty($cantidad)
) {
    die("Todos los campos son obligatorios");
}

if (!is_numeric($cantidad)) {
    die("Cantidad inválida");
}

$verificar = "SELECT id FROM libros WHERE isbn = ?";
$stmt = $conn->prepare($verificar);
$stmt->bind_param("s", $isbn);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    die("El ISBN ya existe");
}

$sql = "INSERT INTO libros
(titulo, autor, categoria, isbn, cantidad)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssi",
    $titulo,
    $autor,
    $categoria,
    $isbn,
    $cantidad
);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    echo "Error al guardar";

}

?>