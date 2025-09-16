<div class="container py-4">
  <h4>Reporte detallado de transacciones</h4>
  <div class="mb-2">
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/resumenClientes">Resumen por cliente</a>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_URL ?>/reportes/cierreDiario">Cierre diario</a>
  </div>
  <form class="row gy-2 gx-3 align-items-end mb-3" method="get" action="<?= BASE_URL ?>/reportes/index">
    <div class="col-md-2">
      <label class="form-label">Desde</label>
      <input type="date" name="desde" value="<?= htmlspecialchars($f['desde']) ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">Hasta</label>
      <input type="date" name="hasta" value="<?= htmlspecialchars($f['hasta']) ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">Tipo</label>
      <select name="tipo" class="form-select">
        <option value="">(Todos)</option>
        <option value="carga"   <?= $f['tipo']==='carga'?'selected':'' ?>>Carga</option>
        <option value="consumo" <?= $f['tipo']==='consumo'?'selected':'' ?>>Consumo</option>
        <option value="premio"  <?= $f['tipo']==='premio'?'selected':'' ?>>Premio</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Juego</label>
      <select name="id_juego" class="form-select">
        <option value="0">(Todos)</option>
        <?php foreach ($juegos as $j): ?>
          <option value="<?= (int)$j['id_juego'] ?>" <?= $f['juego']==(int)$j['id_juego']?'selected':'' ?>>
            <?= htmlspecialchars($j['str_nombrejuego']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Código de tarjeta</label>
      <input type="text" name="code" value="<?= htmlspecialchars($f['code']) ?>" class="form-control" placeholder="pegar lectura de banda">
    </div>
    <div class="col-12 mt-2">
      <button class="btn btn-primary me-2">Filtrar</button>
      <a class="btn btn-outline-secondary me-2" href="<?= BASE_URL ?>/reportes/index">Limpiar</a>
      <a class="btn btn-success me-2" href="<?= BASE_URL ?>/reportes/exportCsv?<?= http_build_query($f) ?>">Exportar CSV</a>
      <a class="btn btn-danger" href="<?= BASE_URL ?>/reportes/exportPdf?<?= http_build_query($f) ?>">Exportar PDF</a>
    </div>
  </form>

  <div class="alert alert-info">
    <strong>Totales</strong> — Carga: <b>$<?= number_format($totales['carga'],2) ?></b> ·
    Consumo: <b>$<?= number_format($totales['consumo'],2) ?></b> ·
    Canje premios: <b>$<?= number_format($totales['premio'],2) ?></b> ·
    <u>Total</u>: <b>$<?= number_format($totales['total'],2) ?></b>
  </div>

  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Tipo</th><th>Tarjeta</th><th>Juego</th><th>Monto</th><th>Fecha</th><th>Descripción</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id_transaccion'] ?></td>
            <td><?= htmlspecialchars($r['enum_tipotransaccion']) ?></td>
            <td><?= htmlspecialchars($r['str_coditotarjeta']) ?></td>
            <td><?= htmlspecialchars($r['str_nombrejuego'] ?? '—') ?></td>
            <td>$ <?= number_format((float)$r['num_monto'],2) ?></td>
            <td><?= htmlspecialchars($r['datetime_fechatransaccion']) ?></td>
            <td><?= htmlspecialchars($r['str_descripcion'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable({pageLength:25});});</script>
