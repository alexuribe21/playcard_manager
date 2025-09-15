<?php
declare(strict_types=1);
require_once __DIR__ . '/../core/Database.php';

final class Cliente {
  public static function create(array $d): int {
    $pdo = Database::getInstance();
    $cedula = trim((string)($d['str_cedula'] ?? ''));
    if ($cedula === '') { throw new InvalidArgumentException('La cédula es obligatoria'); }
    if (strlen($cedula) > 20) { throw new InvalidArgumentException('La cédula no puede superar 20 caracteres'); }

    $sql = "INSERT INTO tbl_clientes
      (str_nombre, str_apellido, str_cedula, str_telefono, str_email, str_direccion,
       str_codigosecreto, bool_anonimo, datetime_fecharegistro, bool_activo)
      VALUES (:nombre,:apellido,:cedula,:tel,:email,:direccion,:codigo,:anon, NOW(), :activo)";
    $st = $pdo->prepare($sql);
    $st->execute([
      ':nombre'    => trim((string)($d['str_nombre'] ?? '')),
      ':apellido'  => trim((string)($d['str_apellido'] ?? '')),
      ':cedula'    => $cedula,
      ':tel'       => trim((string)($d['str_telefono'] ?? '')),
      ':email'     => trim((string)($d['str_email'] ?? '')),
      ':direccion' => trim((string)($d['str_direccion'] ?? '')) ?: null,
      ':codigo'    => trim((string)($d['str_codigosecreto'] ?? '')),
      ':anon'      => !empty($d['bool_anonimo']) ? 1 : 0,
      ':activo'    => isset($d['bool_activo']) ? (int)!!$d['bool_activo'] : 1,
    ]);
    return (int)$pdo->lastInsertId();
  }

  public static function update(int $id, array $d): void {
    $pdo = Database::getInstance();
    $current = self::find($id);
    if (!$current) { throw new RuntimeException('Cliente no encontrado'); }

    $cedula = trim((string)($d['str_cedula'] ?? $current['str_cedula']));
    if ($cedula === '') { throw new InvalidArgumentException('La cédula es obligatoria'); }
    if (strlen($cedula) > 20) { throw new InvalidArgumentException('La cédula no puede superar 20 caracteres'); }

    $sql = "UPDATE tbl_clientes SET
      str_nombre=:nombre,
      str_apellido=:apellido,
      str_cedula=:cedula,
      str_telefono=:tel,
      str_email=:email,
      str_direccion=:direccion,
      str_codigosecreto=:codigo,
      bool_anonimo=:anon,
      bool_activo=:activo
      WHERE id_cliente=:id";
    $st = $pdo->prepare($sql);
    $st->execute([
      ':id'        => $id,
      ':nombre'    => trim((string)($d['str_nombre'] ?? $current['str_nombre'])),
      ':apellido'  => trim((string)($d['str_apellido'] ?? $current['str_apellido'])),
      ':cedula'    => $cedula,
      ':tel'       => trim((string)($d['str_telefono'] ?? $current['str_telefono'])),
      ':email'     => trim((string)($d['str_email'] ?? $current['str_email'])),
      ':direccion' => trim((string)($d['str_direccion'] ?? $current['str_direccion'])) ?: null,
      ':codigo'    => trim((string)($d['str_codigosecreto'] ?? $current['str_codigosecreto'])),
      ':anon'      => isset($d['bool_anonimo']) ? (int)!!$d['bool_anonimo'] : (int)$current['bool_anonimo'],
      ':activo'    => isset($d['bool_activo']) ? (int)!!$d['bool_activo'] : (int)$current['bool_activo'],
    ]);
  }

  public static function delete(int $id): void {
    $pdo = Database::getInstance();
    $pdo->prepare("DELETE FROM tbl_clientes WHERE id_cliente=:id")->execute([':id'=>$id]);
  }

  public static function find(int $id): ?array {
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT * FROM tbl_clientes WHERE id_cliente=:id");
    $st->execute([':id'=>$id]);
    $r = $st->fetch();
    return $r ?: null;
  }

  public static function search(string $q='', bool $soloActivos=false): array {
    $pdo = Database::getInstance();
    $where=[]; $params=[];
    if ($q !== '') {
      $where[] = "(str_nombre LIKE :q1 OR str_apellido LIKE :q2 OR str_email LIKE :q3 OR str_telefono LIKE :q4 OR str_cedula LIKE :q5 OR str_direccion LIKE :q6)";
      $params[':q1']=$params[':q2']=$params[':q3']=$params[':q4']=$params[':q5']=$params[':q6']='%'.$q+'%';
    }
    if ($soloActivos) { $where[]="bool_activo=1"; }
    $whereSql = $where ? ('WHERE '.implode(' AND ', $where)) : '';
    $sql = "SELECT * FROM tbl_clientes $whereSql ORDER BY id_cliente DESC LIMIT 500";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }
}
