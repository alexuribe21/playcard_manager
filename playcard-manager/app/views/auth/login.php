<?php
// app/views/auth/login.php
// Vista de login con action armado usando BASE_URL/url()
// Carga basepath por si la app no trae el helper global
$__partials = __DIR__ . '/../partials/basepath.php';
if (file_exists($__partials)) require_once $__partials;
if (!defined('BASE_URL')) { define('BASE_URL','/playcard-manager'); }
if (!function_exists('url')) {
  function url(string $path): string { return rtrim(BASE_URL,'/') . '/' . ltrim($path,'/'); }
}
?>
<div class="container mt-5" style="max-width:420px;">
  <h2 class="mb-4">Acceso</h2>

  <?php if (function_exists('Flash::get')): ?>
    <?php if ($flash = Flash::get()): ?>
      <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?>">
        <?= htmlspecialchars($flash['message'] ?? '') ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <form method="post" action="<?= htmlspecialchars(url('auth/login')) ?>">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(class_exists('CSRF') ? CSRF::token() : '') ?>">
    <div class="mb-3">
      <label for="username" class="form-label">Usuario</label>
      <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Contraseña</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
  </form>
</div>
