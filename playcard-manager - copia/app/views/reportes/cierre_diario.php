<div class="container py-4">
  <h4>Cierre diario</h4>
  <div class="mb-2">
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/index">Ver detalle</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/resumenClientes">Resumen por cliente</a>
  </div>
  <form class="row gy-2 gx-3 align-items-end mb-3" method="get" action="<?= BASE_URL ?>/reportes/cierreDiario">
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
      <a class="btn btn-success" href="<?= BASE_URL ?>/reportes/exportCierreDiarioCsv?desde=<?= urlencode($desde) ?>&hasta=<?= urlencode($hasta) ?>">Exportar CSV</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead>
        <tr>
          <th>Fecha</th><th>Cargas (# / $)</th><th>Consumos (# / $)</th><th>Premios (# / $)</th><th>Total Día ($)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dias as $fecha => $d): $totalDia = $d['carga']['total'] - $d['consumo']['total'] - $d['premio']['total']; ?>
          <tr>
            <td><?= htmlspecialchars($fecha) ?></td>
            <td><?= (int)$d['carga']['conteo'] ?> / $ <?= number_format((float)$d['carga']['total'],2) ?></td>
            <td><?= (int)$d['consumo']['conteo'] ?> / $ <?= number_format((float)$d['consumo']['total'],2) ?></td>
            <td><?= (int)$d['premio']['conteo'] ?> / $ <?= number_format((float)$d['premio']['total'],2) ?></td>
            <td><strong>$ <?= number_format($totalDia,2) ?></strong></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable({pageLength:25});});</script>
