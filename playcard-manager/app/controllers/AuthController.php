<?php
// app/controllers/AuthController.php — corregido para BASE_URL y CSRF

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/Flash.php';

class AuthController extends Controller
{
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf'] ?? null)) {
                Flash::set('Token CSRF inválido', 'danger');
                return $this->redirect('auth/login');
            }

            $user = trim($_POST['username'] ?? '');
            $pass = trim($_POST['password'] ?? '');

            if ($user === 'admin' && $pass === 'admin') {
                $_SESSION['user'] = $user;
                Flash::set('Bienvenido ' . $user, 'success');
                return $this->redirect('dashboard/index');
            }

            Flash::set('Credenciales inválidas', 'danger');
            return $this->redirect('auth/login');
        }

        return $this->view('auth/login');
    }

    public function logout() {
        session_destroy();
        return $this->redirect('auth/login');
    }
}
