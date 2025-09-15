<div class="container py-4">
  <h4>Cobro de juego</h4>
  <form method="post" action="<?= BASE_URL ?>/tarjetas/cobrar">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Código de tarjeta</label><input name="code" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Código secreto (6 dígitos)</label><input name="secret" class="form-control" required pattern="\d{6}"></div>
      <div class="col-md-3">
        <label class="form-label">Juego</label>
        <select name="id_juego" class="form-select">
          <?php foreach ($juegos as $j): ?><option value="<?= (int)$j['id_juego'] ?>"><?= htmlspecialchars($j['str_nombrejuego']) ?> ($<?= number_format((float)$j['num_costoporuso'],2) ?>)</option><?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Cobrar</button></div>
  </form>
</div>
