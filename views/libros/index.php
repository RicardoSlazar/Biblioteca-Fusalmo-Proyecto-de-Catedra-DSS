<?php require_once __DIR__.'/../layouts/header.php'; ?>

<style>
.badge-disponible{
    background:#28a745;
}

.badge-reservado{
    background:#ffc107;
    color:black;
}

.badge-agotado{
    background:#dc3545;
}
</style>

<div class="card">

<div class="d-flex justify-content-between align-items-center mb-4">

<h1>📚 Gestión de Libros</h1>

<a href="index.php?page=libros&action=create"
class="btn btn-primary">

Agregar Libro

</a>

</div>

<!-- MENSAJES -->

<?php if(!empty($_SESSION['success'])): ?>

<div class="alert alert-success">

<?= Security::escape($_SESSION['success']) ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if(!empty($_SESSION['error'])): ?>

<div class="alert alert-danger">

<?= Security::escape($_SESSION['error']) ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>


<!-- BUSCADOR -->

<div class="card mb-4">

<form
method="GET"
action="index.php">

<input
type="hidden"
name="page"
value="libros">

<input
type="hidden"
name="action"
value="search">

<input
type="text"
name="q"
placeholder="Buscar por título o ISBN"
value="<?= Security::escape($_GET['q'] ?? '') ?>">

<button
type="submit"
class="btn btn-primary">

Buscar

</button>

</form>

</div>


<!-- TABLA -->

<table>

<thead>

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

<?php if(empty($libros)): ?>

<tr>

<td colspan="8">

No hay libros registrados

</td>

</tr>

<?php else: ?>

<?php foreach($libros as $libro): ?>

<tr>

<td><?= $libro['id'] ?></td>

<td>

<strong>

<?= Security::escape(
$libro['titulo']
) ?>

</strong>

</td>

<td>

<?= Security::escape(
$libro['autor'] ?? '-'
) ?>

</td>

<td>

<?= Security::escape(
$libro['categoria'] ?? '-'
) ?>

</td>

<td>

<?= Security::escape(
$libro['isbn']
) ?>

</td>

<td>

<?= $libro['cantidad'] ?>

</td>

<td>

<span
class="badge badge-<?= $libro['estado'] ?>">

<?= ucfirst(
$libro['estado']
) ?>

</span>

</td>

<td>

<a
href="index.php?page=libros&action=edit&id=<?= $libro['id'] ?>"
class="btn btn-warning">

Editar

</a>

<?php if($_SESSION['user_role']==='admin'): ?>

<form
method="POST"
action="index.php?page=libros&action=delete"
style="display:inline;">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $libro['id'] ?>">

<button
type="submit"
class="btn btn-danger">

Eliminar

</button>

</form>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>