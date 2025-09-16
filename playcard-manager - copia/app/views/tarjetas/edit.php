<?php
// Helper seguro para imprimir strings aun si vienen NULL
if (!function_exists('e')) {
  function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
$t = $t ?? []; // evita notices si no está definido
$id = (int)($t['id_tarjeta'] ?? 0);
$code = (string)($t['str_coditotarjeta'] ?? '');
$id_cliente = ($t['id_cliente'] ?? '') === null ? '' : (string)$t['id_cliente'];
$saldo = number_format((float)($t['num_saldo'] ?? 0), 2, '.', '');
$estado = (string)($t['enum_estado'] ?? 'activa');
?>
<div class="container py-4">
  <h4>Editar tarjeta #<?= $id ?></h4>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= BASE_URL ?>/tarjetas/update" class="row g-3">
    <?= \CSRF::field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="col-md-6">
      <label class="form-label">Código de tarjeta</label>
      <input type="text" name="str_coditotarjeta" class="form-control" value="<?= e($code) ?>" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Saldo</label>
      <input type="number" step="0.01" min="0" name="num_saldo" class="form-control" value="<?= e($saldo) ?>">
    </div>

    <div class="col-md-3">
      <label class="form-label">Estado</label>
      <select name="enum_estado" class="form-select">
        <option value="activa"   <?= $estado==='activa'?'selected':'' ?>>activa</option>
        <option value="bloqueada"<?= $estado==='bloqueada'?'selected':'' ?>>bloqueada</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">ID Cliente (opcional)</label>
      <input type="text" name="id_cliente" class="form-control" value="<?= e($id_cliente) ?>" placeholder="Dejar vacío para no asignar">
    </div>

    <div class="col-12">
      <button class="btn btn-primary">Guardar</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/tarjetas/index">Volver</a>
    </div>
  </form>
</div>
