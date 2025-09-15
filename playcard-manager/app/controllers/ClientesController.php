<?php
declare(strict_types=1);
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Cliente.php';

final class ClientesController extends Controller {
  public function index(){
    Auth::requireLogin();
    $q = trim((string)($_GET['q'] ?? ''));
    $clientes = Cliente::search($q);
    return $this->view('clientes/index', ['rows'=>$clientes, 'q'=>$q]);
  }

  public function create(){
    Auth::requireAdmin();
    return $this->view('clientes/create');
  }

  public function store(){
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(400); exit('CSRF'); }
    try {
      Cliente::create($_POST);
      $_SESSION['flash'] = 'Cliente creado correctamente';
      return $this->redirect('/clientes/index');
    } catch(Throwable $e){
      $_SESSION['error'] = $e->getMessage();
      return $this->redirect('/clientes/create');
    }
  }

  public function edit(){
    Auth::requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $c = Cliente::find($id);
    if (!$c) { http_response_code(404); exit('No encontrado'); }
    return $this->view('clientes/edit', ['c'=>$c]);
  }

  public function update(){
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(400); exit('CSRF'); }
    $id = (int)($_POST['id'] ?? 0);
    try {
      Cliente::update($id, $_POST);
      $_SESSION['flash'] = 'Cliente actualizado';
    } catch(Throwable $e){
      $_SESSION['error'] = $e->getMessage();
    }
    return $this->redirect('/clientes/index');
  }

  public function destroy(){
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(400); exit('CSRF'); }
    $id = (int)($_POST['id'] ?? 0);
    Cliente::delete($id);
    $_SESSION['flash'] = 'Cliente eliminado';
    return $this->redirect('/clientes/index');
  }
}
