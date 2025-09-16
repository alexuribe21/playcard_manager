<?php
// NO incluir header/footer aquí: el layout padre ya los imprime.
// Helper seguro
if (!function_exists('e')) { function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); } }
$code = $code ?? ($_GET['code'] ?? '');
?>
<div class="container py-4">
  <h4>Consulta de Saldo</h4>

  <form method="get" action="<?= BASE_URL ?>/tarjetas/saldo" class="row g-3 mb-3">
    <div class="col-md-6">
      <input type="text" name="code" class="form-control" placeholder="Código de tarjeta" value="<?= e($code) ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Consultar</button>
    </div>
  </form>

  <?php if (isset($t)): ?>
    <?php if ($t): ?>
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><b>ID:</b> <?= (int)$t['id_tarjeta'] ?></p>
              <p><b>Código:</b> <code><?= e($t['str_coditotarjeta']) ?></code></p>
              <p><b>Estado:</b> <span class="badge <?= ($t['enum_estado']==='activa')?'bg-success':'bg-danger' ?>"><?= e($t['enum_estado']) ?></span></p>
            </div>
            <div class="col-md-6">
              <p><b>Saldo actual:</b> $ <?= number_format((float)$t['num_saldo'],2) ?></p>
              <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/tarjetas/historial?id=<?= (int)$t['id_tarjeta'] ?>">Ver historial</a>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">Tarjeta no encontrada o código inválido.</div>
    <?php endif; ?>
  <?php endif; ?>
</div>
