<?php

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