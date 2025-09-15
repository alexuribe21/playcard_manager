<?php require __DIR__.'/partials/flash.php'; ?>
<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0">Tarjetas</h1>
  <a class="btn btn-primary" href="<?= $base ?>/tarjetas/create">Nueva tarjeta</a>
</div>

<form class="row g-2 mb-3" method="get" action="<?= $base ?>/tarjetas/index">
  <div class="col-md-8">
    <input type="text" name="q" class="form-control" placeholder="Buscar por código, nombre, cédula o email" value="<?= htmlspecialchars($q ?? '') ?>">
  </div>
  <div class="col-md-4">
    <button class="btn btn-outline-secondary w-100">Buscar</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>#</th>
        <th>Código</th>
        <th>Cliente</th>
        <th>Estado</th>
        <th>Saldo</th>
        <th>Puntos</th>
        <th>Asignación</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="8" class="text-center text-muted py-4">No hay tarjetas.</td></tr>
    <?php else: foreach ($rows as $t): ?>
      <tr>
        <td><?= (int)$t['id_tarjeta'] ?></td>
        <td><?= htmlspecialchars($t['str_coditotarjeta']) ?></td>
        <td>
          <?php if ($t['id_cliente']): ?>
            <?= htmlspecialchars(($t['str_nombre'] ?? '').' '.($t['str_apellido'] ?? '').' ('.($t['str_cedula'] ?? '').')') ?>
          <?php else: ?>
            <span class="text-muted">Sin asignar</span>
          <?php endif; ?>
        </td>
        <td><span class="badge bg-<?= $t['enum_estado']==='activa'?'success':'danger' ?>"><?= htmlspecialchars(ucfirst($t['enum_estado'])) ?></span></td>
        <td>$<?= number_format((float)$t['num_saldo'], 2) ?></td>
        <td><?= (int)($t['num_puntos'] ?? 0) ?></td>
        <td><?= htmlspecialchars($t['datetime_fechaasignacion'] ?? '') ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-secondary" href="<?= $base ?>/tarjetas/edit?id=<?= (int)$t['id_tarjeta'] ?>">Editar</a>
          <form action="<?= $base ?>/tarjetas/destroy?id=<?= (int)$t['id_tarjeta'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar tarjeta?');">
            <?= CSRF::field() ?>
            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
