@echo off
set JWT_DIR=services\auth-service\storage\app\jwt

if not exist "%JWT_DIR%" mkdir "%JWT_DIR%"

echo Génération de la clé privée...
openssl genrsa -out "%JWT_DIR%\private.pem" 2048

echo Génération de la clé publique...
openssl rsa -in "%JWT_DIR%\private.pem" -pubout -out "%JWT_DIR%\public.pem"

echo Clés générées avec succès dans %JWT_DIR%
echo.
echo Ajoutez les clés à votre .env
