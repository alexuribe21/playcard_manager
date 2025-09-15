<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Juego.php';
class TransaccionesController extends Controller {
  public function cobro(){ Auth::requireLogin(); $juegos=\Juego::active(); $this->view('transacciones/cobro',['juegos'=>$juegos]); }
}
