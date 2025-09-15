<?php
// app/views/tarjetas/saldo.php
// Variables esperadas: $rows
?>
<div class="container">
  <h2 class="mb-3">Saldos de tarjetas</h2>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Código</th>
          <th>Estado</th>
          <th class="text-end">Saldo</th>
          <th>Puntos</th>
          <th>Asignación</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id_tarjeta'] ?? $r->id_tarjeta ?? $i+1) ?></td>
            <td><?= htmlspecialchars($r['str_coditotarjeta'] ?? $r->str_coditotarjeta ?? '') ?></td>
            <td>
              <?php $estado = $r['enum_estado'] ?? $r->enum_estado ?? 'activa'; ?>
              <span class="badge bg-<?= $estado==='activa' ? 'success' : 'secondary' ?>"><?= htmlspecialchars(ucfirst($estado)) ?></span>
            </td>
            <td class="text-end">
              $<?= number_format((float)($r['num_saldo'] ?? $r->num_saldo ?? 0), 2, '.', ',') ?>
            </td>
            <td><?= htmlspecialchars($r['num_puntos'] ?? $r->num_puntos ?? 0) ?></td>
            <td><?= htmlspecialchars($r['datetime_fechaasignacion'] ?? $r->datetime_fechaasignacion ?? '') ?></td>
            <td>
              <a class="btn btn-sm btn-outline-primary" href="<?= url('tarjetas/edit?id=' . ($r['id_tarjeta'] ?? $r->id_tarjeta)) ?>">Editar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <a class="btn btn-secondary" href="<?= url('tarjetas/index') ?>">Volver</a>
</div>
