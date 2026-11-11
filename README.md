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

- PHP 8.2+
- Laravel 13.x
- PostgreSQL 16
- Docker
- Alpine.js
- Tailwind CSS
- Chart.js

## Lancement

```bash
docker compose up --build
```

## Documentation

- [Analyse du besoin](docs/analyse-besoin.md)
- [UML](docs/uml/)
- [Base de données](docs/database/)
- [API](docs/api/)
- [Fiche technique](docs/fiche-technique.md)
- [Proposition financière](docs/proposition-financiere.md)
- [Guide d'utilisation](docs/guide-utilisation.md)

## Auteur

Duah Prince Yao AMANKWAAH
