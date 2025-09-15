<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/core/Database.php';
$pdo = Database::getInstance();
echo "== Seeding PlayCard Manager ==\n";

// admin
$u = $pdo->prepare("SELECT COUNT(*) c FROM tbl_usuarios WHERE str_nombreusuario = :u");
$u->execute([':u'=>'admin']);
if ((int)$u->fetch()['c'] === 0) {
  $pdo->prepare("INSERT INTO tbl_usuarios (str_nombreusuario,str_password,enum_tipousuario,datetime_fechacreacion,bool_activo)
    VALUES('admin', :p, 'admin', NOW(), 1)")->execute([':p'=>password_hash('Admin123!', PASSWORD_DEFAULT)]);
  echo "Usuario admin creado (admin / Admin123!)\n";
} else { echo "Usuario admin ya existe.\n"; }

// juegos
$games = [
  ['Ruleta Manual', 5.00],
  ['Lanza el Dado', 3.50],
  ['Tiro al Blanco', 4.00],
];
foreach ($games as $g) {
  $c = $pdo->prepare("SELECT COUNT(*) c FROM tbl_juegos WHERE str_nombrejuego=:n");
  $c->execute([':n'=>$g[0]]);
  if ((int)$c->fetch()['c']===0) {
    $pdo->prepare("INSERT INTO tbl_juegos (str_nombrejuego,num_costoporuso,bool_activo,datetime_fechacreacion) VALUES (:n,:c,1,NOW())")
        ->execute([':n'=>$g[0], ':c'=>$g[1]]);
    echo "Juego sembrado: {$g[0]}\n";
  }
}

// tarjetas (100)
$count = (int)$pdo->query("SELECT COUNT(*) c FROM tbl_tarjetaclientes")->fetch()['c'];
if ($count < 100) {
  for ($i=1; $i<=100; $i++) {
    $prefix20=''; for($j=0;$j<20;$j++){$prefix20.=mt_rand(0,9);}
    $mid14 = str_pad((string)(55550000000000 + $i), 14, '0', STR_PAD_LEFT);
    $codigo = $prefix20 . '&' . $mid14 . '&' . '01*2';
    try {
      $pdo->prepare("INSERT INTO tbl_tarjetaclientes (str_coditotarjeta,id_cliente,num_saldo,enum_estado,datetime_fechaasignacion) VALUES (:c,NULL,0.00,'activa',NULL)")
        ->execute([':c'=>$codigo]);
    } catch(Exception $e){ /* ignora duplicados */ }
  }
  echo "Pool de 100 tarjetas creado.\n";
} else { echo "Tarjetas ya existentes: $count\n"; }

echo "== Seed completo ==\n";
