# LOCAL DEV ONLY — not used on Hostinger.
# Production uses QUEUE_AUTO_DRAIN + Hostinger cron for `php artisan schedule:run`.
#
# Optional always-on worker for Windows/XAMPP only:
#   powershell -ExecutionPolicy Bypass -File scripts\install-queue-autostart.ps1

$ErrorActionPreference = 'Stop'
Write-Host 'This script is for local Windows/XAMPP only.'
Write-Host 'On Hostinger: set QUEUE_AUTO_DRAIN=true and add cron:'
Write-Host '  * * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1'
Write-Host ''

$workerBat = Join-Path $PSScriptRoot 'start-queue-worker.bat'
$schedulerBat = Join-Path $PSScriptRoot 'run-scheduler.bat'
$startup = [Environment]::GetFolderPath('Startup')

if (-not (Test-Path $workerBat)) { throw "Missing $workerBat" }
if (-not (Test-Path $schedulerBat)) { throw "Missing $schedulerBat" }

$startupWorker = Join-Path $startup 'HIMS-QueueWorker.bat'
$startupSched = Join-Path $startup 'HIMS-LaravelScheduler.vbs'

@"
@echo off
start "" /min cmd /c "$workerBat"
"@ | Set-Content -Path $startupWorker -Encoding ASCII

@"
Set WshShell = CreateObject("WScript.Shell")
Do
  WshShell.Run "cmd /c ""$schedulerBat""", 0, True
  WScript.Sleep 60000
Loop
"@ | Set-Content -Path $startupSched -Encoding ASCII

Start-Process -FilePath 'cmd.exe' -ArgumentList '/c', "`"$workerBat`"" -WindowStyle Minimized
Start-Process -FilePath 'wscript.exe' -ArgumentList "`"$startupSched`"" -WindowStyle Hidden

Write-Host "Local Startup entries created (not for Hostinger)."
