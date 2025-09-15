<?php require_once __DIR__ . '/../partials/basepath.php'; ?>
<?php
if (session_status()!==PHP_SESSION_ACTIVE) session_start();
$user=$_SESSION['user']??null;
?><!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>PlayCard Manager</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>:root{--primary-blue:#2c5aa0;--secondary-blue:#4a90e2;--light-blue:#e3f2fd;--white:#fff;--text-dark:#333;--success:#28a745;--warning:#ffc107;--danger:#dc3545}</style>
</head><body>
<nav class="navbar navbar-expand-lg" style="background-color:var(--primary-blue)">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="<?= BASE_URL ?>/dashboard/index">PlayCard Manager</a>
    <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <?php if ($user): ?>
      <ul class="navbar-nav me-auto">
		<?php if (($user['role'] ?? '') === 'admin'): ?>
			<li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/usuarios/index">Usuarios</a></li>
		<?php endif; ?>
		<li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/perfil/index">Mi perfil</a></li>	  
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/clientes/index">Clientes</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/tarjetas/index">Tarjetas</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/tarjetas/saldo">Saldo</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/transacciones/cobro">Cobros</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/premios/index">Premios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/juegos/index">Juegos</a></li>
		<li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/reportes/index">Reportes</a></li>
		<li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/reportes/resumenClientes">Resumen clientes</a></li>
		<li class="nav-item"><a class="nav-link text-white" href="<?= BASE_URL ?>/reportes/cierreDiario">Cierre diario</a></li>
      </ul>
      <div class="d-flex text-white align-items-center">
        <span class="me-3">👤 <?= htmlspecialchars($user['user']??'') ?> (<?= htmlspecialchars($user['role']??'') ?>)</span>
        <a class="btn btn-sm btn-light" href="<?= url('auth/logout') ?>">Salir</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</nav>
