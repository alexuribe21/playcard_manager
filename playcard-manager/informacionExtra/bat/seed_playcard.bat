@echo off
REM Ejecuta el seed para PlayCard Manager en WampServer
cd /d C:\wamp64\www\playcard-manager
C:\wamp64\bin\php\php8.3.14\php.exe tools\seed.php
pause
