<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Auth.php';
class Router {
  public static function dispatch(): void {
    $url = $_GET['url'] ?? 'auth/login';
    $url = trim($url, '/');
    [$controller, $action] = array_pad(explode('/', $url, 2), 2, 'index');
    $controllerName = ucfirst($controller) . 'Controller';
    $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
    if (!file_exists($controllerFile)) { http_response_code(404); echo "Controlador no encontrado"; return; }
    require_once $controllerFile;
    $c = new $controllerName();
    if (!method_exists($c, $action)) { http_response_code(404); echo "Acción no encontrada"; return; }
    $c->$action();
  }
}
