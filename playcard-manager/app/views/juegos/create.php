<div class="container py-4">
  <h4>Nuevo juego</h4>
  <form method="post" action="<?= BASE_URL ?>/juegos/store">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Costo por uso</label><input name="costo" type="number" step="0.01" min="0.01" class="form-control" required></div>
      <div class="col-md-3 form-check mt-4"><input class="form-check-input" type="checkbox" name="activo" id="activo" checked><label for="activo" class="form-check-label">Activo</label></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Guardar</button> <a class="btn btn-secondary" href="<?= BASE_URL ?>/juegos/index">Cancelar</a></div>
  </form>
</div>
