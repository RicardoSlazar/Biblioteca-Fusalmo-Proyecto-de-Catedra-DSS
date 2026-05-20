<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<div
style="
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;">

<h1>⏰ Préstamos Vencidos</h1>

<a
href="index.php?page=prestamos"
class="btn btn-secondary">

Volver

</a>

</div>


<div class="alert-vencidos">

⚠ Se aplicará multa de $30 USD por cada préstamo vencido al registrar devolución.

</div>


<table>

<thead>

<tr>

<th>ID</th>
<th>Usuario</th>
<th>Libro</th>
<th>Vencimiento</th>
<th>Atraso (días)</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(empty($vencidos)): ?>

<tr>

<td colspan="6"
style="
text-align:center;
padding:30px;">

✅ No hay préstamos vencidos

</td>

</tr>

<?php else: ?>

<?php foreach($vencidos as $v): ?>

<tr>

<td><?= $v['id'] ?></td>

<td>

<strong>

<?= Security::escape(
$v['usuario']
) ?>

</strong>

</td>

<td>

<?= Security::escape(
$v['libro']
) ?>

</td>

<td>

<?= date(
'd/m/Y',
strtotime(
$v['fecha_devolucion_esperada']
)
) ?>

</td>

<td>

<span class="badge-atraso">

<?= $v['dias_atraso'] ?>

días

</span>

</td>

<td>

<form
method="POST"
action="index.php?page=prestamos&action=devolucion"
style="display:inline;">

<?= Security::csrfField() ?>

<input
type="hidden"
name="id"
value="<?= $v['id'] ?>">

<button
type="submit"
class="btn btn-success">

Registrar devolución

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