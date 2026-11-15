# Revue de code

Revue réalisée en novembre 2026 après la phase de développement et les campagnes de tests.

## 1. Architecture et organisation

- **4 services Laravel autonomes** + gateway Nginx : frontières de domaine claires (auth / patrimoine / opérations / présentation) ;
- **Service `web` sans état** : pas de base propre, consomme les API via `App\Services\ApiClient` (client HTTP unique, token JWT injecté depuis la session) ;
- **Contrôleurs fins** : validation → appel service → réponse ; la logique métier reste dans les modèles/transactions des API ;
- **Middleware nommés** : `jwt`, `role`, `authenticated`, `admin`, `role` (web) — alias déclarés dans `bootstrap/app.php` de chaque service.

## 2. Points forts

- Cohérence schéma ↔ code : modèles alignés sur les migrations réelles (noms de tables, enums, UUID) ;
- Transactions sur les opérations multi-tables (affectation, clôtures) ;
- Journalisation des mouvements sur chaque transition de statut ;
- Vues Blade homogènes : layout unique, composants de formulaires/tableaux cohérents, échappement automatique ;
- Gestion d'erreurs explicite : `withErrors` + `withInput` côté web, codes HTTP + JSON côté API ;
- Aucune secret dans le code ; configuration 100 % par variables d'environnement.

## 3. Dette technique identifiée

| Élément | Impact | Recommandation |
|---------|--------|----------------|
| Pas de tests automatisés (PHPUnit) | Régressions possibles | Ajouter tests unitaires/feature par service |
| Pagination fixe (20) | UX limitée sur gros volumes | Paramètre `per_page` + sélecteur |
| Doublons de middleware `jwt`/`role` entre services | Maintenance | Extraire en package partagé (optionnel) |
| `docker-compose.yml` : attribut `version` obsolète | Avertissement | Supprimer la clé `version` |
| `CHANGELOG`/versioning absent | Traçabilité | Adopter conventional commits + tags |
| Données de démo dans les seeders | Mélange démo/prod | Séparer `DatabaseSeeder` démo |

## 4. Corrections majeures apportées pendant les tests

- `WORKDIR /app` manquant dans les Dockerfiles API (chemins `/storage` erronés) ;
- `artisan serve --no-reload` pour ne pas filtrer les variables d'environnement Docker ;
- `JwtMiddleware` : `statut` au lieu de `actif`, `setUserResolver()` au lieu de `setUser()` ;
- Middleware `role` variadique pour supporter `role:a,b` ;
- Alignement des contrôleurs sur les schémas réels (dates, statuts, enums) ;
- `$table` + génération UUID sur `BienImmobilier/Vehicule/Equipement` ;
- Ordre des routes (`/biens/create` avant `/biens/{id}`) ;
- Formulaires POST pour le logout (CSRF conservé).

## 5. Verdict

Code propre, conventions Laravel respectées, sécurité sérieuse. La principale marge de progression est la **couverture de tests automatisés** et la paramétrisation de la pagination.
