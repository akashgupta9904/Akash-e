@echo off
title Akash FF Panel — Phone Share & Live Tunnel
echo ========================================================
echo  Hosting Akash FF Panel for Phone Access (Cloudflare)
echo ========================================================
echo.
echo Starting secure tunnel for mobile phones...
echo You will see a live HTTPS link below (ends in .trycloudflare.com)
echo Open that link on your mobile phone browser!
echo.
call npx -y cloudflared tunnel --url http://localhost:3000
pause
