# Gestion du Patrimoine de l'État

Solution numérique de gestion du patrimoine de l'État (bâtiments, terrains, véhicules, équipements) — mission de développement pour la République Togolaise.

**Auteur :** Duah Prince Yao AMANKWAAH

## Architecture

Architecture **microservices** Laravel 12 + PostgreSQL, conteneurisée avec Docker :

| Service | Port | Rôle |
|---------|------|------|
| `web` | 8000 | Interface utilisateur (Blade + Alpine.js + Tailwind CSS) |
| `auth-service` | 8001 | Authentification, JWT RS256, gestion des utilisateurs |
| `patrimoine-service` | 8002 | Biens, catégories, services, affectations, réforme/cession |
| `operations-service` | 8003 | Maintenances, campagnes d'inventaire, anomalies |
| `gateway` | 80 | Nginx, point d'entrée unique |
| `postgres` | 5432 | PostgreSQL 16, une base par service |

Le service `web` est **sans base de données** : il s'authentifie auprès d'`auth-service` (JWT stocké en session) et consomme les APIs des services métier via un client HTTP interne (`ApiClient`).

## Stack

- PHP 8.4 (Docker) / Laravel 12.x
- PostgreSQL 16
- JWT RS256 (`firebase/php-jwt`)
- Blade, Alpine.js, Tailwind CSS
- Docker / Docker Compose
- Nginx (gateway + services)

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | `admin@patrimoine.gouv` | `password123` |
| Gestionnaire | `gestionnaire@patrimoine.gouv` | `password123` |
| Responsable | `responsable@patrimoine.gouv` | `password123` |
| Inspecteur | `inspecteur@patrimoine.gouv` | `password123` |

## Lancement

### Prérequis

- Docker Desktop installé
- Git

### Démarrage

```bash
git clone https://github.com/flex68016-del/Patri_Ges.git
cd Patrimoine_Gouv

# Générer les clés JWT (première fois uniquement)
scripts/generate-jwt-keys.sh        # Linux / Git Bash
scripts\\generate-jwt-keys.bat      # Windows (invite de commandes)

# Démarrer tous les services
docker compose up --build
```

## URLs

### Production (SnapDeploy)

- **Interface web** : https://patrimoine-web.containers.snapdeploy.app
- **Auth API** : https://patrimoine-auth.containers.snapdeploy.app
- **Patrimoine API** : https://patrimoine-patrimoine.containers.snapdeploy.app
- **Operations API** : https://patrimoine-operations.containers.snapdeploy.app

### Développement local

- **Interface web** : http://localhost (gateway) ou http://localhost:8000
- **Auth API** : http://localhost:8001
- **Patrimoine API** : http://localhost:8002
- **Operations API** : http://localhost:8003
- Healthchecks : `GET /api/health` sur chaque service API

### Migrations et Seeders

```bash
docker compose exec auth-service php artisan migrate --seed
docker compose exec patrimoine-service php artisan migrate --seed
docker compose exec operations-service php artisan migrate --seed
```

## Documentation

| Document | Contenu |
|----------|---------|
| [Rapport de soutenance](docs/RAPPORT_SOUTENANCE.md) | Rapport formel complet du projet |
| [Analyse du besoin](docs/analyse-besoin.md) | Contexte, acteurs, périmètre, besoins |
| [Fiche technique](docs/FICHE_TECHNIQUE.md) | Architecture et stack détaillées |
| [API](docs/API.md) | Référence complète des endpoints REST |
| [Base de données](docs/database/README.md) | MCD, MLD, normalisation (3 services) |
| [UML](docs/uml/README.md) | Diagrammes de cas d'utilisation, classes, séquences |
| [Design](docs/design/README.md) | Maquettes et captures d'écran |
| [Guide utilisateur](docs/GUIDE_UTILISATEUR.md) | Manuel d'utilisation de l'interface |
| [Tests](docs/TESTS.md) | Plan de test et résultats |
| [Décisions d'architecture](docs/DECISIONS.md) | ADR et justifications techniques |
| [Journal d'avancement](docs/JOURNAL.md) | Historique du développement |
| [Proposition financière](docs/PROPOSITION_FINANCIERE.md) | Estimation budgétaire |
| [Déploiement](docs/DEPLOIEMENT.md) | Guide de déploiement |
| [Sécurité](docs/SECURITY_REVIEW.md) | Revue de sécurité |
| [Revue de code](docs/CODE_REVIEW.md) | Revue de qualité du code |

## API Endpoints (résumé)

Authentification : `POST /api/login`, `POST /api/logout`, `GET /api/me`, `GET /api/users` (admin), `POST /api/users` (admin).

Patrimoine : `GET/POST /api/biens`, `GET/PUT /api/biens/{id}`, `POST /api/biens/{id}/affecter|reformer|ceder`, `GET /api/biens/{id}/historique`, `GET/POST /api/services`, `GET/POST /api/categories`, `GET /api/affectations`.

Opérations : `GET/POST /api/maintenances`, `POST /api/maintenances/{id}/cloturer`, `GET/POST /api/campagnes-inventaire`, `POST /api/campagnes-inventaire/{id}/cloturer`, `GET/POST /api/campagnes-inventaire/{id}/lignes`, `GET/POST /api/anomalies`, `POST /api/anomalies/{id}/resoudre`.

→ Référence complète avec rôles et exemples : [docs/API.md](docs/API.md)

## Développement

```bash
# Tests
docker compose exec auth-service php artisan test
docker compose exec patrimoine-service php artisan test
docker compose exec operations-service php artisan test
docker compose exec web php artisan test

# Qualité (Pint / Larastan / composer audit)
docker compose exec <service> ./vendor/bin/pint
docker compose exec <service> ./vendor/bin/phpstan analyse
docker compose exec <service> composer audit
```

## Statut du projet

- ✅ Analyse du besoin et conception UML
- ✅ Maquettes d'interface
- ✅ Modélisation base de données (MCD/MLD)
- ✅ Développement complet (4 services + interface web)
- ✅ Tests fonctionnels et de sécurité
- ✅ Déploiement en production : SnapDeploy + Neon PostgreSQL
- ✅ Documentation de livraison

## Auteur

Duah Prince Yao AMANKWAAH — République Togolaise

## Licence

Propriétaire — République Togolaise
