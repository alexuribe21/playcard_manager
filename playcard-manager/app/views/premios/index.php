<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Premios</h4>
    <div>
      <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/premios/canjear">Canjear</a>
      <a class="btn btn-primary" href="<?= BASE_URL ?>/premios/create">Nuevo premio</a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped" id="tbl">
      <thead><tr><th>ID</th><th>Nombre</th><th>Puntos requeridos</th><th>Disponible</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id_premio'] ?></td>
          <td><?= htmlspecialchars($r['str_nombrepremio']) ?></td>
          <td><?= (float)$r['num_puntosrequeridos'] ?></td>
          <td><?= $r['bool_disponible']?'Sí':'No' ?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/premios/edit?id=<?= (int)$r['id_premio'] ?>">Editar</a>
            <form class="d-inline" method="post" action="<?= BASE_URL ?>/premios/delete" onsubmit="return confirm('¿Eliminar premio?');">
              <?= \CSRF::field() ?><input type="hidden" name="id" value="<?= (int)$r['id_premio'] ?>"><button class="btn btn-sm btn-outline-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$(function(){$('#tbl').DataTable();});</script>
