@echo off
title Ashu Gay Profile Launcher
if exist "AshuGayProfile.exe" (
    start "" "%~dp0AshuGayProfile.exe"
) else if exist "publish\AshuGayProfile.exe" (
    start "" "%~dp0publish\AshuGayProfile.exe"
) else (
    echo App not compiled yet. Running build.bat...
    call build.bat
    if exist "AshuGayProfile.exe" (
        start "" "%~dp0AshuGayProfile.exe"
    )
)
