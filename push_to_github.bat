@echo off
setlocal enabledelayedexpansion
title Pushing Ashu Gay Website to GitHub

echo ======================================================================
echo          UPLOADING ASHU GAY WEBSITE TO GITHUB (VERCEL SYNC)
echo ======================================================================
echo Repository: https://github.com/akashgupta9904/Akash-e.git
echo.

set "GIT_CMD=git"
where git >nul 2>&1
if %errorlevel% neq 0 (
    if exist "%LOCALAPPDATA%\Programs\Git\cmd\git.exe" (
        set "GIT_CMD=%LOCALAPPDATA%\Programs\Git\cmd\git.exe"
    ) else if exist "C:\Program Files\Git\cmd\git.exe" (
        set "GIT_CMD=C:\Program Files\Git\cmd\git.exe"
    )
)

echo [1/3] Adding all updated files...
"%GIT_CMD%" add .

echo [2/3] Checking Git Branch...
"%GIT_CMD%" branch -M main

echo [3/3] Pushing to GitHub (origin main)...
echo ----------------------------------------------------------------------
echo [IMPORTANT] Agar browser me GitHub Login / Authorize ka page khule,
echo to please 'Sign in with your browser' ya 'Authorize' par click karein!
echo ----------------------------------------------------------------------
echo.

"%GIT_CMD%" push -u origin main --force

echo.
echo ======================================================================
if %errorlevel% equ 0 (
    echo [SUCCESS] Sab kuch GitHub par successfully PUSH ho gaya hai!
    echo Ab aap Vercel par jaakar check kar sakte hain, site 10s me live hogi!
) else (
    echo [ERROR] Push fail hua ya login pending hai.
    echo Kripya upar ka message padhein aur GitHub authentication complete karein.
)
echo ======================================================================
echo.
pause
