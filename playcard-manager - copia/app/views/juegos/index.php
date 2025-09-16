<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Juegos</h4>
    <a class="btn btn-primary" href="<?= BASE_URL ?>/juegos/create">Nuevo juego</a>
  </div>
  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Nombre</th><th>Costo</th><th>Activo</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id_juego'] ?></td>
          <td><?= htmlspecialchars($r['str_nombrejuego']) ?></td>
          <td>$ <?= number_format((float)$r['num_costoporuso'],2) ?></td>
          <td><?= $r['bool_activo']?'Sí':'No' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/juegos/edit?id=<?= (int)$r['id_juego'] ?>">Editar</a>
            <form class="d-inline" method="post" action="<?= BASE_URL ?>/juegos/delete" onsubmit="return confirm('¿Eliminar juego?');">
              <?= \CSRF::field() ?><input type="hidden" name="id" value="<?= (int)$r['id_juego'] ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable();});</script>
