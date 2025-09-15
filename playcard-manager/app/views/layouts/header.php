<?php
// app/views/layouts/header.php — solo el pedazo clave con el enlace Salir
if (!defined('BASE_URL')) { define('BASE_URL','/playcard-manager'); }
if (!function_exists('url')) {
  function url(string $path): string { return rtrim(BASE_URL,'/') . '/' . ltrim($path,'/'); }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= url('dashboard/index') ?>">PlayCard Manager</a>
    <div class="d-flex">
      <a class="btn btn-outline-light" href="<?= url('auth/logout') ?>">Salir</a>
    </div>
  </div>
</nav>
