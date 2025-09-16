<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Juego.php';
class JuegosController extends Controller {
  public function index(){ Auth::requireAdmin(); $rows=Juego::all(); $this->view('juegos/index',['rows'=>$rows]); }
  public function create(){ Auth::requireAdmin(); $this->view('juegos/create'); }
  public function store(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Juego::create(['nombre'=>$_POST['nombre'],'costo'=>(float)$_POST['costo'],'activo'=>isset($_POST['activo'])]); $this->redirect('/juegos/index'); }
  public function edit(){ Auth::requireAdmin(); $id=(int)$_GET['id']; $j=Juego::find($id); $this->view('juegos/edit',['j'=>$j]); }
  public function update(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Juego::update((int)$_POST['id'],['nombre'=>$_POST['nombre'],'costo'=>(float)$_POST['costo'],'activo'=>isset($_POST['activo'])]); $this->redirect('/juegos/index'); }
  public function delete(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf']??null)) die('CSRF'); Juego::delete((int)$_POST['id']); $this->redirect('/juegos/index'); }
}
