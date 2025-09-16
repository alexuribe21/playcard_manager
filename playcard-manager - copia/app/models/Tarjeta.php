<?php
require_once __DIR__ . '/../core/Database.php';

final class Tarjeta {

  public static function validateCodigo(string $code): bool {
    return (bool)preg_match('/^\d{20}&\d{14}&\d{2}\*[1-9]$/', $code);
  }

  public static function all(string $q = ''): array {
    $pdo = Database::getInstance();
    $params = [];
    $where  = '';
    if ($q !== '') {
      $where = " WHERE (t.str_coditotarjeta LIKE :q OR c.str_nombre LIKE :q OR c.str_apellido LIKE :q OR c.str_email LIKE :q) ";
      $params[':q'] = '%'.$q.'%';
    }
    $sql = "SELECT t.*, c.str_nombre, c.str_apellido, c.str_email
            FROM tbl_tarjetaclientes t
            LEFT JOIN tbl_clientes c ON c.id_cliente = t.id_cliente
            $where
            ORDER BY t.id_tarjeta DESC
            LIMIT 200";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public static function find(int $id): ?array {
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT * FROM tbl_tarjetaclientes WHERE id_tarjeta=:id");
    $st->execute([':id'=>$id]);
    $r = $st->fetch();
    return $r ?: null;
  }

  public static function findByCodigo(string $code): ?array {
    if (!self::validateCodigo($code)) { return null; }
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT * FROM tbl_tarjetaclientes WHERE str_coditotarjeta=:c");
    $st->execute([':c'=>$code]);
    $r = $st->fetch();
    return $r ?: null;
  }

  public static function create(array $data): int {
    $pdo = Database::getInstance();
    if (!self::validateCodigo($data['str_coditotarjeta'])) {
      throw new InvalidArgumentException('Formato de código de tarjeta inválido');
    }
    $sql = "INSERT INTO tbl_tarjetaclientes (str_coditotarjeta,id_cliente,num_saldo,enum_estado,datetime_fechaasignacion)
            VALUES (:code,:cliente,:saldo,:estado,:fecha)";
    $st = $pdo->prepare($sql);
    $st->execute([
      ':code'   => $data['str_coditotarjeta'],
      ':cliente'=> $data['id_cliente'] ?? null,
      ':saldo'  => $data['num_saldo'] ?? 0,
      ':estado' => $data['enum_estado'] ?? 'activa',
      ':fecha'  => $data['datetime_fechaasignacion'] ?? date('Y-m-d H:i:s'),
    ]);
    return (int)$pdo->lastInsertId();
  }

  public static function update(int $id, array $data): void {
    $pdo = Database::getInstance();
    $set = [];
    $params = [':id'=>$id];
    foreach (['str_coditotarjeta','id_cliente','num_saldo','enum_estado'] as $k) {
      if (array_key_exists($k, $data)) {
        $set[] = "$k = :$k";
        $params[":$k"] = $data[$k];
      }
    }
    if (!$set) return;
    $sql = "UPDATE tbl_tarjetaclientes SET ".implode(',', $set)." WHERE id_tarjeta=:id";
    $st = $pdo->prepare($sql);
    $st->execute($params);
  }

  public static function canDelete(int $id): bool {
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT COUNT(*) FROM tbl_transacciones WHERE id_tarjeta=:id");
    $st->execute([':id'=>$id]);
    return (int)$st->fetchColumn() == 0;
  }

  public static function delete(int $id): void {
    $pdo = Database::getInstance();
    if (!self::canDelete($id)) {
      throw new RuntimeException('No se puede eliminar: la tarjeta tiene transacciones. Bloquéala en su lugar.');
    }
    $pdo->prepare("DELETE FROM tbl_tarjetaclientes WHERE id_tarjeta=:id")->execute([':id'=>$id]);
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
