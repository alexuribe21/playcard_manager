<?php
// app/views/tarjetas/partials/flash.php
$f = Flash::get();
if ($f): ?>
  <div class="alert alert-<?= htmlspecialchars($f['type']) ?> alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($f['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif;

$errs = $_SESSION['errors'] ?? [];
if ($errs): ?>
  <div class="alert alert-warning"><strong>Revisa los campos:</strong>
    <ul class="mb-0">
      <?php foreach ($errs as $k=>$v): ?>
        <li><code><?= htmlspecialchars($k) ?></code>: <?= htmlspecialchars($v) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php unset($_SESSION['errors']); endif; ?>
