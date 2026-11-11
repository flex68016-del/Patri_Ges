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
# Générer les clés JWT
make generate-jwt-keys

# Copier et configurer .env
cp .env.example .env
# Éditer .env avec vos clés JWT

# Démarrer les services
docker compose up --build

# Installer les dépendances (dans un autre terminal)
make install-auth install-patrimoine install-operations install-web

# Exécuter les migrations
make migrate-auth migrate-patrimoine migrate-operations migrate-web

# Exécuter les seeders
make seed-auth seed-patrimoine seed-operations seed-web
```

## Documentation

- [Analyse du besoin](docs/analyse-besoin.md)
- [UML](docs/uml/README.md)
- [Design](docs/design/README.md)
- [Base de données](docs/database/README.md)
- [Décisions d'architecture](docs/DECISIONS.md)
- [Journal d'avancement](docs/JOURNAL.md)

## À faire

### Phase F - Développement (en cours)

- [ ] Initialiser les services Laravel (composer create-project)
- [ ] Créer les migrations basées sur le MLD
- [ ] Créer les modèles Eloquent
- [ ] Créer les FormRequests de validation
- [ ] Créer les services/actions (logique métier)
- [ ] Créer les contrôleurs
- [ ] Créer les API Resources
- [ ] Implémenter l'authentification JWT
- [ ] Créer les tests unitaires et d'intégration
- [ ] Développer l'interface web (Blade + Alpine + Tailwind)

### Phases restantes

- [ ] Phase G : Qualité et sécurité (Pint, Larastan, tests, composer audit)
- [ ] Phase H : Déploiement sur Render
- [ ] Phase I : Documents (fiche technique, proposition financière, guide)
- [ ] Phase J : Soutenance (plan, script, réponses)
- [ ] Phase K : Vérification finale

## Auteur

Duah Prince Yao AMANKWAAH
