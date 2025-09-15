<?php
require_once __DIR__ . '/CSRF.php';

class Controller {
  protected function view(string $view, array $data = []) {
    extract($data);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layouts/header.php';
    if (file_exists($viewFile)) require $viewFile; else echo "<div class='container py-4'><div class='alert alert-danger'>Vista no encontrada: {$view}</div></div>";
    require __DIR__ . '/../views/layouts/footer.php';
  }
  protected function redirect(string $path) { header('Location: ' . BASE_URL . $path); exit; }
}
