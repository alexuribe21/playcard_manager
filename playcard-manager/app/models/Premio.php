<?php
require_once __DIR__ . '/../core/Database.php';
final class Premio {
  public static function all(): array { return Database::getInstance()->query("SELECT * FROM tbl_premios ORDER BY id_premio DESC")->fetchAll(); }
  public static function find(int $id): ?array { $st=Database::getInstance()->prepare("SELECT * FROM tbl_premios WHERE id_premio=:id"); $st->execute([':id'=>$id]); $r=$st->fetch(); return $r?:null; }
  public static function create(array $d): void { Database::getInstance()->prepare("INSERT INTO tbl_premios (str_nombrepremio,num_puntosrequeridos,bool_disponible) VALUES (:n,:p,:b)")->execute([':n'=>$d['nombre'],':p'=>$d['puntos'],':b'=>!empty($d['disp'])?1:0]); }
  public static function update(int $id, array $d): void { Database::getInstance()->prepare("UPDATE tbl_premios SET str_nombrepremio=:n,num_puntosrequeridos=:p,bool_disponible=:b WHERE id_premio=:id")->execute([':n'=>$d['nombre'],':p'=>$d['puntos'],':b'=>!empty($d['disp'])?1:0,':id'=>$id]); }
  public static function delete(int $id): void { Database::getInstance()->prepare("DELETE FROM tbl_premios WHERE id_premio=:id")->execute([':id'=>$id]); }
}
