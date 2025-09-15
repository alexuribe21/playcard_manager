<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
class DashboardController extends Controller {
  public function index(){
    Auth::requireLogin(); $pdo=Database::getInstance();
    $stats=[
      'clientes'=>(int)$pdo->query("SELECT COUNT(*) c FROM tbl_clientes")->fetch()['c'],
      'tarjetas'=>(int)$pdo->query("SELECT COUNT(*) c FROM tbl_tarjetaclientes")->fetch()['c'],
      'juegos'  =>(int)$pdo->query("SELECT COUNT(*) c FROM tbl_juegos")->fetch()['c'],
      'trans'   =>(int)$pdo->query("SELECT COUNT(*) c FROM tbl_transacciones")->fetch()['c'],
    ];
    $this->view('dashboard/index',['stats'=>$stats]);
  }
}
