@echo off
title Ashu Gay Profile - Build Windows EXE
echo ==========================================================
echo    Ashu Gay Profile - C# Lightweight Desktop App Builder
echo ==========================================================
echo.

set EXE_DIR=C:\Users\akash\Documents\Gay exe src

if exist "%EXE_DIR%\build.bat" (
    cd /d "%EXE_DIR%"
    call build.bat
) else (
    echo [ERROR] %EXE_DIR% not found!
    pause
)
