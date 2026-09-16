@echo off
title Akash FF Panel Server
echo ========================================================
echo  Launching Akash FF Panel Gaming Store Platform...
echo ========================================================
echo.

node -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js is not installed or not in PATH!
    echo Please install Node.js from https://nodejs.org
    pause
    exit /b
)

if not exist node_modules (
    echo [INFO] Installing node packages...
    call npm install
)

echo [INFO] Starting Express server on http://localhost:3000 ...
echo [INFO] Admin Dashboard available at: http://localhost:3000/admin/index.html
echo.
call node server.js
pause
