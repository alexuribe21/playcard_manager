<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Usuarios del sistema</h4>
    <a class="btn btn-primary" href="<?= BASE_URL ?>/usuarios/create">Nuevo usuario</a>
  </div>
  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Usuario</th><th>Rol</th><th>Creación</th><th>Activo</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id_usuario'] ?></td>
            <td><?= htmlspecialchars($r['str_nombreusuario']) ?></td>
            <td><?= htmlspecialchars($r['enum_tipousuario']) ?></td>
            <td><?= htmlspecialchars($r['datetime_fechacreacion']) ?></td>
            <td><?= !empty($r['bool_activo']) ? 'Sí' : 'No' ?></td>
            <td>
              <?php if (!empty($r['bool_activo'])): ?>
                <form class="d-inline" method="post" action="<?= BASE_URL ?>/usuarios/desactivar" onsubmit="return confirm('¿Desactivar usuario?');">
                  <?= \CSRF::field() ?>
                  <input type="hidden" name="id" value="<?= (int)$r['id_usuario'] ?>">
                  <button class="btn btn-sm btn-outline-warning">Desactivar</button>
                </form>
              <?php else: ?>
                <form class="d-inline" method="post" action="<?= BASE_URL ?>/usuarios/activar" onsubmit="return confirm('¿Activar usuario?');">
                  <?= \CSRF::field() ?>
                  <input type="hidden" name="id" value="<?= (int)$r['id_usuario'] ?>">
                  <button class="btn btn-sm btn-outline-success">Activar</button>
                </form>
              <?php endif; ?>
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalPass<?= (int)$r['id_usuario'] ?>">Reset pass</button>
              <!-- Modal reset password -->
              <div class="modal fade" id="modalPass<?= (int)$r['id_usuario'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Resetear contraseña</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="post" action="<?= BASE_URL ?>/usuarios/resetPass">
                      <div class="modal-body">
                        <?= \CSRF::field() ?>
                        <input type="hidden" name="id" value="<?= (int)$r['id_usuario'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Nueva contraseña</label>
                          <input type="password" name="newpass" class="form-control" required minlength="6">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary">Guardar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable();});</script>
