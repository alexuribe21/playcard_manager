<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header">Iniciar sesión</div>
        <div class="card-body">
          <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
          <form method="post" action="<?= BASE_URL ?>/auth/login">
            <?= \CSRF::field() ?>
            <div class="mb-3"><label class="form-label">Usuario</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" name="password" class="form-control" required></div>
            <button class="btn btn-primary w-100" type="submit">Entrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
