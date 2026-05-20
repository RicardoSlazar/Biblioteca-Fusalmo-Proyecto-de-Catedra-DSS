<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="card">

<h1>Dashboard Biblioteca Fusalmo</h1>

<div style="display:flex;gap:15px;flex-wrap:wrap;">

<a
href="index.php?page=libros"
class="btn btn-primary">

Libros

</a>

<a
href="index.php?page=categorias"
class="btn btn-success">

Categorías

</a>

<a
href="index.php?page=prestamos"
class="btn btn-warning">

Préstamos

</a>

<a
href="index.php?page=usuarios"
class="btn btn-danger">

Usuarios

</a>

</div>

</div>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>