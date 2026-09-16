@echo off
title Ashu Gay Profile - EXE Builder
echo ==========================================================
echo    Ashu Gay Profile - C# Lightweight Desktop App Builder
echo ==========================================================
echo.

set WEB_SRC=C:\Users\akash\Documents\Phela Project
set DOTNET_EXE="C:\Program Files\dotnet\dotnet.exe"
if not exist %DOTNET_EXE% (
    set DOTNET_EXE=dotnet
)

echo [1/3] Syncing web assets from %WEB_SRC%...
if not exist "wwwroot" mkdir "wwwroot"
if exist "%WEB_SRC%\index.html" copy /Y "%WEB_SRC%\index.html" "wwwroot\index.html" >nul
if exist "%WEB_SRC%\style.css" copy /Y "%WEB_SRC%\style.css" "wwwroot\style.css" >nul
if exist "%WEB_SRC%\script.js" copy /Y "%WEB_SRC%\script.js" "wwwroot\script.js" >nul

echo [2/3] Compiling C# Windows Desktop EXE (Lightweight ~1.3 MB)...
%DOTNET_EXE% publish -c Release -r win-x64 --self-contained false -p:PublishSingleFile=true -o "publish"
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Build failed!
    pause
    exit /b %ERRORLEVEL%
)

echo [3/3] Copying executable to root folder...
copy /Y "publish\AshuGayProfile.exe" "AshuGayProfile.exe" >nul

echo.
echo ==========================================================
echo  [SUCCESS] AshuGayProfile.exe built successfully!
echo  Size: ~1.3 MB (Lightweight, Fast and Smooth)
echo  Location: %~dp0AshuGayProfile.exe
echo ==========================================================
echo.
pause
