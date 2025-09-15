<div class="container py-4">
  <h4>Editar premio</h4>
  <form method="post" action="<?= BASE_URL ?>/premios/update">
    <?= \CSRF::field() ?>
    <input type="hidden" name="id" value="<?= (int)$p['id_premio'] ?>">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" value="<?= htmlspecialchars($p['str_nombrepremio']) ?>" required></div>
      <div class="col-md-3"><label class="form-label">Puntos requeridos</label><input name="puntos" type="number" min="1" step="0.01" class="form-control" value="<?= (float)$p['num_puntosrequeridos'] ?>" required></div>
      <div class="col-md-3 form-check mt-4"><input class="form-check-input" type="checkbox" name="disp" id="disp" <?= $p['bool_disponible']?'checked':'' ?>><label for="disp" class="form-check-label">Disponible</label></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Actualizar</button> <a class="btn btn-secondary" href="<?= BASE_URL ?>/premios/index">Cancelar</a></div>
  </form>
</div>
