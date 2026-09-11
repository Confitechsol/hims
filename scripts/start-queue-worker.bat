@echo off
REM Persistent Laravel queue worker for HIMS (audit, bed-charges, default).
cd /d "D:\xampp-8.2\htdocs\hims"

set PHP_EXE=D:\xampp-8.2\php\php.exe
if not exist "%PHP_EXE%" set PHP_EXE=php

:loop
"%PHP_EXE%" artisan queue:work --queue=audit,bed-charges,default --sleep=3 --tries=3 --max-time=3600 --memory=256 >> "storage\logs\queue-worker.log" 2>&1
ping -n 3 127.0.0.1 >nul
goto loop
