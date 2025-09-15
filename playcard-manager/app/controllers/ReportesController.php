<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class ReportesController extends Controller {

  // ---------- Utilidad de filtros para listado detalle ----------
  private function filtros() : array {
    $desde = $_GET['desde'] ?? '';
    $hasta = $_GET['hasta'] ?? '';
    $tipo  = $_GET['tipo']  ?? '';
    $juego = isset($_GET['id_juego']) ? (int)$_GET['id_juego'] : 0;
    $code  = trim($_GET['code'] ?? '');
    return compact('desde','hasta','tipo','juego','code');
  }
  private function buildWhere(array $f, array &$params): string {
    $w = ["1=1"];
    if ($f['desde'] !== '') { $w[] = "t.datetime_fechatransaccion >= :desde"; $params[':desde'] = $f['desde'].' 00:00:00'; }
    if ($f['hasta'] !== '') { $w[] = "t.datetime_fechatransaccion <= :hasta"; $params[':hasta'] = $f['hasta'].' 23:59:59'; }
    if ($f['tipo']  !== '') { $w[] = "t.enum_tipotransaccion = :tipo";      $params[':tipo']  = $f['tipo']; }
    if ($f['juego'] > 0)     { $w[] = "t.id_juego = :juego";                 $params[':juego'] = $f['juego']; }
    if ($f['code']  !== '')  { $w[] = "tar.str_coditotarjeta = :code";       $params[':code']  = $f['code']; }
    return implode(' AND ', $w);
  }

  // ---------- Listado detalle con export CSV/PDF ----------
  public function index() {
    Auth::requireAdmin();
    $pdo = Database::getInstance();
    $f = $this->filtros();
    $params = [];
    $where = $this->buildWhere($f, $params);

    $sql = "SELECT t.id_transaccion, t.enum_tipotransaccion, t.id_juego, j.str_nombrejuego,
                   t.num_monto, t.num_saldoanterior, t.num_saldonuevo, t.datetime_fechatransaccion,
                   tar.str_coditotarjeta, t.str_descripcion
            FROM tbl_transacciones t
            LEFT JOIN tbl_juegos j ON j.id_juego = t.id_juego
            INNER JOIN tbl_tarjetaclientes tar ON tar.id_tarjeta = t.id_tarjeta
            WHERE $where
            ORDER BY t.id_transaccion DESC
            LIMIT 500";
    $st = $pdo->prepare($sql); $st->execute($params); $rows = $st->fetchAll();

    $totales = ['carga'=>0,'consumo'=>0,'premio'=>0,'total'=>0];
    foreach ($rows as $r) {
      $t = $r['enum_tipotransaccion'];
      if (isset($totales[$t])) $totales[$t] += (float)$r['num_monto'];
      $totales['total'] += (float)$r['num_monto'];
    }
    $juegos = $pdo->query("SELECT id_juego, str_nombrejuego FROM tbl_juegos WHERE bool_activo = 1 ORDER BY str_nombrejuego")->fetchAll();
    $this->view('reportes/index', ['rows'=>$rows,'f'=>$f,'totales'=>$totales,'juegos'=>$juegos]);
  }

  public function exportCsv() {
    Auth::requireAdmin();
    $pdo = Database::getInstance(); $f = $this->filtros(); $params = []; $where = $this->buildWhere($f,$params);
    $sql = "SELECT t.id_transaccion AS ID, t.enum_tipotransaccion AS Tipo, tar.str_coditotarjeta AS Tarjeta,
                   IFNULL(j.str_nombrejuego,'—') AS Juego, t.num_monto AS Monto, t.num_saldoanterior AS SaldoAnterior,
                   t.num_saldonuevo AS SaldoNuevo, t.datetime_fechatransaccion AS Fecha, t.str_descripcion AS Descripcion
            FROM tbl_transacciones t
            LEFT JOIN tbl_juegos j ON j.id_juego = t.id_juego
            INNER JOIN tbl_tarjetaclientes tar ON tar.id_tarjeta = t.id_tarjeta
            WHERE $where
            ORDER BY t.id_transaccion DESC";
    $st=$pdo->prepare($sql); $st->execute($params); $rows=$st->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="reporte_transacciones.csv"');
    $out=fopen('php://output','w');
    if ($rows){ fputcsv($out,array_keys($rows[0])); foreach($rows as $r) fputcsv($out,$r);} else { fputcsv($out,['Sin datos']); }
    fclose($out); exit;
  }

  public function exportPdf() {
    Auth::requireAdmin();
    require_once __DIR__ . '/../lib/SimplePDF.php';
    $pdo = Database::getInstance(); $f=$this->filtros(); $params=[]; $where=$this->buildWhere($f,$params);
    $sql = "SELECT t.id_transaccion, t.enum_tipotransaccion, tar.str_coditotarjeta,
                   IFNULL(j.str_nombrejuego,'—') AS juego, t.num_monto, t.datetime_fechatransaccion
            FROM tbl_transacciones t
            LEFT JOIN tbl_juegos j ON j.id_juego = t.id_juego
            INNER JOIN tbl_tarjetaclientes tar ON tar.id_tarjeta = t.id_tarjeta
            WHERE $where
            ORDER BY t.id_transaccion DESC LIMIT 1000";
    $st=$pdo->prepare($sql); $st->execute($params); $rows=$st->fetchAll();
    $pdf=new SimplePDF(); $pdf->addPage(); $pdf->text(20,20,'Reporte de Transacciones'); $y=32;
    foreach($rows as $r){ $line=sprintf('#%d | %s | %s | %s | $%.2f | %s',$r['id_transaccion'],$r['enum_tipotransaccion'],$r['str_coditotarjeta'],$r['juego'],(float)$r['num_monto'],$r['datetime_fechatransaccion']); $pdf->text(20,$y,$line); $y+=6; if($y>280){$pdf->addPage(); $y=20;} }
    $pdf->output('reporte_transacciones.pdf'); exit;
  }

  // ---------- Resumen por cliente ----------
  public function resumenClientes() {
    Auth::requireAdmin();
    $pdo = Database::getInstance();
    $desde = $_GET['desde'] ?? ''; $hasta = $_GET['hasta'] ?? '';
    $params = []; $w = ["1=1"];
    if ($desde!==''){ $w[]="t.datetime_fechatransaccion>=:d"; $params[':d']=$desde.' 00:00:00'; }
    if ($hasta!==''){ $w[]="t.datetime_fechatransaccion<=:h"; $params[':h']=$hasta.' 23:59:59'; }

    $sql = "SELECT c.id_cliente,
                   CONCAT(IFNULL(c.str_nombre,''),' ',IFNULL(c.str_apellido,'')) AS cliente,
                   SUM(CASE WHEN t.enum_tipotransaccion='carga'   THEN t.num_monto ELSE 0 END) AS total_cargas,
                   SUM(CASE WHEN t.enum_tipotransaccion='consumo' THEN t.num_monto ELSE 0 END) AS total_consumos,
                   SUM(CASE WHEN t.enum_tipotransaccion='premio'  THEN t.num_monto ELSE 0 END) AS total_premios,
                   COUNT(t.id_transaccion) AS movimientos
            FROM tbl_clientes c
            LEFT JOIN tbl_tarjetaclientes tar ON tar.id_cliente = c.id_cliente
            LEFT JOIN tbl_transacciones t ON t.id_tarjeta = tar.id_tarjeta AND ".implode(' AND ', $w)."
            GROUP BY c.id_cliente
            ORDER BY cliente ASC";
    $st = $pdo->prepare($sql); $st->execute($params); $rows = $st->fetchAll();

    // totales globales
    $acc = ['cargas'=>0,'consumos'=>0,'premios'=>0];
    foreach($rows as $r){ $acc['cargas']+=(float)$r['total_cargas']; $acc['consumos']+=(float)$r['total_consumos']; $acc['premios']+=(float)$r['total_premios']; }

    $this->view('reportes/resumen_clientes', ['rows'=>$rows,'desde'=>$desde,'hasta'=>$hasta,'acc'=>$acc]);
  }

  public function exportResumenClientesCsv() {
    Auth::requireAdmin();
    $pdo = Database::getInstance();
    $desde = $_GET['desde'] ?? ''; $hasta = $_GET['hasta'] ?? '';
    $params = []; $w = ["1=1"];
    if ($desde!==''){ $w[]="t.datetime_fechatransaccion>=:d"; $params[':d']=$desde.' 00:00:00'; }
    if ($hasta!==''){ $w[]="t.datetime_fechatransaccion<=:h"; $params[':h']=$hasta.' 23:59:59'; }

    $sql = "SELECT c.id_cliente AS ID, CONCAT(IFNULL(c.str_nombre,''),' ',IFNULL(c.str_apellido,'')) AS Cliente,
                   SUM(CASE WHEN t.enum_tipotransaccion='carga'   THEN t.num_monto ELSE 0 END) AS TotalCargas,
                   SUM(CASE WHEN t.enum_tipotransaccion='consumo' THEN t.num_monto ELSE 0 END) AS TotalConsumos,
                   SUM(CASE WHEN t.enum_tipotransaccion='premio'  THEN t.num_monto ELSE 0 END) AS TotalPremios,
                   COUNT(t.id_transaccion) AS Movimientos
            FROM tbl_clientes c
            LEFT JOIN tbl_tarjetaclientes tar ON tar.id_cliente = c.id_cliente
            LEFT JOIN tbl_transacciones t ON t.id_tarjeta = tar.id_tarjeta AND ".implode(' AND ', $w)."
            GROUP BY c.id_cliente
            ORDER BY Cliente ASC";
    $st=$pdo->prepare($sql); $st->execute($params); $rows=$st->fetchAll();
    header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="resumen_clientes.csv"');
    $out=fopen('php://output','w'); if($rows){ fputcsv($out,array_keys($rows[0])); foreach($rows as $r) fputcsv($out,$r);} else { fputcsv($out,['Sin datos']); } fclose($out); exit;
  }

  // ---------- Cierre diario (agrupado por fecha y tipo) ----------
  public function cierreDiario() {
    Auth::requireAdmin();
    $pdo = Database::getInstance();
    $desde = $_GET['desde'] ?? ''; $hasta = $_GET['hasta'] ?? '';
    $params = []; $w = ["1=1"];
    if ($desde!==''){ $w[]="t.datetime_fechatransaccion>=:d"; $params[':d']=$desde.' 00:00:00'; }
    if ($hasta!==''){ $w[]="t.datetime_fechatransaccion<=:h"; $params[':h']=$hasta.' 23:59:59'; }

    $sql = "SELECT DATE(t.datetime_fechatransaccion) AS fecha,
                   t.enum_tipotransaccion AS tipo,
                   COUNT(*) AS conteo,
                   SUM(t.num_monto) AS total
            FROM tbl_transacciones t
            WHERE ".implode(' AND ', $w)."
            GROUP BY DATE(t.datetime_fechatransaccion), t.enum_tipotransaccion
            ORDER BY fecha DESC, FIELD(tipo,'carga','consumo','premio')";
    $st=$pdo->prepare($sql); $st->execute($params); $rows=$st->fetchAll();

    // Armado por día
    $dias = [];
    foreach($rows as $r){
      $d = $r['fecha'];
      if (!isset($dias[$d])) $dias[$d] = ['carga'=>['conteo'=>0,'total'=>0],'consumo'=>['conteo'=>0,'total'=>0],'premio'=>['conteo'=>0,'total'=>0]];
      $dias[$d][$r['tipo']]['conteo'] = (int)$r['conteo'];
      $dias[$d][$r['tipo']]['total']  = (float)$r['total'];
    }
    $this->view('reportes/cierre_diario', ['dias'=>$dias,'desde'=>$desde,'hasta'=>$hasta]);
  }

  public function exportCierreDiarioCsv() {
    Auth::requireAdmin();
    $pdo = Database::getInstance();
    $desde = $_GET['desde'] ?? ''; $hasta = $_GET['hasta'] ?? '';
    $params = []; $w = ["1=1"];
    if ($desde!==''){ $w[]="t.datetime_fechatransaccion>=:d"; $params[':d']=$desde+' 00:00:00'; }
    if ($hasta!==''){ $w[]="t.datetime_fechatransaccion<=:h"; $params[':h']=$hasta.' 23:59:59'; }

    $sql = "SELECT DATE(t.datetime_fechatransaccion) AS Fecha,
                   t.enum_tipotransaccion AS Tipo,
                   COUNT(*) AS Conteo,
                   SUM(t.num_monto) AS Total
            FROM tbl_transacciones t
            WHERE ".implode(' AND ', $w)."
            GROUP BY DATE(t.datetime_fechatransaccion), t.enum_tipotransaccion
            ORDER BY Fecha DESC, FIELD(Tipo,'carga','consumo','premio')";
    $st=$pdo->prepare($sql); $st->execute($params); $rows=$st->fetchAll();
    header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="cierre_diario.csv"');
    $out=fopen('php://output','w'); if($rows){ fputcsv($out,array_keys($rows[0])); foreach($rows as $r) fputcsv($out,$r);} else { fputcsv($out,['Sin datos']); } fclose($out); exit;
  }

}
