<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<div class="d-flex justify-content-between align-items-center mb-4">

<h1>📅 Gestión de Préstamos</h1>

<div>

<a
href="index.php?page=prestamos&action=create"
class="btn btn-primary">

Nuevo Préstamo

</a>

<a
href="index.php?page=prestamos&action=vencidos"
class="btn btn-danger">

Vencidos

</a>

</div>

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
<th>Usuario</th>
<th>Libro</th>
<th>Fecha préstamo</th>
<th>Vencimiento</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(empty($prestamos)): ?>

<tr>

<td colspan="7">

No existen préstamos registrados

</td>

</tr>

<?php else: ?>

<?php foreach($prestamos as $p): ?>

<tr>

<td>

<?= $p['id'] ?>

</td>

<td>

<?= Security::escape(
$p['usuario']
) ?>

</td>

<td>

<?= Security::escape(
$p['libro']
) ?>

</td>

<td>

<?= date(
'd/m/Y',
strtotime(
$p['fecha_prestamo']
)
) ?>

</td>

<td>

<?= date(
'd/m/Y',
strtotime(
$p['fecha_devolucion_esperada']
)
) ?>

</td>

<td>

<?= ucfirst(
$p['estado']
) ?>

</td>

<td>

<?php if(
$p['estado']==='activo'
): ?>

<form
method="POST"
action="index.php?page=prestamos&action=devolucion"
style="display:inline;">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $p['id'] ?>">

<button
type="submit"
class="btn btn-success">

Devolver

</button>

</form>


<form
method="POST"
action="index.php?page=prestamos&action=renovar"
style="display:inline;">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $p['id'] ?>">

<button
type="submit"
class="btn btn-warning">

Renovar

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