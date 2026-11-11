# Gestion du Patrimoine de l'État

Solution numérique de gestion du patrimoine de l'État (bâtiments, terrains, véhicules, équipements).

## Architecture

Micro-services Laravel :
- `auth-service` : identité et accès (JWT RS256)
- `patrimoine-service` : référentiels, biens, affectations, valorisation, réforme/cession
- `operations-service` : maintenance, inventaire
- `web` : interface Laravel Blade + Alpine.js + Tailwind CSS
- `gateway` : Nginx (point d'entrée unique)

## Stack

- PHP 8.2
- Laravel 12.x
- PostgreSQL 16
- Docker
- Alpine.js
- Tailwind CSS
- Chart.js

## Lancement

### Prérequis

- Docker Desktop installé
- PHP 8.2+ installé localement
- Composer installé localement

### Configuration

```bash
# Cloner le dépôt
git clone <url>
cd Patrimoine_Gouv

# Générer les clés JWT (Windows)
scripts\generate-jwt-keys.bat

# Configurer l'environnement
cp .env.example .env
# Éditer .env avec vos clés JWT encodées en base64
```

### Démarrage

```bash
# Démarrer tous les services
docker compose up --build

# Les services seront accessibles sur :
# - web : http://localhost:8000
# - auth-service : http://localhost:8001
# - patrimoine-service : http://localhost:8002
# - operations-service : http://localhost:8003
```

### Migrations et Seeders

```bash
# Exécuter les migrations (dans un autre terminal)
docker compose exec auth-service php artisan migrate
docker compose exec patrimoine-service php artisan migrate
docker compose exec operations-service php artisan migrate

# Exécuter les seeders
docker compose exec patrimoine-service php artisan db:seed
docker compose exec operations-service php artisan db:seed
```

## Documentation

- [Analyse du besoin](docs/analyse-besoin.md)
- [UML](docs/uml/README.md)
- [Design](docs/design/README.md)
- [Base de données](docs/database/README.md)
- [Décisions d'architecture](docs/DECISIONS.md)
- [Journal d'avancement](docs/JOURNAL.md)

## API Endpoints

### Auth Service
- `POST /login` - Connexion (retourne token JWT)
- `POST /logout` - Déconnexion
- `GET /me` - Profil utilisateur connecté
- `GET /health` - Healthcheck

### Patrimoine Service
- `GET /api/services` - Liste des services
- `GET /api/categories` - Liste des catégories
- `GET /api/biens` - Liste des biens (avec filtres)
- `POST /api/biens` - Créer un bien
- `POST /api/biens/{id}/affecter` - Affecter un bien
- `POST /api/biens/{id}/reformer` - Réformer un bien
- `POST /api/biens/{id}/ceder` - Céder un bien
- `GET /api/biens/{id}/historique` - Historique des mouvements

### Operations Service
- `GET /api/maintenances` - Liste des maintenances
- `POST /api/maintenances` - Créer une maintenance
- `POST /api/maintenances/{id}/cloturer` - Clôturer une maintenance
- `GET /api/campagnes-inventaire` - Liste des campagnes
- `POST /api/campagnes-inventaire` - Créer une campagne
- `POST /api/campagnes-inventaire/{id}/cloturer` - Clôturer une campagne
- `GET /api/anomalies` - Liste des anomalies
- `POST /api/anomalies` - Signaler une anomalie
- `POST /api/anomalies/{id}/resoudre` - Résoudre une anomalie

## Développement

### Tests

```bash
# Tests par service
docker compose exec auth-service php artisan test
docker compose exec patrimoine-service php artisan test
docker compose exec operations-service php artisan test
docker compose exec web php artisan test
```

### Qualité

```bash
# Linting (Pint)
docker compose exec auth-service ./vendor/bin/pint
docker compose exec patrimoine-service ./vendor/bin/pint
docker compose exec operations-service ./vendor/bin/pint
docker compose exec web ./vendor/bin/pint

# Analyse statique (Larastan)
docker compose exec auth-service ./vendor/bin/phpstan analyse
docker compose exec patrimoine-service ./vendor/bin/phpstan analyse
docker compose exec operations-service ./vendor/bin/phpstan analyse
docker compose exec web ./vendor/bin/phpstan analyse

# Audit de sécurité
docker compose exec auth-service composer audit
docker compose exec patrimoine-service composer audit
docker compose exec operations-service composer audit
docker compose exec web composer audit
```

## Déploiement

Ce projet est configuré pour être déployé sur Render (gratuit pour les projets open-source).

### Étapes de déploiement

1. Créer un compte Render
2. Connecter le dépôt GitHub
3. Créer les services Render :
   - PostgreSQL
   - auth-service
   - patrimoine-service
   - operations-service
   - web
4. Configurer les variables d'environnement
5. Configurer les clés JWT
6. Déployer

## Statut du Projet

### Complété

- ✅ Phase A - Initialisation
- ✅ Phase B - Conception UML
- ✅ Phase C - Maquettes Figma (disponibles sur Figma)
- ✅ Phase D - Base de données
- ✅ Phase E - Socle Docker
- ✅ Phase F - Développement (backend + web de base)

### À terminer

- ⏳ Déploiement effectif sur Render (configuration effectuée, à déployer par l'utilisateur)

## Auteur

Duah Prince Yao AMANKWAAH

## Licence

Propriétaire - République Togolaise
