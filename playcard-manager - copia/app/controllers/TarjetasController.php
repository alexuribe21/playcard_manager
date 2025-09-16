<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Tarjeta.php';

final class TarjetasController extends Controller {

  // LISTADO
  public function index() {
    Auth::requireLogin();
    $q = trim($_GET['q'] ?? '');
    $rows = Tarjeta::all($q);
    return $this->view('tarjetas/index', ['rows'=>$rows, 'q'=>$q]);
  }

  // FORM NUEVA
  public function create() {
    Auth::requireAdmin();
    return $this->view('tarjetas/create');
  }

  // GUARDAR NUEVA
  public function store() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $data = [
      'str_coditotarjeta' => trim($_POST['str_coditotarjeta'] ?? ''),
      'id_cliente'        => ($_POST['id_cliente'] !== '' ? (int)$_POST['id_cliente'] : null),
      'num_saldo'         => max(0,(float)($_POST['num_saldo'] ?? 0)),
      'enum_estado'       => $_POST['enum_estado'] ?? 'activa',
      'datetime_fechaasignacion' => date('Y-m-d H:i:s'),
    ];
    Tarjeta::create($data);
    return $this->redirect('/tarjetas/index');
  }

  // FORM EDITAR (toma id de ?id=)
  public function edit() {
    Auth::requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $t = Tarjeta::find($id);
    if (!$t) { http_response_code(404); exit('Tarjeta no encontrada'); }
    return $this->view('tarjetas/edit', ['t'=>$t]);
  }

  // GUARDAR EDICIÓN (id desde POST)
  public function update() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    $data = [
      'str_coditotarjeta' => trim($_POST['str_coditotarjeta'] ?? ''),
      'id_cliente'        => ($_POST['id_cliente'] !== '' ? (int)$_POST['id_cliente'] : null),
      'num_saldo'         => max(0,(float)($_POST['num_saldo'] ?? 0)),
      'enum_estado'       => $_POST['enum_estado'] ?? 'activa',
    ];
    Tarjeta::update($id, $data);
    return $this->redirect('/tarjetas/index');
  }

  // ELIMINAR (id desde POST)
  public function destroy() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    Tarjeta::delete($id);
    return $this->redirect('/tarjetas/index');
  }

  // ACTIVAR/BLOQUEAR (id desde POST)
  public function toggle() {
    Auth::requireAdmin();
    if (!CSRF::check($_POST['csrf'] ?? null)) die('CSRF');
    $id = (int)($_POST['id'] ?? 0);
    Tarjeta::toggleEstado($id);
    return $this->redirect('/tarjetas/index');
  }

  // HISTORIAL (toma id de ?id=)
  public function historial() {
    Auth::requireLogin();
    $id = (int)($_GET['id'] ?? 0);
    $t = Tarjeta::find($id);
    if (!$t) { http_response_code(404); exit('Tarjeta no encontrada'); }
    $movs = Tarjeta::historial($id);
    return $this->view('tarjetas/historial', ['t'=>$t, 'movs'=>$movs]);
  }

  // SALDO (HTML/JSON) acepta ?code= o ?id=
  public function saldo() {
    Auth::requireLogin();
    $format = strtolower(trim($_GET['format'] ?? 'html'));
    $code   = trim($_GET['code'] ?? '');
    $id     = (int)($_GET['id'] ?? 0);

    $tarjeta = null;
    if ($code !== '') {
      if (method_exists('Tarjeta','findByCodigo')) $tarjeta = Tarjeta::findByCodigo($code);
    } elseif ($id > 0) {
      $tarjeta = Tarjeta::find($id);
    }

    if ($format === 'json') {
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($tarjeta ? [
        'ok'=>true,
        'id_tarjeta'=>(int)$tarjeta['id_tarjeta'],
        'codigo'=>$tarjeta['str_coditotarjeta'],
        'saldo'=>(float)$tarjeta['num_saldo'],
        'estado'=>$tarjeta['enum_estado'],
        'id_cliente'=>(int)($tarjeta['id_cliente'] ?? 0)
      ] : ['ok'=>false,'error'=>'Tarjeta no encontrada']);
      return;
    }

    return $this->view('tarjetas/saldo', ['t'=>$tarjeta, 'code'=>$code, 'id'=>$id]);
  }
}
