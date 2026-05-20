<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<div
style="
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;">

<h1>🏷️ Gestión de Categorías</h1>

<a
href="index.php?page=categorias&action=create"
class="btn btn-primary">

Agregar Categoría

</a>

</div>


<!-- MENSAJES -->

<?php if(!empty($_SESSION['success'])): ?>

<div class="alert alert-success">

<?= Security::escape(
$_SESSION['success']
) ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>


<!-- TABLA -->

<table>

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Libros</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(empty($categorias)): ?>

<tr>

<td colspan="5">

No existen categorías registradas

</td>

</tr>

<?php else: ?>

<?php foreach($categorias as $cat): ?>

<tr>

<td>

<?= $cat['id'] ?>

</td>

<td>

<strong>

<?= Security::escape(
$cat['nombre']
) ?>

</strong>

</td>

<td>

<?= Security::escape(
$cat['descripcion']
?? '-'
) ?>

</td>

<td>

<?= $this->categoriaModel
->getLibrosCount(
$cat['id']
) ?>

</td>

<td>

<a
href="index.php?page=categorias&action=edit&id=<?= $cat['id'] ?>"
class="btn btn-warning">

Editar

</a>


<form
method="POST"
action="index.php?page=categorias&action=delete"
style="display:inline;"
onsubmit="return confirm('¿Eliminar categoría?');">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $cat['id'] ?>">

<button
type="submit"
class="btn btn-danger">

Eliminar

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>