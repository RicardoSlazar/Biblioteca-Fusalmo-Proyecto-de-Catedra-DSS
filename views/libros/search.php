<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<h1>📚 Resultados de Búsqueda</h1>

<br>

<!-- FORMULARIO -->

<form
method="GET"
action="index.php"
style="
display:flex;
gap:10px;
margin-bottom:25px;">

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
placeholder="Buscar por título o ISBN..."
value="<?= Security::escape($query) ?>"
autofocus>

<button
type="submit"
class="btn btn-primary">

Buscar

</button>

</form>


<!-- RESULTADOS -->

<?php if(empty($query)): ?>

<div class="alert alert-info">

Ingresa un término de búsqueda.

</div>

<?php elseif(empty($libros)): ?>

<div class="alert alert-warning">

No se encontraron libros para:

<strong>

<?= Security::escape($query) ?>

</strong>

</div>

<?php else: ?>

<table>

<thead>

<tr>

<th>Título</th>
<th>Autor</th>
<th>Categoría</th>
<th>ISBN</th>
<th>Cantidad</th>
<th>Estado</th>

</tr>

</thead>

<tbody>

<?php foreach($libros as $libro): ?>

<tr>

<td>

<strong>

<?= Security::escape(
$libro['titulo']
) ?>

</strong>

</td>

<td>

<?= Security::escape(
$libro['autor']
?? '-'
) ?>

</td>

<td>

<?= Security::escape(
$libro['categoria']
?? '-'
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

<?php

$estadoClase='btn-success';

if(
$libro['estado']==='prestado'
){
$estadoClase='btn-warning';
}

if(
$libro['estado']==='perdido'
){
$estadoClase='btn-danger';
}

?>

<span class="btn <?= $estadoClase ?>">

<?= ucfirst(
$libro['estado']
) ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

<br>

<a
href="index.php?page=libros"
class="btn btn-secondary">

← Volver a Libros

</a>

</div>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>