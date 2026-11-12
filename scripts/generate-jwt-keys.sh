#!/bin/bash
# Script de génération des clés JWT pour les services

mkdir -p services/auth-service/storage/app/jwt
mkdir -p services/patrimoine-service/storage/app/jwt
mkdir -p services/operations-service/storage/app/jwt
mkdir -p services/web/storage/app/jwt

# Générer les clés
openssl genrsa -out services/auth-service/storage/app/jwt/private.pem 2048
openssl rsa -in services/auth-service/storage/app/jwt/private.pem -pubout -out services/auth-service/storage/app/jwt/public.pem

# Copier les clés vers les autres services
cp services/auth-service/storage/app/jwt/private.pem services/patrimoine-service/storage/app/jwt/
cp services/auth-service/storage/app/jwt/public.pem services/patrimoine-service/storage/app/jwt/
cp services/auth-service/storage/app/jwt/private.pem services/operations-service/storage/app/jwt/
cp services/auth-service/storage/app/jwt/public.pem services/operations-service/storage/app/jwt/
cp services/auth-service/storage/app/jwt/private.pem services/web/storage/app/jwt/
cp services/auth-service/storage/app/jwt/public.pem services/web/storage/app/jwt/

# Fixer les permissions
chmod 600 services/*/storage/app/jwt/*.pem

echo "Clés JWT générées avec succès"
