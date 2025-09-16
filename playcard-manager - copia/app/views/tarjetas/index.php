<?php
// Helper para evitar warnings de PHP 8.x cuando llegan NULLs desde la BD
if (!function_exists('e')) {
  function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Tarjetas</h4>
    <div>
      <form class="d-inline" method="get" action="<?= BASE_URL ?>/tarjetas/index">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="form-control d-inline" style="width:260px" placeholder="Buscar por código o cliente">
      </form>
      <?php if (($user['role'] ?? '') === 'admin'): ?>
        <a class="btn btn-primary ms-2" href="<?= BASE_URL ?>/tarjetas/create">Nueva tarjeta</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead>
        <tr>
          <th>ID</th><th>Código</th><th>Cliente</th><th>Saldo</th><th>Estado</th><th>Asignación</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)($r['id_tarjeta'] ?? 0) ?></td>
            <td><code><?= e($r['str_coditotarjeta'] ?? '') ?></code></td>
            <td>
              <?php if (!empty($r['id_cliente'])): ?>
                <?= e(trim(($r['str_nombre'] ?? '').' '.($r['str_apellido'] ?? ''))) ?><br>
                <small><?= e($r['str_email'] ?? '') ?></small>
              <?php else: ?>
                <span class="text-muted">Sin asignar</span>
              <?php endif; ?>
            </td>
            <td>$ <?= number_format((float)($r['num_saldo'] ?? 0),2) ?></td>
            <td>
              <span class="badge <?= (($r['enum_estado'] ?? '')==='activa')?'bg-success':'bg-danger' ?>">
                <?= e($r['enum_estado'] ?? '') ?>
              </span>
            </td>
            <td><?= e($r['datetime_fechaasignacion'] ?? '') ?></td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/tarjetas/historial?id=<?= (int)($r['id_tarjeta'] ?? 0) ?>">Historial</a>
              <?php if (($user['role'] ?? '') === 'admin'): ?>
                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/tarjetas/edit?id=<?= (int)($r['id_tarjeta'] ?? 0) ?>">Editar</a>
                <form class="d-inline" method="post" action="<?= BASE_URL ?>/tarjetas/toggle">
                  <?= \CSRF::field() ?>
                  <input type="hidden" name="id" value="<?= (int)($r['id_tarjeta'] ?? 0) ?>">
                  <button class="btn btn-sm btn-outline-warning"><?= (($r['enum_estado'] ?? '')==='activa')?'Bloquear':'Activar' ?></button>
                </form>
                <form class="d-inline" method="post" action="<?= BASE_URL ?>/tarjetas/destroy" onsubmit="return confirm('¿Eliminar tarjeta? Esta acción es permanente si no tiene transacciones.');">
                  <?= \CSRF::field() ?>
                  <input type="hidden" name="id" value="<?= (int)($r['id_tarjeta'] ?? 0) ?>">
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable({pageLength:25});});</script>
