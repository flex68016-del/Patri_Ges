# Journal du projet

Journal chronologique des phases de réalisation de la plateforme de gestion du patrimoine de l'État. Chaque phase liste les livrables produits et les points techniques notables.

---

## Phase A — Initialisation

**Livrables :**

- Structure du dépôt Git et conventions (`.gitignore`, `.editorconfig`)
- README d'entrée du projet
- `docs/analyse-besoin.md` : analyse fonctionnelle et non fonctionnelle
- `docs/DECISIONS.md` : journal des décisions d'architecture

---

## Phase B — Conception UML

**Livrables :** 13 diagrammes Mermaid dans `docs/uml/`

- Cas d'utilisation : global, authentification, patrimoine, opérations
- Diagramme de classes du domaine métier
- Diagrammes de séquence : connexion, affectation d'un bien, clôture de maintenance, clôture de campagne d'inventaire
- Diagramme d'états : cycle de vie d'un bien
- Diagramme d'activités : cycle de vie complet
- Diagrammes de composants et de déploiement
- Versions PNG pour la présentation (`docs/uml/png/`)

---

## Phase C — Design de l'interface

**Livrables :**

- Tokens de design : palette institutionnelle (vert `#2E7D32` / `#047857`), typographie Inter, espacements, rayons, ombres, transitions 150–300 ms
- Maquettes des 14 écrans : connexion, tableau de bord, liste et fiche de bien, création/édition, affectation, maintenance, inventaire (constats, rapport), anomalies, administration, déclinaisons mobiles
- Captures d'écran dans `docs/design/Screenshots/`
- Lien Figma : https://www.figma.com/design/n0Ykk3lKV5zLpNfeRheqxe/Maquette-Patrimoine-Etat?node-id=0-1

---

## Phase D — Modélisation des données

**Livrables :** `docs/database/`

- MCD et MLD des trois services (auth, patrimoine, opérations)
- Documentation de normalisation (1FN, 2FN, 3FN) et des redondances volontaires
- Plan d'index et index.md récapitulatif
- Script d'initialisation `db/init/01-init-databases.sql`

---

## Phase E — Infrastructure Docker

**Livrables :**

- `docker-compose.yml` : PostgreSQL 16, 3 services métier, service web, gateway Nginx
- Dockerfiles multi-étapes (PHP 8.4 Alpine) pour chaque service
- Configuration Nginx (gateway + services) et scripts d'entrée
- Healthchecks, réseau interne, `depends_on` conditionnés à la santé
- `.env.example` et Makefile

---

## Phase F — Développement des services

### auth-service (:8001)

- Laravel 12, `firebase/php-jwt`
- Tables `users` et `connexions` (UUID, journal des connexions)
- Authentification JWT RS256 : `POST /api/login`, `/logout`, `/me`
- Gestion des utilisateurs : `GET/POST /api/users` (administrateur)
- Middleware JWT (vérification signature + statut `actif`), middleware de rôle

### patrimoine-service (:8002)

- 9 migrations : services, catégories, biens, biens_immobiliers, biens_vehicules, biens_equipements, affectations, mouvements, cessions
- 9 modèles Eloquent avec relations et calcul de la valeur nette comptable (amortissement linéaire)
- API complète : CRUD biens, affectation, réforme, cession, historique, référentiels services/catégories
- Transactions DB sur les opérations multi-tables

### operations-service (:8003)

- 5 migrations : maintenances, campagnes_inventaire, lignes_inventaire, anomalies, journaux_audit
- API : maintenances (planification, clôture), campagnes d'inventaire (création, lignes/constats, clôture), anomalies (signalement, résolution)

### web (:8000)

- Laravel 12, Blade + Alpine.js + Tailwind CSS
- Client HTTP interne `ApiClient` : JWT conservé en session serveur, transmis aux API
- Middleware `authenticated`, `admin`, `role` alignés sur les permissions API
- 14 pages : connexion, tableau de bord, biens (liste, fiche, création, édition), affectation, réforme, cession, maintenances, inventaires (campagnes, constats, rapport, clôture), anomalies, utilisateurs, référentiels

---

## Phase G — Tests et validation

**Livrables :** `docs/TESTS.md`

- Campagne fonctionnelle complète via l'interface et les API
- Tests d'autorisation sur les 4 rôles (401/403/201 conformes)
- Tests de validation (doublons, champs requis, enums, UUID, dates)
- Tests non fonctionnels : healthchecks, rate limiting, sessions
- Base de démonstration alimentée via l'interface (biens, maintenance, campagne d'inventaire, anomalies, référentiels, utilisateur)
- `composer audit` : 0 vulnérabilité

**Anomalies corrigées pendant les tests :**

- `artisan serve` filtrait les variables d'environnement Docker → option `--no-reload`
- `WORKDIR /app` manquant dans les Dockerfiles (chemins de stockage erronés)
- Guard `api` inexistant → middleware `jwt` dédié ; `Request::setUser()` → `setUserResolver()`
- Colonne `actif` testée au lieu de `statut`
- Middleware `role` variadique pour `role:a,b`
- Contrôleurs alignés sur les schémas réels des migrations
- Noms de tables `biens_*` explicites dans les modèles + génération UUID
- Ordre des routes (`/biens/create` avant `/biens/{id}`)
- Déconnexion en POST + CSRF (liens GET remplacés par formulaires)
- Middleware de rôle web + masquage des liens selon le profil

---

## Phase H — Qualité et sécurité

**Livrables :**

- `docs/CODE_REVIEW.md` : revue de l'architecture et de la qualité
- `docs/SECURITY_REVIEW.md` : revue OWASP (JWT RS256, RBAC, CSRF, validation, secrets)
- Durcissement : rate limiting, CSRF complet, sessions invalidées au logout

---

## Phase I — Déploiement

**Livrables :**

- `docs/DEPLOIEMENT.md` : procédure générique multi-hébergeurs Docker
- `docs/DEPLOIEMENT_RENDER.md` : variante Render
- Dockerfiles adaptés au port dynamique `$PORT` et à l'injection des clés JWT par variables d'environnement
- Base PostgreSQL Neon provisionnée (projet `patrimoine-gouv`, 3 bases)
- Déploiement effectif : en attente du choix d'un hébergeur (voir `docs/DECISIONS.md`)

---

## Phase J — Documentation de livraison

**Livrables :**

- `docs/RAPPORT_SOUTENANCE.md` : rapport formel complet
- `docs/API.md` : référence complète des endpoints
- `docs/FICHE_TECHNIQUE.md`, `docs/GUIDE_UTILISATEUR.md`, `docs/PROPOSITION_FINANCIERE.md`
- README central mis à jour

---

## Phase K — Vérification finale

- 13 pages web vérifiées (HTTP 200) pour l'administrateur
- Parcours complet testés de bout en bout : formulaire → API → base → affichage
- Restrictions de rôle vérifiées pour inspecteur et gestionnaire
- Dépôt synchronisé sur GitHub (`main`)
