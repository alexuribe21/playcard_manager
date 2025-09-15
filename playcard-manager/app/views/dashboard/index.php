<?php
// app/views/dashboard/index.php — corregido botón Salir con BASE_URL
require_once __DIR__ . '/../partials/basepath.php';
?>
<div class="container">
  <h2>Panel de control</h2>

  <p>Bienvenido al sistema PlayCard Manager.</p>

  <div class="mt-3">
    <!-- Botón Salir corregido -->
    <a href="<?= url('auth/login') ?>" class="btn btn-danger">Salir</a>
  </div>
</div>
