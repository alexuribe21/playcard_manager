<?php
final class Flash {
  public static function set(string $type, string $msg): void { if (session_status()!==PHP_SESSION_ACTIVE) session_start(); $_SESSION['flash']=['type'=>$type,'msg'=>$msg]; }
  public static function get(): ?array { if (session_status()!==PHP_SESSION_ACTIVE) session_start(); $f=$_SESSION['flash']??null; unset($_SESSION['flash']); return $f; }
}
