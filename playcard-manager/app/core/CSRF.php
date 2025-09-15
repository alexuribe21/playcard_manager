<?php
final class CSRF {
  public static function token(): string { if (session_status()!==PHP_SESSION_ACTIVE) session_start(); if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
  public static function field(): string { return '<input type="hidden" name="csrf" value="'.htmlspecialchars(self::token(), ENT_QUOTES).'">'; }
  public static function check(?string $token): bool { if (session_status()!==PHP_SESSION_ACTIVE) session_start(); return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$token); }
}
