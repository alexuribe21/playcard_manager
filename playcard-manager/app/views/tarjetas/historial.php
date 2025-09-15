<div class="container py-4">
  <h4>Historial de tarjeta #<?= (int)$t['id_tarjeta'] ?> — <code><?= htmlspecialchars($t['str_coditotarjeta']) ?></code></h4>
  <p>Saldo actual: <b>$ <?= number_format((float)$t['num_saldo'],2) ?></b> — Estado: <span class="badge <?= $t['enum_estado']==='activa'?'bg-success':'bg-danger' ?>"><?= htmlspecialchars($t['enum_estado']) ?></span></p>
  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Tipo</th><th>Juego</th><th>Monto</th><th>Saldo Anterior</th><th>Saldo Nuevo</th><th>Fecha</th><th>Descripción</th></tr></thead>
      <tbody>
        <?php foreach ($movs as $m): ?>
          <tr>
            <td><?= (int)$m['id_transaccion'] ?></td>
            <td><?= htmlspecialchars($m['enum_tipotransaccion']) ?></td>
            <td><?= htmlspecialchars($m['str_nombrejuego'] ?? '—') ?></td>
            <td>$ <?= number_format((float)$m['num_monto'],2) ?></td>
            <td>$ <?= number_format((float)$m['num_saldoanterior'],2) ?></td>
            <td>$ <?= number_format((float)$m['num_saldonuevo'],2) ?></td>
            <td><?= htmlspecialchars($m['datetime_fechatransaccion']) ?></td>
            <td><?= htmlspecialchars($m['str_descripcion'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <a class="btn btn-secondary" href="<?= BASE_URL ?>/tarjetas/index">Volver</a>
</div>
<script>$(function(){$('#tbl').DataTable({pageLength:25});});</script>
