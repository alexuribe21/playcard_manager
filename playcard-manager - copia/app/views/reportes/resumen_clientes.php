<div class="container py-4">
  <h4>Resumen por cliente</h4>
  <div class="mb-2">
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/index">Ver detalle</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/cierreDiario">Cierre diario</a>
  </div>
  <form class="row gy-2 gx-3 align-items-end mb-3" method="get" action="<?= BASE_URL ?>/reportes/resumenClientes">
    <div class="col-md-3">
      <label class="form-label">Desde</label>
      <input type="date" name="desde" value="<?= htmlspecialchars($desde) ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Hasta</label>
      <input type="date" name="hasta" value="<?= htmlspecialchars($hasta) ?>" class="form-control">
    </div>
    <div class="col-md-6 mt-2">
      <button class="btn btn-primary me-2">Aplicar</button>
      <a class="btn btn-success" href="<?= BASE_URL ?>/reportes/exportResumenClientesCsv?desde=<?= urlencode($desde) ?>&hasta=<?= urlencode($hasta) ?>">Exportar CSV</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Cliente</th><th>Cargas</th><th>Consumos</th><th>Premios</th><th>Movimientos</th><th>Neto (Cargas - Consumos - Premios)</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): $neto = (float)$r['total_cargas'] - (float)$r['total_consumos'] - (float)$r['total_premios']; ?>
        <tr>
          <td><?= (int)$r['id_cliente'] ?></td>
          <td><?= htmlspecialchars($r['cliente']) ?></td>
          <td>$ <?= number_format((float)$r['total_cargas'],2) ?></td>
          <td>$ <?= number_format((float)$r['total_consumos'],2) ?></td>
          <td>$ <?= number_format((float)$r['total_premios'],2) ?></td>
          <td><?= (int)$r['movimientos'] ?></td>
          <td><strong>$ <?= number_format($neto,2) ?></strong></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="alert alert-info">
    <b>Totales globales</b> — Cargas: $<?= number_format($acc['cargas'],2) ?> · Consumos: $<?= number_format($acc['consumos'],2) ?> · Premios: $<?= number_format($acc['premios'],2) ?>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable({pageLength:25});});</script>
