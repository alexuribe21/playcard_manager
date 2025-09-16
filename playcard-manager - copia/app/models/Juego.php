<?php
require_once __DIR__ . '/../core/Database.php';
final class Juego {
  public static function all(): array { return Database::getInstance()->query("SELECT * FROM tbl_juegos ORDER BY id_juego DESC")->fetchAll(); }
  public static function active(): array { return Database::getInstance()->query("SELECT * FROM tbl_juegos WHERE bool_activo=1 ORDER BY str_nombrejuego")->fetchAll(); }
  public static function find(int $id): ?array { $st=Database::getInstance()->prepare("SELECT * FROM tbl_juegos WHERE id_juego=:id"); $st->execute([':id'=>$id]); $r=$st->fetch(); return $r?:null; }
  public static function create(array $d): void { Database::getInstance()->prepare("INSERT INTO tbl_juegos (str_nombrejuego,num_costoporuso,bool_activo,datetime_fechacreacion) VALUES (:n,:c,:a,NOW())")->execute([':n'=>$d['nombre'],':c'=>$d['costo'],':a'=>!empty($d['activo'])?1:0]); }
  public static function update(int $id, array $d): void { Database::getInstance()->prepare("UPDATE tbl_juegos SET str_nombrejuego=:n,num_costoporuso=:c,bool_activo=:a WHERE id_juego=:id")->execute([':n'=>$d['nombre'],':c'=>$d['costo'],':a'=>!empty($d['activo'])?1:0,':id'=>$id]); }
  public static function delete(int $id): void { Database::getInstance()->prepare("DELETE FROM tbl_juegos WHERE id_juego=:id")->execute([':id'=>$id]); }
}
