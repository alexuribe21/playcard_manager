@echo off
REM Importa la base de datos y ejecuta el seed de PlayCard Manager en un solo paso
echo === Importando estructura de la base de datos ===
cd /d C:\wamp64\www\playcard-manager\database
C:\wamp64\bin\mysql\mysql9.1.0\bin\mysql.exe -u root -psbarman playcard_db < schema.sql

echo === Ejecutando seed para datos iniciales ===
cd /d C:\wamp64\www\playcard-manager
C:\wamp64\bin\php\php8.3.14\php.exe tools\seed.php

echo === Proceso completo. Ahora puedes abrir http://localhost/playcard-manager/ ===
pause
