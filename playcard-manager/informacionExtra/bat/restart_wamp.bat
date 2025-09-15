@echo off
REM Reinicia todos los servicios de WampServer (si usas wampmanager.exe)
echo Reiniciando servicios de WampServer...
net stop wampapache64
net stop wampmysqld64
net start wampapache64
net start wampmysqld64
pause
