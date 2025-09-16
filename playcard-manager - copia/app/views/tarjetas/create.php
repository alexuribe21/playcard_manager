<div class="container py-4">
  <h4>Nueva tarjeta</h4>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" action="<?= BASE_URL ?>/tarjetas/store">
    <?= \CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Código de tarjeta</label>
        <input type="text" name="str_coditotarjeta" class="form-control" required
               placeholder="00000000000000000000&55550000000001&01*2"
               pattern="^\d{20}&\d{14}&\d{2}\*[1-9]$">
        <div class="form-text">Formato exacto: 20 dígitos & 14 dígitos & 2 dígitos *1-9.</div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Saldo inicial</label>
        <input type="number" name="num_saldo" class="form-control" min="0" step="0.01" value="0.00">
      </div>
      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="enum_estado" class="form-select">
          <option value="activa">activa</option>
          <option value="bloqueada">bloqueada</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Asignar a id_cliente (opcional)</label>
        <input type="number" name="id_cliente" class="form-control" min="1" placeholder="ID del cliente">
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">Guardar</button>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>/tarjetas/index">Cancelar</a>
    </div>
  </form>
</div>
