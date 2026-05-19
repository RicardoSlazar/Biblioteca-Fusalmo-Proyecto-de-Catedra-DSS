<?php

include("../config/conexion.php");

$id = $_POST['id'];
$titulo = trim($_POST['titulo']);
$autor = trim($_POST['autor']);
$categoria = trim($_POST['categoria']);
$isbn = trim($_POST['isbn']);
$cantidad = trim($_POST['cantidad']);
$estado = trim($_POST['estado']);

$sql = "UPDATE libros
SET titulo = ?,
autor = ?,
categoria = ?,
isbn = ?,
cantidad = ?,
estado = ?
WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssisi",
    $titulo,
    $autor,
    $categoria,
    $isbn,
    $cantidad,
    $estado,
    $id
);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    echo "Error al actualizar";

}

?>