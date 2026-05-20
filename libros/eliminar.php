<?php
session_start();
if (empty($_SESSION['logged_in'])) {
    header('Location: ../index.php?page=login');
    exit();
}
include("../config/conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM libros WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    echo "Error al eliminar";

}

?>