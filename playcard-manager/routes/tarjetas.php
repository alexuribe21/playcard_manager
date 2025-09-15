<?php
// routes/tarjetas.php
// Se asume que este archivo es incluido por Router::__construct()

// Rutas GET
$this->get('/tarjetas/index', ['TarjetasController', 'index']);
$this->get('/tarjetas/create', ['TarjetasController', 'create']);
$this->get('/tarjetas/edit', ['TarjetasController', 'edit']);
$this->get('/tarjetas/saldo', ['TarjetasController', 'saldo']);

// Rutas POST
$this->post('/tarjetas/store', ['TarjetasController', 'store']);
$this->post('/tarjetas/update', ['TarjetasController', 'update']);
$this->post('/tarjetas/destroy', ['TarjetasController', 'destroy']);
$this->post('/tarjetas/recargar', ['TarjetasController', 'recargar']);
$this->post('/tarjetas/toggle', ['TarjetasController', 'toggle']);
