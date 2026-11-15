# Plan et résultats des tests

Ce document récapitule les tests fonctionnels et de sécurité exécutés sur la plateforme (novembre 2026), via l'interface web et les API.

## 1. Environnement de test

- Docker Compose local : `web` (:8000), `auth-service` (:8001), `patrimoine-service` (:8002), `operations-service` (:8003), `postgres` (:5432), `gateway` (:80)
- Comptes : `admin@`, `gestionnaire@`, `responsable@`, `inspecteur@patrimoine.gouv` (mot de passe `password123`)

## 2. Tests d'authentification

| # | Test | Résultat |
|---|------|----------|
| A1 | GET `/login` affiche le formulaire | ✅ |
| A2 | POST `/login` identifiants valides → JWT + session, redirect `/dashboard` | ✅ |
| A3 | POST `/login` identifiants invalides → message d'erreur, pas de session | ✅ |
| A4 | POST `/logout` → session invalidée, token CSRF régénéré, redirect `/login` | ✅ |
| A5 | GET `/logout` → 405 (route POST uniquement, pas de contournement) | ✅ |
| A6 | Accès `/dashboard` sans session → redirect `/login` | ✅ |
| A7 | `GET /api/me` avec JWT valide → utilisateur courant | ✅ |
| A8 | `GET /api/me` sans token → 401 | ✅ |
| A9 | JWT d'un utilisateur `inactif` → rejeté | ✅ (middleware vérifie `statut`) |
| A10 | Token expiré → rejeté | ✅ |

## 3. Tests fonctionnels — interface web

| # | Fonctionnalité | Étapes | Résultat |
|---|----------------|--------|----------|
| F1 | Tableau de bord | Login admin → `/dashboard` | ✅ Compteurs réels (biens, maintenances, campagnes, anomalies) + derniers éléments |
| F2 | Liste des biens | `/biens` | ✅ Données API, recherche par texte, pagination |
| F3 | Création d'un bien | `/biens/create` → POST | ✅ Bien `WEB-TEST-001` créé, visible liste + API + fiche |
| F4 | Fiche bien | `/biens/{id}` | ✅ Informations, VNC calculée, historique des mouvements |
| F5 | Édition d'un bien | `/biens/{id}/edit` → POST | ✅ Désignation, statut, service, emplacement modifiés |
| F6 | Affectation | Fiche bien → service + date | ✅ Statut → `affecte`, mouvement créé |
| F7 | Réforme (admin) | Fiche bien → motif | ✅ Statut → `reforme` (bien `REFORM-TEST`) |
| F8 | Cession (admin) | Fiche bien → type/date/valeur | ✅ Statut → `cede`, `actif=false` (bien `CESSION-TEST`) |
| F9 | Création maintenance | `/maintenances/create` | ✅ Maintenance planifiée créée |
| F10 | Clôture maintenance | `/maintenances/{id}/cloturer` → date + coût | ✅ Statut → `termine` |
| F11 | Campagne inventaire | `/inventaires/create` | ✅ Campagne créée avec inspecteur |
| F12 | Constats | `/inventaires/{id}/constats` → formulaire | ✅ Ligne `present` enregistrée |
| F13 | Rapport écarts | `/inventaires/{id}/rapport` | ✅ Compteurs calculés sur les vraies lignes |
| F14 | Clôture campagne | Bouton Clôturer | ✅ Statut → `termine` |
| F15 | Anomalies | `/anomalies` : liste, filtres, création, résolution | ✅ Anomalie créée puis `resolu` |
| F16 | Utilisateurs | `/admin/users` + création | ✅ Utilisateur `test.interface@` créé |
| F17 | Services | `/admin/references` + création | ✅ Service `TEST-SRV` créé |
| F18 | Catégories | `/admin/references` + création | ✅ Catégorie `TEST-CAT` créée |
| F19 | Sidebar desktop/mobile | Navigation, liens conditionnels par rôle | ✅ |

## 4. Tests d'autorisation (rôles)

| # | Scénario | Attendu | Résultat |
|---|----------|---------|----------|
| R1 | Gestionnaire → `POST /api/services` | 403 (admin only) | ✅ |
| R2 | Gestionnaire → `POST /api/biens` | 201 | ✅ |
| R3 | Inspecteur → `POST /api/anomalies` | 201 | ✅ |
| R4 | Inspecteur → `GET /api/users` | 403 | ✅ |
| R5 | Sans token → API | 401 | ✅ |
| R6 | Inspecteur web → `/admin/users` | 403 | ✅ |
| R7 | Inspecteur web → `/biens/create`, `/maintenances/create`, `/inventaires/create` | 403 | ✅ |
| R8 | Inspecteur web → `/anomalies/create` | 200 | ✅ |
| R9 | Non-admin → liens Utilisateurs/Référentiels sidebar | masqués | ✅ |
| R10 | `role:a,b` multi-rôles | les deux rôles acceptés | ✅ (middleware variadique) |

## 5. Tests de validation

| # | Test | Résultat |
|---|------|----------|
| V1 | Code bien en doublon → erreur "already been taken" affichée | ✅ |
| V2 | Champs requis manquants → 422 JSON (API) / retour formulaire (web) | ✅ |
| V3 | `type_bien` catégorie invalide → rejeté | ✅ |
| V4 | UUID invalide → rejeté | ✅ |
| V5 | `date_fin` < `date_debut` campagne → rejeté | ✅ |
| V6 | Anomalie sans `statut`/`date_constatation` → 422 | ✅ |

## 6. Tests non fonctionnels

| # | Test | Résultat |
|---|------|----------|
| N1 | Healthchecks `/api/health` ×3 services | ✅ `{"status":"ok"}` |
| N2 | Cohérence web ↔ API (données saisies via interface visibles dans l'API) | ✅ |
| N3 | Journalisation des connexions (`derniere_connexion_at`) | ✅ |
| N4 | Rate limiting login (5/min API, throttle 100/min routes) | ✅ (429 observé en test intensif) |
| N5 | Sessions : invalidation complète au logout | ✅ |

## 7. Anomalies corrigées pendant les tests

| Problème | Cause | Correction |
|----------|-------|------------|
| APIs ignoraient les variables Docker | `artisan serve` filtrait l'environnement | `--no-reload` ajouté |
| Chemins `/storage` erronés | Dockerfiles sans `WORKDIR /app` | WORKDIR ajouté |
| `Auth guard [api] is not defined` | Guard inexistant | Middleware `jwt` dédié |
| `Request::setUser()` inexistant | API Laravel 12 | `setUserResolver()` |
| Token rejeté (`actif`) | Colonne `statut` | Test sur `statut` |
| `role:a,b` incomplet | Params middleware | Middleware variadique |
| Erreurs SQL (schémas divergents) | Contrôleurs sur ancien schéma | Alignés sur les migrations |
| Tables `bien_*` introuvables | Noms devinés par Eloquent | `$table` explicite + UUID |
| `/biens/create` → fiche bien | Ordre des routes | Route littérale avant `{id}` |
| GET `/logout` → 405 | Lien au lieu de formulaire | Formulaires POST `@csrf` |

## 8. Données de test insérées (via l'interface)

- Biens : `WEB-TEST-001`, `REFORM-TEST`, `CESSION-TEST`, `GES-001`
- Service : `TEST-SRV` — Catégorie : `TEST-CAT`
- Utilisateur : `test.interface@patrimoine.gouv` (gestionnaire)
- Maintenance : préventive planifiée puis clôturée
- Campagne inventaire : « Inventaire test interface » + constat `present`
- Anomalies : dégradation (créée via web), anomalie inspecteur (API)
