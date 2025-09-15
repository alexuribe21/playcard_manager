<?php require __DIR__.'/partials/flash.php'; ?>
<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Nueva tarjeta</h1>
  <a class="btn btn-outline-secondary" href="<?= $base ?>/tarjetas/index">Volver</a>
</div>

<form method="post" action="<?= $base ?>/tarjetas/store" class="card card-body shadow-sm">
  <?= CSRF::field() ?>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Código de tarjeta *</label>
      <input type="text" name="str_coditotarjeta" class="form-control" value="<?= htmlspecialchars(($old['str_coditotarjeta'] ?? $t['str_coditotarjeta'] ?? '')) ?>" required>
      <div class="form-text">Formato actual permitido o alfanumérico 6-50.</div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Cliente (opcional)</label>
      <input type="number" name="id_cliente" class="form-control" value="<?= htmlspecialchars(($old['id_cliente'] ?? $t['id_cliente'] ?? '')) ?>" placeholder="ID de cliente">
      <div class="form-text">Ingresa el ID de cliente existente (por ahora).</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Estado</label>
      <?php $estado = $old['enum_estado'] ?? ($t['enum_estado'] ?? 'activa'); ?>
      <select name="enum_estado" class="form-select">
        <option value="activa" <?= $estado==='activa'?'selected':'' ?>>Activa</option>
        <option value="bloqueada" <?= $estado==='bloqueada'?'selected':'' ?>>Bloqueada</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Saldo</label>
      <input type="number" step="0.01" min="0" name="num_saldo" class="form-control" value="<?= htmlspecialchars(($old['num_saldo'] ?? $t['num_saldo'] ?? 0)) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Fecha asignación</label>
      <input type="text" name="datetime_fechaasignacion" class="form-control" placeholder="YYYY-mm-dd HH:ii:ss" value="<?= htmlspecialchars(($old['datetime_fechaasignacion'] ?? $t['datetime_fechaasignacion'] ?? '')) ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">UID (hex opcional)</label>
      <input type="text" name="uid_hex" class="form-control" value="<?= htmlspecialchars(($old['uid_hex'] ?? $t['uid_hex'] ?? '')) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Puntos</label>
      <input type="number" step="1" min="0" name="num_puntos" class="form-control" value="<?= htmlspecialchars(($old['num_puntos'] ?? $t['num_puntos'] ?? 0)) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">PIN (4 dígitos)</label>
      <input type="text" maxlength="4" name="str_pin" class="form-control" value="<?= htmlspecialchars(($old['str_pin'] ?? $t['str_pin'] ?? '')) ?>">
    </div>

    <div class="col-md-6">
      <label class="form-label">Fecha vencimiento</label>
      <input type="date" name="date_fechavencimiento" class="form-control" value="<?= htmlspecialchars(($old['date_fechavencimiento'] ?? $t['date_fechavencimiento'] ?? '')) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Notas</label>
      <input type="text" name="txt_notas" class="form-control" value="<?= htmlspecialchars(($old['txt_notas'] ?? $t['txt_notas'] ?? '')) ?>">
    </div>
  </div>

  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">Guardar</button>
    <a class="btn btn-light" href="<?= $base ?>/tarjetas/index">Cancelar</a>
  </div>
</form>
