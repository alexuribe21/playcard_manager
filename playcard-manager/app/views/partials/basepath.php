<?php
// app/views/partials/basepath.php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/playcard-manager');
}
if (!function_exists('url')) {
    function url(string $path): string {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}
$__BASE = rtrim(BASE_URL, '/') . '/';
?>
<base href="<?= htmlspecialchars($__BASE, ENT_QUOTES, 'UTF-8') ?>">
