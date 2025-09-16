<div class="container py-4">
  <h4>Crear usuario</h4>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" action="<?= BASE_URL ?>/usuarios/store">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Usuario</label>
        <input type="text" name="username" class="form-control" required pattern="[a-zA-Z0-9_.-]{3,50}" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
        <div class="form-text">3-50 caracteres: letras, números, _ . -</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required minlength="6">
      </div>
      <div class="col-md-4">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="password2" class="form-control" required minlength="6">
      </div>
      <div class="col-md-4">
        <label class="form-label">Rol</label>
        <select name="role" class="form-select">
          <option value="normal" <?= (isset($old['role']) && $old['role']==='normal')?'selected':'' ?>>Normal</option>
          <option value="admin"  <?= (isset($old['role']) && $old['role']==='admin')?'selected':'' ?>>Admin</option>
        </select>
      </div>
      <div class="col-md-4 form-check mt-4">
        <input class="form-check-input" type="checkbox" name="active" id="active" <?= !empty($old['active'])?'checked':'' ?> checked>
        <label for="active" class="form-check-label">Activo</label>
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">Crear</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/usuarios/index">Cancelar</a>
    </div>
  </form>
</div>
