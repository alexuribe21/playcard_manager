<?php
// app/views/tarjetas/index.php — Fix: flash keys validados
require_once __DIR__ . '/../partials/basepath.php';
?>
<div class="container">
  <h2 class="mb-3">Tarjetas</h2>

  <?php if ($flash = Flash::get()): ?>
    <?php $type = $flash['type'] ?? 'info'; ?>
    <?php $msg  = $flash['message'] ?? ''; ?>
    <div class="alert alert-<?= htmlspecialchars($type) ?>">
      <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <form class="mb-3" method="get" action="<?= url('tarjetas/index') ?>">
    <input type="text" name="q" class="form-control" placeholder="Buscar por código, nombre, cédula o email" value="<?= htmlspecialchars($q ?? '') ?>">
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Código</th>
          <th>Cliente</th>
          <th>Estado</th>
          <th>Saldo</th>
          <th>Puntos</th>
          <th>PIN</th>
          <th>Asignación</th>
          <th>Vencimiento</th>
          <th>Notas</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id_tarjeta']) ?></td>
            <td><?= htmlspecialchars($r['str_coditotarjeta']) ?></td>
            <td><?= $r['id_cliente'] ? htmlspecialchars($r['id_cliente']) : 'Sin asignar' ?></td>
            <td>
              <?php if ($r['enum_estado'] === 'activa'): ?>
                <span class="badge bg-success">Activa</span>
              <?php else: ?>
                <span class="badge bg-danger">Bloqueada</span>
              <?php endif; ?>
            </td>
            <td>$<?= number_format((float)($r['num_saldo'] ?? 0), 2) ?></td>
            <td><?= (int)($r['num_puntos'] ?? 0) ?></td>
            <td><?= htmlspecialchars($r['str_pin'] ?? '—') ?></td>
            <td><?= $r['datetime_fechaasignacion'] ?? '—' ?></td>
            <td><?= $r['date_fechavencimiento'] ?? '—' ?></td>
            <td><?= $r['txt_notas'] ? htmlspecialchars(substr($r['txt_notas'],0,50)) : '—' ?></td>
            <td>
              <a href="<?= url('tarjetas/edit?id=' . $r['id_tarjeta']) ?>" class="btn btn-sm btn-primary">Editar</a>
              <form method="post" action="<?= url('tarjetas/destroy?id=' . $r['id_tarjeta']) ?>" style="display:inline" onsubmit="return confirm('¿Eliminar esta tarjeta?');">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(CSRF::token()) ?>"/>
                <button class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <a href="<?= url('tarjetas/create') ?>" class="btn btn-success">Nueva tarjeta</a>
</div>
