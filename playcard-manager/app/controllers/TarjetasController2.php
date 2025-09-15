<?php
// app/controllers/TarjetasController.php (config en app/config/config.php soportado)
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/Flash.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Tarjeta.php';

// Orden de búsqueda de config:
$paths = [
  __DIR__ . '/../config/config.php', // app/config/config.php  ✅ (tu caso)
  __DIR__ . '/../config.php',        // app/config.php
  __DIR__ . '/../../config.php',     // /config.php en raíz
];
foreach ($paths as $p) { if (file_exists($p)) { require_once $p; break; } }

if (!defined('BASE_URL')) {
  // fallback seguro
  $script = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '');
  $base = rtrim(str_replace('\\','/', dirname($script)), '/');
  $base = preg_replace('#/public$#','', $base);
  if ($base === '') $base = '/';
  define('BASE_URL', $base);
}

final class TarjetasController extends Controller
{
    private function u(string $path): string {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }

    public function index() {
        Auth::requireLogin();
        $q = trim($_GET['q'] ?? '');
        $rows = Tarjeta::all($q);
        return $this->view('tarjetas/index', ['rows'=>$rows, 'q'=>$q]);
    }

    public function create() {
        Auth::requireLogin();
        return $this->view('tarjetas/create', ['old'=>$_SESSION['old'] ?? []]);
    }

    public function store() {
        Auth::requireLogin();
        if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(419); exit('CSRF inválido'); }
        $in = $this->validate($_POST);
        if (!empty($in['__errors'])) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $in['__errors'];
            return $this->redirect('tarjetas/create');
        }
        Tarjeta::create($in);
        Flash::set('success', 'Tarjeta creada correctamente');
        return $this->redirect('tarjetas/index');
    }

    public function edit() {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        $t = Tarjeta::find($id);
        if (!$t) { http_response_code(404); exit('Tarjeta no encontrada'); }
        return $this->view('tarjetas/edit', ['t'=>$t, 'old'=>$_SESSION['old'] ?? []]);
    }

    public function update() {
        Auth::requireLogin();
        if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(419); exit('CSRF inválido'); }
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        $in = $this->validate($_POST, $id);
        if (!empty($in['__errors'])) {
            $_SESSION['old'] = $_POST;
            $_SESSION['errors'] = $in['__errors'];
            return $this->redirect('tarjetas/edit?id=' . $id);
        }
        Tarjeta::update($id, $in);
        Flash::set('success', 'Tarjeta actualizada');
        return $this->redirect('tarjetas/edit?id=' . $id);
    }

    public function destroy() {
        Auth::requireLogin();
        if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(419); exit('CSRF inválido'); }
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        Tarjeta::delete($id);
        Flash::set('success', 'Tarjeta eliminada');
        return $this->redirect('tarjetas/index');
    }

    public function recargar() {
        Auth::requireLogin();
        if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(419); exit('CSRF inválido'); }
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        $monto = (float)($_POST['monto'] ?? 0);
        if ($monto <= 0) { Flash::set('danger','Monto inválido'); return $this->redirect($this->u('tarjetas/edit?id='.$id)); }
        Tarjeta::recargar($id, $monto);
        Flash::set('success','Recarga aplicada');
        return $this->redirect('tarjetas/edit?id='.$id);
    }

    public function toggle() {
        Auth::requireLogin();
        if (!CSRF::check($_POST['csrf'] ?? null)) { http_response_code(419); exit('CSRF inválido'); }
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); exit('ID inválido'); }
        Tarjeta::toggleEstado($id);
        Flash::set('success','Estado actualizado');
        return $this->redirect('tarjetas/edit?id='.$id);
    }

    public function saldo() {
        Auth::requireLogin();
        $rows = Tarjeta::all();
        return $this->view('tarjetas/saldo', ['rows'=>$rows]);
    }

    private function validate(array $in, ?int $id=null): array {
        $errors = [];
        $codigo = trim($in['str_coditotarjeta'] ?? '');
        if ($codigo === '') $errors['str_coditotarjeta'] = 'El código es obligatorio';
        elseif (!Tarjeta::validateCodigo($codigo)) $errors['str_coditotarjeta'] = 'Formato de código inválido';
        elseif (Tarjeta::numeroExiste($codigo, $id)) $errors['str_coditotarjeta'] = 'El código ya existe';

        $estado = $in['enum_estado'] ?? 'activa';
        if (!in_array($estado, ['activa','bloqueada'], true)) $errors['enum_estado'] = 'Estado inválido';

        $saldo = isset($in['num_saldo']) ? (float)$in['num_saldo'] : 0.0;
        if ($saldo < 0) $errors['num_saldo'] = 'El saldo no puede ser negativo';

        return [
            '__errors' => $errors,
            'str_coditotarjeta' => $codigo,
            'enum_estado' => $estado,
            'num_saldo'   => $saldo,
        ];
    }
}
