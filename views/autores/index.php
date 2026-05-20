<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<div
style="
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;">

<h1>✍️ Gestión de Autores</h1>

<a
href="index.php?page=autores&action=create"
class="btn btn-primary">

Agregar Autor

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
<th>Nacionalidad</th>
<th>Libros</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(empty($autores)): ?>

<tr>

<td colspan="5">

No existen autores registrados

</td>

</tr>

<?php else: ?>

<?php foreach($autores as $aut): ?>

<tr>

<td>

<?= $aut['id'] ?>

</td>

<td>

<strong>

<?= Security::escape(
$aut['nombre']
) ?>

</strong>

</td>

<td>

<?= Security::escape(
$aut['nacionalidad']
?? '-'
) ?>

</td>

<td>

<?= $this->autorModel
->getLibrosCount(
$aut['id']
) ?>

</td>

<td>

<a
href="index.php?page=autores&action=edit&id=<?= $aut['id'] ?>"
class="btn btn-warning">

Editar

</a>


<form
method="POST"
action="index.php?page=autores&action=delete"
style="display:inline;"
onsubmit="return confirm('¿Eliminar autor?');">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $aut['id'] ?>">

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