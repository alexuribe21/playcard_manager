<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Usuario.php';

class PerfilController extends Controller {

  public function index() {
    Auth::requireLogin();
    $user = Auth::user();
    $u = Usuario::findById((int)$user['id']);
    $this->view('perfil/index', ['u'=>$u]);
  }

  public function changePassword() {
    Auth::requireLogin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $user = Auth::user();
    $id = (int)$user['id'];
    $actual = (string)($_POST['actual'] ?? '');
    $nueva = (string)($_POST['nueva'] ?? '');
    $repetir = (string)($_POST['repetir'] ?? '');

    if ($nueva === '' || strlen($nueva) < 6) die('La nueva contraseña debe tener al menos 6 caracteres');
    if ($nueva !== $repetir) die('Las contraseñas nuevas no coinciden');
    if (!Usuario::verifyPassword($id, $actual)) die('La contraseña actual no es correcta');

    Usuario::resetPassword($id, $nueva);
    $this->redirect('/perfil/index');
  }
}
