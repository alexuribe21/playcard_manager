@echo off
REM Importa la estructura de la base de datos playcard_db en MySQL con password sbarman
cd /d C:\wamp64\www\playcard-manager\database
C:\wamp64\bin\mysql\mysql9.1.0\bin\mysql.exe -u root -psbarman playcard_db < schema.sql
pause
