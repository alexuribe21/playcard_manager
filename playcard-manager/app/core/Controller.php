<?php
// app/core/Controller.php — redirect robusto: no duplica BASE_URL
abstract class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $to): void
    {
        // Si es absoluta (http/https) o root-relative (/...), no tocar
        if (preg_match('#^https?://#i', $to) || str_starts_with($to, '/')) {
            $url = $to;
        } else {
            // Ruta corta: anteponer BASE_URL exactamente una vez
            $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
            $url  = $base . '/' . ltrim($to, '/');
        }
        header('Location: ' . $url, true, 302);
        exit;
    }
}
