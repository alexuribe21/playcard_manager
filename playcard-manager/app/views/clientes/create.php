<div class="container py-4">
  <h4>Nuevo cliente</h4>
  <form method="post" action="<?= BASE_URL ?>/clientes/store" class="row g-3">
    <?= \CSRF::field() ?>
    <div class="col-md-4">
      <label class="form-label">Nombre</label>
      <input type="text" name="str_nombre" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Apellido</label>
      <input type="text" name="str_apellido" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Cédula</label>
      <input type="text" name="str_cedula" class="form-control" maxlength="20" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Teléfono</label>
      <input type="text" name="str_telefono" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="str_email" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Dirección (opcional)</label>
      <input type="text" name="str_direccion" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Código secreto (6 dígitos)</label>
      <input type="text" name="str_codigosecreto" class="form-control" pattern="^\d{6}$" required>
    </div>
    <div class="col-md-8 d-flex align-items-end gap-4">
      <div class="form-check">
        <input type="hidden" name="bool_anonimo" value="0">
        <input class="form-check-input" type="checkbox" name="bool_anonimo" value="1" id="anon">
        <label class="form-check-label" for="anon">Anónimo</label>
      </div>
      <div class="form-check">
        <input type="hidden" name="bool_activo" value="1">
        <input class="form-check-input" type="checkbox" name="bool_activo" value="1" id="activo" checked>
        <label class="form-check-label" for="activo">Activo</label>
      </div>
    </div>
    <div class="col-12 mt-2">
      <button class="btn btn-primary">Crear</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/clientes/index">Volver</a>
    </div>
  </form>
</div>
