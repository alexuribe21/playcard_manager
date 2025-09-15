<?php
// app/views/tarjetas/edit.php — Formulario corregido usando $t y null safety
require_once __DIR__ . '/../partials/basepath.php';
?>
<div class="container">
  <h2>Editar tarjeta #<?= htmlspecialchars($t['id_tarjeta'] ?? '') ?></h2>

  <form method="post" action="<?= url('tarjetas/update?id=' . ($t['id_tarjeta'] ?? 0)) ?>">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>">

    <div class="row mb-3">
      <div class="col">
        <label>Código</label>
        <input type="text" class="form-control" name="str_coditotarjeta" 
               value="<?= htmlspecialchars($t['str_coditotarjeta'] ?? '') ?>">
      </div>
      <div class="col">
        <label>Estado</label>
        <?php $estado = $t['enum_estado'] ?? 'activa'; ?>
        <select name="enum_estado" class="form-control">
          <option value="activa" <?= $estado=='activa'?'selected':'' ?>>Activa</option>
          <option value="bloqueada" <?= $estado=='bloqueada'?'selected':'' ?>>Bloqueada</option>
        </select>
      </div>
      <div class="col">
        <label>Saldo</label>
        <input type="number" step="0.01" class="form-control" name="num_saldo" 
               value="<?= htmlspecialchars($t['num_saldo'] ?? '0.00') ?>">
      </div>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label>Puntos</label>
        <input type="number" class="form-control" name="num_puntos" 
               value="<?= htmlspecialchars($t['num_puntos'] ?? '0') ?>">
      </div>
      <div class="col">
        <label>PIN</label>
        <input type="text" maxlength="4" class="form-control" name="str_pin" 
               value="<?= htmlspecialchars($t['str_pin'] ?? '') ?>">
      </div>
      <div class="col">
        <label>Fecha vencimiento</label>
        <input type="date" class="form-control" name="date_fechavencimiento" 
               value="<?= htmlspecialchars($t['date_fechavencimiento'] ?? '') ?>">
      </div>
    </div>

    <div class="mb-3">
      <label>Notas</label>
      <textarea class="form-control" name="txt_notas"><?= htmlspecialchars($t['txt_notas'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="<?= url('tarjetas/index') ?>" class="btn btn-secondary">Volver</a>
  </form>
</div>
