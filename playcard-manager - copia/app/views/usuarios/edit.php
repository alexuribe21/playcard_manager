<div class="container py-4">
  <h4>Editar usuario</h4>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" action="<?= BASE_URL ?>/usuarios/update">
    <?= \CSRF::field() ?>
    <input type="hidden" name="id" value="<?= (int)$u['id_usuario'] ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Usuario</label>
        <input type="text" name="username" class="form-control" required pattern="[a-zA-Z0-9_.-]{3,50}" value="<?= htmlspecialchars($u['str_nombreusuario']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Rol</label>
        <select name="role" class="form-select">
          <option value="normal" <?= $u['enum_tipousuario']==='normal'?'selected':'' ?>>Normal</option>
          <option value="admin"  <?= $u['enum_tipousuario']==='admin'?'selected':'' ?>>Admin</option>
        </select>
      </div>
      <div class="col-md-4 form-check mt-4">
        <input class="form-check-input" type="checkbox" name="active" id="active" <?= !empty($u['bool_activo'])?'checked':'' ?>>
        <label for="active" class="form-check-label">Activo</label>
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">Guardar cambios</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/usuarios/index">Cancelar</a>
    </div>
  </form>
</div>
