<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../app/core/Router.php';

$router = new Router();
$router->dispatch();
