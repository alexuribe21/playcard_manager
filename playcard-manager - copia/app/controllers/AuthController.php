<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Usuario.php';
class AuthController extends Controller {
  public function login() {
    if ($_SERVER['REQUEST_METHOD']==='POST') {
      if (!CSRF::check($_POST['csrf']??null)) { http_response_code(400); die('CSRF inválido'); }
      $user=trim($_POST['username']??''); $pass=(string)($_POST['password']??'');
      $row=Usuario::findByUsername($user);
      if ($row && password_verify($pass,$row['str_password'])) { Auth::login($row); $this->redirect('/dashboard/index'); }
      $this->view('auth/login',['error'=>'Usuario o contraseña incorrectos']); return;
    }
    $this->view('auth/login');
  }
  public function logout(){ Auth::logout(); $this->redirect('/auth/login'); }
}
