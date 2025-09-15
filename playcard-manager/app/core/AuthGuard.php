<?php
// app/core/AuthGuard.php — ejemplo de guard sin bucles
if (!defined('BASE_URL')) define('BASE_URL','/playcard-manager');
function base_url($p=''){ return rtrim(BASE_URL,'/').'/'.ltrim($p,'/'); }

class AuthGuard {
  public static function enforce() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    // Ruta pedida (sin el prefijo del proyecto)
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $prefix = rtrim(BASE_URL,'/');
    if ($prefix && strpos($path, $prefix) === 0) $path = ltrim(substr($path, strlen($prefix)), '/');

    // Rutas públicas que nunca deben redirigirse al login
    $public = ['auth/login', 'auth/logout', 'assets', 'public/assets'];
    $isPublic = false;
    foreach ($public as $allow) { if (strpos($path, $allow) === 0) { $isPublic = true; break; } }

    $logged = !empty($_SESSION['user_id']);

    // No logueado y ruta privada => al login (con BASE_URL)
    if (!$logged && !$isPublic) {
      header('Location: ' . base_url('auth/login'), true, 302);
      exit;
    }

    // Ya logueado y entra a login => al dashboard
    if ($logged && $path === 'auth/login') {
      header('Location: ' . base_url('dashboard/index'), true, 302);
      exit;
    }
  }
}
