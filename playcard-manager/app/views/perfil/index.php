<div class="container py-4">
  <h4>Mi perfil</h4>
  <div class="card">
    <div class="card-body">
      <p><b>Usuario:</b> <?= htmlspecialchars($u['str_nombreusuario']) ?></p>
      <p><b>Rol:</b> <?= htmlspecialchars($u['enum_tipousuario']) ?></p>
      <p><b>Activo:</b> <?= !empty($u['bool_activo']) ? 'Sí' : 'No' ?></p>
    </div>
  </div>

  <h5 class="mt-4">Cambiar contraseña</h5>
  <form method="post" action="<?= BASE_URL ?>/perfil/changePassword" class="row g-3">
    <?= \CSRF::field() ?>
    <div class="col-md-4">
      <label class="form-label">Contraseña actual</label>
      <input type="password" name="actual" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Nueva contraseña</label>
      <input type="password" name="nueva" class="form-control" required minlength="6">
    </div>
    <div class="col-md-4">
      <label class="form-label">Repite nueva contraseña</label>
      <input type="password" name="repetir" class="form-control" required minlength="6">
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Actualizar contraseña</button>
    </div>
  </form>
</div>
