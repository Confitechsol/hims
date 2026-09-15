@echo off
REM Laravel scheduler tick (every minute via Task Scheduler).
cd /d "D:\xampp-8.2\htdocs\hims"

set PHP_EXE=D:\xampp-8.2\php\php.exe
if not exist "%PHP_EXE%" set PHP_EXE=php

"%PHP_EXE%" artisan schedule:run >> "storage\logs\scheduler.log" 2>&1
