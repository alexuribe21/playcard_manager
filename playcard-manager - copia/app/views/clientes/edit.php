<div class="container py-4">
  <h4>Editar cliente #<?= (int)($c['id_cliente'] ?? 0) ?></h4>
  <form method="post" action="<?= BASE_URL ?>/clientes/update" class="row g-3">
    <?= \CSRF::field() ?>
    <input type="hidden" name="id" value="<?= (int)($c['id_cliente'] ?? 0) ?>">
    <div class="col-md-4">
      <label class="form-label">Nombre</label>
      <input type="text" name="str_nombre" class="form-control" value="<?= htmlspecialchars((string)($c['str_nombre'] ?? '')) ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Apellido</label>
      <input type="text" name="str_apellido" class="form-control" value="<?= htmlspecialchars((string)($c['str_apellido'] ?? '')) ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Cédula</label>
      <input type="text" name="str_cedula" class="form-control" maxlength="20" value="<?= htmlspecialchars((string)($c['str_cedula'] ?? '')) ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Teléfono</label>
      <input type="text" name="str_telefono" class="form-control" value="<?= htmlspecialchars((string)($c['str_telefono'] ?? '')) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="str_email" class="form-control" value="<?= htmlspecialchars((string)($c['str_email'] ?? '')) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Dirección (opcional)</label>
      <input type="text" name="str_direccion" class="form-control" value="<?= htmlspecialchars((string)($c['str_direccion'] ?? '')) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Código secreto (6 dígitos)</label>
      <input type="text" name="str_codigosecreto" class="form-control" pattern="^\d{6}$" value="<?= htmlspecialchars((string)($c['str_codigosecreto'] ?? '')) ?>">
    </div>
    <div class="col-md-8 d-flex align-items-end gap-4">
      <div class="form-check">
        <input type="hidden" name="bool_anonimo" value="0">
        <input class="form-check-input" type="checkbox" name="bool_anonimo" value="1" id="anon" <?= !empty($c['bool_anonimo']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="anon">Anónimo</label>
      </div>
      <div class="form-check">
        <input type="hidden" name="bool_activo" value="0">
        <input class="form-check-input" type="checkbox" name="bool_activo" value="1" id="activo" <?= !empty($c['bool_activo']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="activo">Activo</label>
      </div>
    </div>
    <div class="col-12 mt-2">
      <button class="btn btn-primary">Guardar</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/clientes/index">Volver</a>
    </div>
  </form>
</div>
