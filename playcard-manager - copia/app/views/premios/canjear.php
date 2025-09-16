<div class="container py-4">
  <h4>Canjear premio</h4>
  <form method="post" action="<?= BASE_URL ?>/premios/canjear">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Código de tarjeta</label><input name="code" class="form-control" required></div>
      <div class="col-md-6">
        <label class="form-label">Premio</label>
        <select name="id_premio" class="form-select">
          <?php foreach ($rows as $r): if(!$r['bool_disponible']) continue; ?>
            <option value="<?= (int)$r['id_premio'] ?>"><?= htmlspecialchars($r['str_nombrepremio']) ?> (<?= (float)$r['num_puntosrequeridos'] ?> pts)</option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Canjear</button></div>
  </form>
</div>
