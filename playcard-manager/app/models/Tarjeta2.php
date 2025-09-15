<?php
// app/models/Tarjeta.php
require_once __DIR__ . '/../core/Database.php';

final class Tarjeta {

  public static function validateCodigo(string $code): bool {
    if (preg_match('/^\d{20}&\d{14}&\d{2}\*[1-9]$/', $code)) return true;
    return (bool)preg_match('/^[A-Za-z0-9_-]{6,50}$/', $code);
  }

  public static function numeroExiste(string $codigo, ?int $ignoreId=null): bool {
    $pdo = Database::getInstance();
    if ($ignoreId) {
      $st=$pdo->prepare("SELECT id_tarjeta FROM tbl_tarjetaclientes WHERE str_coditotarjeta=? AND id_tarjeta<>? LIMIT 1");
      $st->execute([$codigo,$ignoreId]);
    } else {
      $st=$pdo->prepare("SELECT id_tarjeta FROM tbl_tarjetaclientes WHERE str_coditotarjeta=? LIMIT 1");
      $st->execute([$codigo]);
    }
    return (bool)$st->fetchColumn();
  }

  public static function all(string $q = ''): array {
    $pdo = Database::getInstance();
    $params = [];
    $where  = '';
    if ($q !== '') {
      $where = " WHERE (
        t.str_coditotarjeta LIKE :q1 OR
        c.str_nombre       LIKE :q2 OR
        c.str_apellido     LIKE :q3 OR
        c.str_email        LIKE :q4 OR
        c.str_cedula       LIKE :q5
      ) ";
      $like = '%'.$q.'%';
      $params = [ ':q1'=>$like, ':q2'=>$like, ':q3'=>$like, ':q4'=>$like, ':q5'=>$like ];
    }
    $sql = "SELECT t.*, c.str_nombre, c.str_apellido, c.str_cedula
            FROM tbl_tarjetaclientes t
            LEFT JOIN tbl_clientes c ON c.id_cliente = t.id_cliente
            {$where}
            ORDER BY t.id_tarjeta DESC";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public static function find(int $id): ?array {
    $pdo = Database::getInstance();
    $st=$pdo->prepare("SELECT * FROM tbl_tarjetaclientes WHERE id_tarjeta=?");
    $st->execute([$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function create(array $in): int {
    $pdo = Database::getInstance();
    $sql = "INSERT INTO tbl_tarjetaclientes
            (str_coditotarjeta, id_cliente, num_saldo, enum_estado, datetime_fechaasignacion, uid_hex, num_puntos, str_pin, date_fechavencimiento, txt_notas)
            VALUES (:codigo, :cliente, :saldo, :estado, :fasig, :uid, :puntos, :pin, :fv, :notas)";
    $st = $pdo->prepare($sql);
    $st->execute([
      ':codigo' => $in['str_coditotarjeta'],
      ':cliente'=> $in['id_cliente'] ?: null,
      ':saldo'  => $in['num_saldo'] ?? 0,
      ':estado' => $in['enum_estado'] ?? 'activa',
      ':fasig'  => $in['datetime_fechaasignacion'] ?: date('Y-m-d H:i:s'),
      ':uid'    => $in['uid_hex'] ?: null,
      ':puntos' => $in['num_puntos'] ?? 0,
      ':pin'    => $in['str_pin'] ?: null,
      ':fv'     => $in['date_fechavencimiento'] ?: null,
      ':notas'  => $in['txt_notas'] ?: null,
    ]);
    return (int)$pdo->lastInsertId();
  }

  public static function update(int $id, array $in): void {
    $pdo = Database::getInstance();
    $sql = "UPDATE tbl_tarjetaclientes SET
              str_coditotarjeta=:codigo, id_cliente=:cliente, num_saldo=:saldo, enum_estado=:estado,
              datetime_fechaasignacion=:fasig, uid_hex=:uid, num_puntos=:puntos, str_pin=:pin,
              date_fechavencimiento=:fv, txt_notas=:notas
            WHERE id_tarjeta=:id";
    $st = $pdo->prepare($sql);
    $st->execute([
      ':codigo' => $in['str_coditotarjeta'],
      ':cliente'=> $in['id_cliente'] ?: null,
      ':saldo'  => $in['num_saldo'] ?? 0,
      ':estado' => $in['enum_estado'] ?? 'activa',
      ':fasig'  => $in['datetime_fechaasignacion'] ?: date('Y-m-d H:i:s'),
      ':uid'    => $in['uid_hex'] ?: null,
      ':puntos' => $in['num_puntos'] ?? 0,
      ':pin'    => $in['str_pin'] ?: null,
      ':fv'     => $in['date_fechavencimiento'] ?: null,
      ':notas'  => $in['txt_notas'] ?: null,
      ':id'     => $id
    ]);
  }

  public static function delete(int $id): void {
    $pdo = Database::getInstance();
    $pdo->prepare("DELETE FROM tbl_tarjetaclientes WHERE id_tarjeta=?")->execute([$id]);
  }

  public static function recargar(int $id, float $monto): void {
    if ($monto <= 0) throw new InvalidArgumentException('Monto inválido');
    $pdo = Database::getInstance();
    $pdo->prepare("UPDATE tbl_tarjetaclientes SET num_saldo = num_saldo + :m WHERE id_tarjeta=:id")->execute([':m'=>$monto, ':id'=>$id]);
  }

  public static function toggleEstado(int $id): void {
    $pdo = Database::getInstance();
    $t = self::find($id);
    if (!$t) throw new RuntimeException('Tarjeta no encontrada');
    $nuevo = ($t['enum_estado']==='activa') ? 'bloqueada' : 'activa';
    $pdo->prepare("UPDATE tbl_tarjetaclientes SET enum_estado=:e WHERE id_tarjeta=:id")->execute([':e'=>$nuevo, ':id'=>$id]);
  }

  public static function historial(int $id): array {
    $pdo = Database::getInstance();
    $sql = "SELECT t.*, j.str_nombrejuego
            FROM tbl_transacciones t
            LEFT JOIN tbl_juegos j ON j.id_juego = t.id_juego
            WHERE t.id_tarjeta=:id
            ORDER BY t.id_transaccion DESC LIMIT 300";
    $st = $pdo->prepare($sql);
    $st->execute([':id'=>$id]);
    return $st->fetchAll();
  }
}
