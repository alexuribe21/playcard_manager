<?php
// app/views/partials/basepath.php
// Carga config, define BASE_URL y helper url(), y fija <base href> para que
// los enlaces/form action RELATIVOS se resuelvan con el prefijo del proyecto.

// 1) Intentar cargar config desde varias ubicaciones
$__candidates = [
    __DIR__ . '/../../config/config.php', // app/config/config.php
    __DIR__ . '/../../config.php',        // app/config.php
    __DIR__ . '/../../../config.php',     // /config.php (raíz proyecto)
];
foreach ($__candidates as $__p) {
    if (file_exists($__p)) { require_once $__p; break; }
}

// 2) Fallback si no hay BASE_URL definida
if (!defined('BASE_URL')) {
    $script = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '');
    $base   = rtrim(str_replace('\\','/', dirname($script)), '/');
    // quitar /public si aplica
    $base   = preg_replace('#/public$#', '', $base);
    if ($base === '') { $base = '/'; }
    define('BASE_URL', $base);
}

// 3) Helper url()
if (!function_exists('url')) {
    function url(string $path): string {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

// 4) Base href (terminado en /)
$__BASE = rtrim(BASE_URL, '/') . '/';
?>
<base href="<?= htmlspecialchars($__BASE, ENT_QUOTES, 'UTF-8') ?>">
