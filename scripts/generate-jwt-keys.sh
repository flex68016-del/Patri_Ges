#!/bin/bash

# Script pour générer les clés JWT RSA

JWT_DIR="services/auth-service/storage/app/jwt"

mkdir -p "$JWT_DIR"

echo "Génération de la clé privée..."
openssl genrsa -out "$JWT_DIR/private.pem" 2048

echo "Génération de la clé publique..."
openssl rsa -in "$JWT_DIR/private.pem" -pubout -out "$JWT_DIR/public.pem"

echo "Définition des permissions..."
chmod 600 "$JWT_DIR/private.pem"
chmod 644 "$JWT_DIR/public.pem"

echo "Extraction des clés pour .env..."
PRIVATE_KEY=$(cat "$JWT_DIR/private.pem" | base64 -w 0)
PUBLIC_KEY=$(cat "$JWT_DIR/public.pem" | base64 -w 0)

echo "Clés générées avec succès !"
echo ""
echo "Ajoutez ceci à votre .env :"
echo "JWT_PRIVATE_KEY=$PRIVATE_KEY"
echo "JWT_PUBLIC_KEY=$PUBLIC_KEY"
