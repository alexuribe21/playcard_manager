<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Premio.php';
require_once __DIR__ . '/../models/Tarjeta.php';
require_once __DIR__ . '/../models/Cliente.php';
class PremiosController extends Controller {
  public function index(){ Auth::requireLogin(); $rows=Premio::all(); $this->view('premios/index',['rows'=>$rows]); }
  public function create(){ Auth::requireAdmin(); $this->view('premios/create'); }
  public function store(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Premio::create(['nombre'=>$_POST['nombre'],'puntos'=>(float)$_POST['puntos'],'disp'=>isset($_POST['disp'])]); $this->redirect('/premios/index'); }
  public function edit(){ Auth::requireAdmin(); $id=(int)$_GET['id']; $p=Premio::find($id); $this->view('premios/edit',['p'=>$p]); }
  public function update(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Premio::update((int)$_POST['id'],['nombre'=>$_POST['nombre'],'puntos'=>(float)$_POST['puntos'],'disp'=>isset($_POST['disp'])]); $this->redirect('/premios/index'); }
  public function delete(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Premio::delete((int)$_POST['id']); $this->redirect('/premios/index'); }
  public function canjear(){ Auth::requireLogin(); if($_SERVER['REQUEST_METHOD']!=='POST'){ $rows=Premio::all(); $this->view('premios/canjear',['rows'=>$rows]); return; }
    if(!CSRF::check($_POST['csrf']??null)) die('CSRF');
    $code=trim($_POST['code']); $id_premio=(int)$_POST['id_premio'];
    $premio=Premio::find($id_premio); if(!$premio || !$premio['bool_disponible']) die('Premio no disponible');
    $t=Tarjeta::findByCode($code); if(!$t) die('Tarjeta no encontrada'); if($t['enum_estado']!=='activa') die('Tarjeta bloqueada');
    if(empty($t['id_cliente'])) die('Tarjeta sin cliente asignado');
    $ok=Tarjeta::canjearPremio((int)$t['id_tarjeta'], (float)$premio['num_puntosrequeridos'], $premio['str_nombrepremio']);
    if(!$ok) die('Saldo insuficiente');
    $this->redirect('/tarjetas/saldo?code='.urlencode($code));
  }
}
