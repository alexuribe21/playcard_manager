<div class="container py-4">
  <h4>Nuevo premio</h4>
  <form method="post" action="<?= BASE_URL ?>/premios/store">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Nombre</label><input name="nombre" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Puntos requeridos</label><input name="puntos" type="number" min="1" step="0.01" class="form-control" required></div>
      <div class="col-md-3 form-check mt-4"><input class="form-check-input" type="checkbox" name="disp" id="disp" checked><label for="disp" class="form-check-label">Disponible</label></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Guardar</button> <a class="btn btn-secondary" href="<?= BASE_URL ?>/premios/index">Cancelar</a></div>
  </form>
</div>
