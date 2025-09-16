<?php /** @var array $rows */ ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Clientes</h4>
    <a class="btn btn-primary" href="<?= BASE_URL ?>/clientes/create">Nuevo cliente</a>
  </div>

  <form class="row g-2 mb-3" method="get" action="<?= BASE_URL ?>/clientes/index">
    <div class="col-sm-6 col-md-4">
      <input type="text" name="q" value="<?= htmlspecialchars((string)($q ?? '')) ?>" class="form-control" placeholder="Buscar por nombre, cédula, email, dirección...">
    </div>
    <div class="col-auto"><button class="btn btn-outline-secondary">Buscar</button></div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Cédula</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Dirección</th>
          <th>Activo</th>
          <th style="width:120px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= (int)$r['id_cliente'] ?></td>
            <td><?= htmlspecialchars((string)$r['str_nombre']) ?></td>
            <td><?= htmlspecialchars((string)$r['str_apellido']) ?></td>
            <td><?= htmlspecialchars((string)$r['str_cedula']) ?></td>
            <td><?= htmlspecialchars((string)$r['str_telefono']) ?></td>
            <td><?= htmlspecialchars((string)$r['str_email']) ?></td>
            <td><?= htmlspecialchars((string)($r['str_direccion'] ?? '')) ?></td>
            <td><?= !empty($r['bool_activo']) ? 'Sí' : 'No' ?></td>
            <td class="d-flex gap-1">
              <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/clientes/edit?id=<?= (int)$r['id_cliente'] ?>">Editar</a>
              <form method="post" action="<?= BASE_URL ?>/clientes/destroy" onsubmit="return confirm('¿Eliminar cliente?');">
                <?= \CSRF::field() ?>
                <input type="hidden" name="id" value="<?= (int)$r['id_cliente'] ?>">
                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr><td colspan="9" class="text-center text-muted">Sin resultados</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
