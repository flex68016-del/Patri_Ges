# Base de Données

Ce dossier contient la documentation de la base de données pour le système de gestion du patrimoine de l'État.

## Architecture

Une base PostgreSQL unique avec trois schémas/bases logiques :
- `auth_db` : Service d'authentification
- `patrimoine_db` : Service patrimoine
- `operations_db` : Service opérations

Chaque service a son propre utilisateur PostgreSQL avec droits limités.

## Normalisation

Le passage de la table non normalisée aux formes normales est documenté dans `normalisation.md`.

## Index

Le plan d'index est documenté dans `index.md`.

## Migrations et Seeders

Les migrations Laravel et les seeders seront créés dans les dossiers des services respectifs.
