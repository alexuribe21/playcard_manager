<?php
// app/core/Router.php (versión robusta)
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct()
    {
        // Cargar archivos de rutas si existen
        $routesDir = __DIR__ . '/../../routes';
        if (is_dir($routesDir)) {
            foreach (glob($routesDir . '/*.php') as $file) {
                // Cada archivo puede llamar a $this->get() / $this->post()
                require $file;
            }
        }
    }

    // Registro de rutas explícitas
    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }
    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    // Normaliza path: sin barra final, siempre con leading slash
    private function normalize(string $path): string
    {
        $p = '/' . ltrim($path, '/');
        return rtrim($p, '/');
    }

    // Despachar la petición actual
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $raw = $_GET['url'] ?? '';
        $raw = trim($raw, "/ \t\n\r\0\x0B");
        if ($raw === '') $raw = 'dashboard/index';

        $path = '/' . $raw;
        $norm = $this->normalize($path);

        // 1) Intentar tabla de rutas
        if (isset($this->routes[$method][$norm])) {
            $handler = $this->routes[$method][$norm];
            $this->invokeHandler($handler);
            return;
        }

        // 2) Resolver por convención /Controller/action
        $parts = explode('/', ltrim($norm, '/'));
        $controllerName = !empty($parts[0]) ? $parts[0] : 'dashboard';
        $action = $parts[1] ?? 'index';

        $class = ucfirst($controllerName) . 'Controller';
        $file = __DIR__ . '/../controllers/' . $class . '.php';
        if (!file_exists($file)) {
            http_response_code(404);
            echo 'Controlador no encontrado';
            return;
        }
        require_once $file;
        if (!class_exists($class)) {
            http_response_code(500);
            echo 'Error: clase de controlador no definida: ' . htmlspecialchars($class);
            return;
        }
        $controller = new $class();
        if (!method_exists($controller, $action)) {
            http_response_code(404);
            echo 'Acción no encontrada';
            return;
        }
        // Ejecutar acción
        $controller->$action();
    }

    private function invokeHandler(callable|array $handler): void
    {
        if (is_array($handler)) {
            // [ControllerClass, 'method']
            [$class, $method] = $handler;
            $file = __DIR__ . '/../controllers/' . $class . '.php';
            if (!file_exists($file)) {
                http_response_code(404);
                echo 'Controlador no encontrado';
                return;
            }
            require_once $file;
            if (!class_exists($class)) {
                http_response_code(500);
                echo 'Error: clase de controlador no definida: ' . htmlspecialchars($class);
                return;
            }
            $controller = new $class();
            if (!method_exists($controller, $method)) {
                http_response_code(404);
                echo 'Acción no encontrada';
                return;
            }
            $controller->$method();
            return;
        }
        // Callable directo
        call_user_func($handler);
    }
}
