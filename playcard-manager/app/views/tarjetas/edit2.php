<?php
// app/views/tarjetas/edit.php — vista de edición de tarjeta completa
require_once __DIR__ . '/../partials/basepath.php';

$id = $t['id_tarjeta'] ?? ($t->id_tarjeta ?? 0);
$codigo = $_SESSION['old']['str_coditotarjeta'] ?? ($t['str_coditotarjeta'] ?? '');
$estado = $_SESSION['old']['enum_estado'] ?? ($t['enum_estado'] ?? 'activa');
$saldo  = $_SESSION['old']['num_saldo'] ?? ($t['num_saldo'] ?? '0.00');
$puntos = $_SESSION['old']['num_puntos'] ?? ($t['num_puntos'] ?? '0');
$pin    = $_SESSION['old']['str_pin'] ?? ($t['str_pin'] ?? '');
$venc   = $_SESSION['old']['date_fechavencimiento'] ?? ($t['date_fechavencimiento'] ?? '');
$notas  = $_SESSION['old']['txt_notas'] ?? ($t['txt_notas'] ?? '');
?>
<div class="container">
  <h2 class="mb-3">Editar tarjeta #<?= htmlspecialchars((string)$id) ?></h2>

  <div class="card mb-4">
    <div class="card-body">
      <form method="post" action="<?= url('tarjetas/update?id=' . $id) ?>">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>"/>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Código</label>
            <input class="form-control" name="str_coditotarjeta" value="<?= htmlspecialchars($codigo) ?>"/>
          </div>
          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="enum_estado">
              <option value="activa"   <?= $estado==='activa'?'selected':'' ?>>Activa</option>
              <option value="bloqueada"<?= $estado==='bloqueada'?'selected':'' ?>>Bloqueada</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Saldo</label>
            <input type="number" step="0.01" min="0" class="form-control" name="num_saldo" value="<?= htmlspecialchars($saldo) ?>"/>
          </div>
          <div class="col-md-3">
            <label class="form-label">Puntos</label>
            <input type="number" min="0" class="form-control" name="num_puntos" value="<?= htmlspecialchars($puntos) ?>"/>
          </div>
          <div class="col-md-3">
            <label class="form-label">PIN</label>
            <input type="text" maxlength="4" class="form-control" name="str_pin" value="<?= htmlspecialchars($pin) ?>"/>
          </div>
          <div class="col-md-3">
            <label class="form-label">Fecha vencimiento</label>
            <input type="date" class="form-control" name="date_fechavencimiento" value="<?= htmlspecialchars($venc) ?>"/>
          </div>
          <div class="col-md-12">
            <label class="form-label">Notas</label>
            <textarea class="form-control" name="txt_notas"><?= htmlspecialchars($notas) ?></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary">Guardar cambios</button>
          <a class="btn btn-secondary" href="<?= url('tarjetas/index') ?>">Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>
