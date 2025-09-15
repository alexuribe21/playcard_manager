<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController extends Controller {

  public function index() {
    Auth::requireAdmin();
    $rows = Usuario::all();
    $this->view('usuarios/index', ['rows'=>$rows]);
  }

  public function create() {
    Auth::requireAdmin();
    $this->view('usuarios/create');
  }

  public function store() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(400); die('CSRF inválido'); }
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $password2 = (string)($_POST['password2'] ?? '');
    $role = $_POST['role'] ?? 'normal';
    $active = isset($_POST['active']);

    if ($username === '' || !preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) die('Usuario inválido (3-50, letras/números/_ . -)');
    if ($password === '' || strlen($password) < 6) die('La contraseña debe tener al menos 6 caracteres');
    if ($password !== $password2) die('Las contraseñas no coinciden');
    if (!in_array($role, ['admin','normal'], true)) die('Rol inválido');

    try {
      Usuario::create($username, $password, $role, $active);
      $this->redirect('/usuarios/index');
    } catch (RuntimeException $e) {
      $this->view('usuarios/create', ['error'=>$e->getMessage(), 'old'=>['username'=>$username,'role'=>$role,'active'=>$active]]);
    }
  }

  public function edit() {
    Auth::requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $u = Usuario::findById($id);
    if (!$u) { http_response_code(404); die('Usuario no encontrado'); }
    $this->view('usuarios/edit', ['u'=>$u]);
  }

  public function update() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? 'normal';
    $active = isset($_POST['active']);
    if ($username === '' || !preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) die('Usuario inválido');
    if (!in_array($role, ['admin','normal'], true)) die('Rol inválido');
    try {
      Usuario::updateUser($id, $username, $role, $active);
      $this->redirect('/usuarios/index');
    } catch (RuntimeException $e) {
      $u = Usuario::findById($id);
      $this->view('usuarios/edit', ['u'=>$u, 'error'=>$e->getMessage()]);
    }
  }

  public function activar() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    Usuario::setActive($id, true);
    $this->redirect('/usuarios/index');
  }

  public function desactivar() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    Usuario::setActive($id, false);
    $this->redirect('/usuarios/index');
  }

  public function resetPass() {
    Auth::requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); die('Método no permitido'); }
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    $newpass = (string)($_POST['newpass'] ?? '');
    if ($newpass === '' || strlen($newpass) < 6) die('La nueva contraseña debe tener al menos 6 caracteres');
    Usuario::resetPassword($id, $newpass);
    $this->redirect('/usuarios/index');
  }
}
