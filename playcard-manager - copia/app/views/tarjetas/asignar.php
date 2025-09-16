<div class="container py-4">
  <h4>Asignar tarjeta a cliente</h4>
  <form method="post" action="<?= BASE_URL ?>/tarjetas/asignarPost">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Tarjeta (ID)</label>
        <select name="id_tarjeta" class="form-select">
          <?php foreach ($tarjetas as $t): ?>
            <option value="<?= (int)$t['id_tarjeta'] ?>"><?= (int)$t['id_tarjeta'] ?> — <?= htmlspecialchars($t['str_coditotarjeta']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Cliente</label>
        <select name="id_cliente" class="form-select">
          <?php foreach ($clientes as $c): ?>
            <option value="<?= (int)$c['id_cliente'] ?>"><?= (int)$c['id_cliente'] ?> — <?= htmlspecialchars($c['str_nombre'].' '.$c['str_apellido']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Asignar</button> <a class="btn btn-secondary" href="<?= BASE_URL ?>/tarjetas/index">Cancelar</a></div>
  </form>
</div>
