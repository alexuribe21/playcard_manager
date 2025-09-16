<?php
final class Auth {
  public static function start(): void { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); }
  public static function login(array $user): void { self::start(); $_SESSION['user'] = ['id'=>$user['id_usuario'],'user'=>$user['str_nombreusuario'],'role'=>$user['enum_tipousuario']]; }
  public static function user(): ?array { self::start(); return $_SESSION['user'] ?? null; }
  public static function logout(): void { self::start(); session_destroy(); }
  public static function requireLogin(): void { if (!self::user()) { header('Location: ' . BASE_URL . '/auth/login'); exit; } }
  public static function requireAdmin(): void { self::requireLogin(); if (($_SESSION['user']['role']??'normal')!=='admin'){ http_response_code(403); echo "<div class='container py-5'><div class='alert alert-danger'>Acceso restringido.</div></div>"; exit; } }
}
