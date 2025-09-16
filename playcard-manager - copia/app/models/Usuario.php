<?php
require_once __DIR__ . '/../core/Database.php';

final class Usuario {
  public static function findByUsername(string $user): ?array {
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT * FROM tbl_usuarios WHERE str_nombreusuario=:u AND bool_activo=1");
    $st->execute([':u'=>$user]);
    $r=$st->fetch();
    return $r?:null;
  }

  public static function findById(int $id): ?array {
    $pdo = Database::getInstance();
    $st = $pdo->prepare("SELECT * FROM tbl_usuarios WHERE id_usuario=:id");
    $st->execute([':id'=>$id]);
    $r=$st->fetch(); return $r?:null;
  }

  public static function all(): array {
    return Database::getInstance()->query("SELECT id_usuario, str_nombreusuario, enum_tipousuario, datetime_fechacreacion, bool_activo FROM tbl_usuarios ORDER BY id_usuario DESC")->fetchAll();
  }

  public static function create(string $username, string $password, string $role, bool $active=true): bool {
    $pdo = Database::getInstance();
    if (!in_array($role, ['admin','normal'], true)) { throw new InvalidArgumentException('Rol inválido'); }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO tbl_usuarios (str_nombreusuario,str_password,enum_tipousuario,datetime_fechacreacion,bool_activo)
            VALUES (:u,:p,:r,NOW(),:a)";
    try {
      $st = $pdo->prepare($sql);
      return $st->execute([':u'=>$username, ':p'=>$hash, ':r'=>$role, ':a'=>$active?1:0]);
    } catch (PDOException $e) {
      if ((int)$e->errorInfo[1] === 1062) { throw new RuntimeException('El usuario ya existe'); }
      throw $e;
    }
  }

  public static function updateUser(int $id, string $username, string $role, bool $active): void {
    if (!in_array($role, ['admin','normal'], true)) { throw new InvalidArgumentException('Rol inválido'); }
    $pdo = Database::getInstance();
    $sql = "UPDATE tbl_usuarios SET str_nombreusuario=:u, enum_tipousuario=:r, bool_activo=:a WHERE id_usuario=:id";
    $st = $pdo->prepare($sql);
    try {
      $st->execute([':u'=>$username, ':r'=>$role, ':a'=>$active?1:0, ':id'=>$id]);
    } catch (PDOException $e) {
      if ((int)$e->errorInfo[1] === 1062) { throw new RuntimeException('El usuario ya existe'); }
      throw $e;
    }
  }

  public static function setActive(int $id, bool $active): void {
    Database::getInstance()->prepare("UPDATE tbl_usuarios SET bool_activo=:a WHERE id_usuario=:id")->execute([':a'=>$active?1:0, ':id'=>$id]);
  }

  public static function resetPassword(int $id, string $newPass): void {
    $hash = password_hash($newPass, PASSWORD_DEFAULT);
    Database::getInstance()->prepare("UPDATE tbl_usuarios SET str_password=:p WHERE id_usuario=:id")->execute([':p'=>$hash, ':id'=>$id]);
  }

  public static function verifyPassword(int $id, string $password): bool {
    $u = self::findById($id);
    if (!$u) return false;
    return password_verify($password, $u['str_password']);
  }
}
