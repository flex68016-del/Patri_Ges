# Rapport de Projet — Gestion du Patrimoine de l'État

**Solution numérique de gestion du patrimoine de l'État**

| | |
|---|---|
| **Réalisé par** | Duah Prince Yao AMANKWAAH |
| **Mission** | 10 – 15 novembre 2026 |
| **Cadre** | République Togolaise |
| **Technologies** | Laravel 12, PHP 8.4, PostgreSQL 16, Docker, JWT RS256 |

---

## Résumé

Ce rapport présente la conception et la réalisation d'une plateforme numérique de gestion du patrimoine de l'État togolais. La solution couvre l'ensemble du cycle de vie des biens publics — acquisition, affectation, maintenance, inventaire, réforme et cession — à travers une architecture microservices conteneurisée, une interface web responsive et une sécurité d'accès par jetons JWT et contrôle des rôles.

**Mots-clés :** gestion du patrimoine, microservices, Laravel, PostgreSQL, JWT, inventaire, maintenance.

---

## Abstract

This report presents the design and implementation of a digital platform for managing the State's public assets in Togo. The solution covers the full life cycle of public property — acquisition, assignment, maintenance, inventory, reform and disposal — through a containerized microservices architecture, a responsive web interface and access security based on JWT tokens and role-based access control.

**Keywords:** asset management, microservices, Laravel, PostgreSQL, JWT, inventory, maintenance.

---

# Introduction

## Contexte

L'État togolais possède un patrimoine mobilier et immobilier considérable : bâtiments administratifs, terrains, véhicules, matériel informatique, équipements de santé et de sécurité. Or, dans la majorité des administrations, ce patrimoine reste suivi par des registres papier et des tableurs dispersés. Conséquences observées :

- impossibilité de localiser rapidement un bien ;
- affectations non tracées : personne ne sait « qui détient quoi, depuis quand » ;
- maintenances oubliées, biens hors service durablement ;
- inventaires longs et erreurs découvertes tardivement ;
- valeur comptable non suivie (amortissements) ;
- risque de pertes, de doublons et de contentieux.

## Problématique

> Comment doter l'État d'un outil numérique unique, fiable et traçable pour inventorier, affecter, entretenir et valoriser son patrimoine ?

## Objectifs du projet

1. Constituer un **référentiel unique** des biens de l'État ;
2. **Tracer le cycle de vie** de chaque bien (mouvements horodatés) ;
3. **Responsabiliser les services** détenteurs ;
4. **Faciliter les inventaires** et la détection d'écarts ;
5. **Suivre la valeur** des biens (amortissement linéaire) ;
6. Fournir des **indicateurs de pilotage** (tableaux de bord).

## Méthodologie

Démarche itérative en phases : analyse du besoin → modélisation UML et bases de données → conception des maquettes → développement par services → tests → documentation. Chaque phase a produit des livrables vérifiables (diagrammes, schémas, code, tests).

## Plan du rapport

1. Analyse du besoin et conception
2. Architecture et modélisation
3. Réalisation technique
4. Tests et validation
5. Déploiement et exploitation
6. Limites et perspectives

---

# Chapitre 1 — Analyse du besoin et conception

## 1.1 Acteurs et rôles

Quatre profils d'utilisateurs, traduits en contrôle d'accès par rôle (RBAC) :

| Rôle | Mission | Droits |
|------|---------|--------|
| **Administrateur** | Exploitation de la plateforme | Utilisateurs, référentiels, réforme, cession |
| **Gestionnaire du patrimoine** | Gestion centralisée | Biens : création, modification, affectation, maintenance, campagnes, anomalies |
| **Responsable de service** | Pilotage de son service | Consultation des biens, signalement d'anomalies, inventaire |
| **Inspecteur / auditeur** | Contrôle | Lecture globale, constats d'inventaire, anomalies |

## 1.2 Besoins fonctionnels

- Authentification sécurisée et gestion des comptes ;
- Référentiels : services et catégories de biens ;
- Fiches biens : caractéristiques (immeuble, véhicule, équipement), valeur d'acquisition, localisation ;
- Affectations entre services avec dates et motifs ;
- Valorisation : amortissement linéaire → valeur nette comptable ;
- Maintenances préventives/correctives avec suivi de coût ;
- Campagnes d'inventaire : constats (présent / absent / dégradé), rapport d'écarts ;
- Anomalies : signalement, gravité, cycle de résolution ;
- Réforme et cession (actes administratifs) ;
- Historique complet des mouvements de chaque bien ;
- Tableau de bord : indicateurs et dernières activités.

## 1.3 Besoins non fonctionnels

- **Sécurité** : mots de passe hachés, JWT RS256, RBAC, CSRF, rate limiting ;
- **Traçabilité** : journal des mouvements et des connexions ;
- **Disponibilité** : conteneurisation, healthchecks, redémarrage automatique ;
- **Maintenabilité** : séparation des domaines en microservices ;
- **Ergonomie** : interface responsive (desktop + mobile), en français.

## 1.4 Conception UML

La modélisation complète est disponible dans `docs/uml/` :

- **Cas d'utilisation** : global + un par service (auth, patrimoine, opérations) ;
- **Diagramme de classes** : domaine métier (biens et spécialisations, affectations, mouvements, cessions, maintenances, campagnes, anomalies) ;
- **Diagrammes de séquence** : connexion, affectation, clôture de maintenance, clôture d'inventaire ;
- **Diagramme d'états** : cycle de vie d'un bien (`disponible → affecte → en_maintenance → reforme/cede`) ;
- **Diagramme d'activités** : cycle de vie complet ;
- **Diagrammes de composants et de déploiement** : architecture microservices.

## 1.5 Modélisation des données

Trois bases PostgreSQL, une par service (isolation des contextes bornés) :

- `auth_db` : utilisateurs, rôles, statuts ;
- `patrimoine_db` : services, catégories, biens (+ tables spécialisées `biens_immobiliers`, `biens_vehicules`, `biens_equipements`), affectations, mouvements, cessions ;
- `operations_db` : maintenances, campagnes d'inventaire, lignes d'inventaire, anomalies.

Détails : MCD, MLD et règles de normalisation dans `docs/database/`.

## 1.6 Maquettes

Quatorze écrans ont été maquettés puis implémentés : connexion, tableau de bord, liste et fiche de bien, création/édition, affectation, maintenance, inventaire (constats, rapport), anomalies, administration (utilisateurs, services, catégories), versions mobiles. Captures dans `docs/design/Screenshots/`.

---

# Chapitre 2 — Architecture et modélisation technique

## 2.1 Architecture microservices

```
┌────────────┐      ┌──────────────┐      ┌─────────────────┐
│   Navigateur│─────▶│   Gateway    │─────▶│   web (:8000)   │
│  (session) │      │  Nginx :80   │      │  Blade + Alpine │
└────────────┘      └──────────────┘      └────────┬────────┘
                                                   │ HTTPS interne
                          ┌────────────────────────┼────────────────────────┐
                          ▼                        ▼                        ▼
                   ┌─────────────┐        ┌────────────────┐       ┌──────────────────┐
                   │ auth-service│        │patrimoine-service│     │operations-service│
                   │  (:8001)    │        │    (:8002)      │     │     (:8003)      │
                   └──────┬──────┘        └───────┬────────┘       └────────┬─────────┘
                          │                       │                         │
                   ┌──────▼──────┐        ┌───────▼────────┐       ┌────────▼─────────┐
                   │  auth_db    │        │ patrimoine_db  │       │  operations_db   │
                   └─────────────┘        └────────────────┘       └──────────────────┘
```

**Justification :** chaque domaine métier (identité, patrimoine, opérations) possède son propre service, sa base et ses règles. Le service `web` est un **Backend-for-Frontend** : il n'expose pas de base et délègue l'authentification et les données aux API. Cette séparation permet de faire évoluer ou déployer chaque service indépendamment.

## 2.2 Sécurité d'accès

- **JWT RS256** : la clé privée ne quitte jamais `auth-service` ; les autres services vérifient la signature avec la clé publique ;
- **Validation utilisateur** : chaque service interroge `GET /api/me` pour confirmer que le compte est `actif` ;
- **RBAC** : middleware `role` par endpoint (ex. réforme/cession → administrateur) ;
- **Mots de passe** : hachés `bcrypt` ;
- **Web** : sessions serveur, CSRF sur tous les formulaires, rate limiting.

## 2.3 Choix technologiques

| Couche | Choix | Justification |
|--------|-------|---------------|
| Framework | Laravel 12 (PHP 8.4) | Productivité, écosystème, ORM, validation |
| Base de données | PostgreSQL 16 | Robuste, types UUID, JSON, licences libres |
| Auth | JWT RS256 maison | Stateless, inter-services sans session partagée |
| Frontend | Blade + Alpine + Tailwind | Rendu serveur (SEO/rapidité), interactivité ciblée |
| Conteneurisation | Docker Compose | Parité dev/prod, isolation des services |
| Serveur | Nginx | Gateway unique, routage, fichiers statiques |

---

# Chapitre 3 — Réalisation

## 3.1 Fonctionnalités livrées

**Gestion des biens** : création (code, désignation, catégorie, service, date, valeur, localisation), fiche détaillée avec valeur nette comptable, édition, recherche/filtres.

**Cycle de vie** : affectation à un service (clôture de l'affectation précédente, statut `affecte`), maintenance (planification → clôture avec coût), réforme et cession (administrateur, traçabilité des actes).

**Inventaires** : campagnes avec période et inspecteur, constats par bien (`présent` / `absent` / `dégradé` + observation), rapport d'écarts calculé, clôture.

**Anomalies** : signalement typé (disparition, dégradation, écart d'inventaire, autre) avec gravité, cycle `signale → en_cours → résolu → clos`.

**Administration** : comptes utilisateurs (4 rôles), référentiels services/catégories.

**Pilotage** : tableau de bord (totaux par domaine, derniers biens et anomalies), historique des mouvements par bien.

## 3.2 Points techniques notables

- **Transaction + saga simplifiée** : affectation et clôtures mettent à jour plusieurs tables de façon cohérente ;
- **Spécialisation des biens** : tables `biens_immobiliers/vehicules/equipements` en 1:1 selon `type_bien` de la catégorie ;
- **Journal automatique** : chaque création produit un `Mouvement` horodaté (création, affectation, transfert, maintenance, réforme, cession) ;
- **Pagination et filtres** sur toutes les listes ;
- **Validation centralisée** : règles identiques côté API (source de vérité) et côté formulaires web.

## 3.3 Qualité du code

- Conventions Laravel (contrôleurs fins, modèles Eloquent, middleware nommés) ;
- Revue de code et revue de sécurité documentées (`docs/CODE_REVIEW.md`, `docs/SECURITY_REVIEW.md`) ;
- Scripts de qualité : Pint (formatage), Larastan (analyse statique), `composer audit`.

---

# Chapitre 4 — Tests et validation

Les tests ont été menés **via l'interface réelle et les API**, pas sur des mocks.

## 4.1 Résultats synthétiques

- **Authentification** : 10/10 scénarios (login, logout POST-only, session, JWT valide/invalide/expiré/inactif) ;
- **Fonctionnel web** : 19/19 parcours (CRUD biens, affectation, réforme, cession, maintenance, inventaire complet, anomalies, administration) ;
- **Autorisation** : 10/10 (403/201/401 conformes aux rôles, routes admin protégées, liens masqués) ;
- **Validation** : 6/6 (doublons, champs requis, enums, UUID, dates) ;
- **Non fonctionnel** : healthchecks, cohérence web↔API, rate limiting, invalidation de session.

Détail complet : `docs/TESTS.md`.

## 4.2 Données de démonstration

Une base de démonstration a été alimentée **exclusivement via l'interface** : 4 biens de test (dont un réformé et un cédé), 1 service, 1 catégorie, 1 utilisateur gestionnaire, 1 maintenance clôturée, 1 campagne d'inventaire avec constat et rapport, 2 anomalies.

---

# Chapitre 5 — Déploiement et exploitation

## 5.1 Environnements

- **Développement** : `docker compose up --build` — tout le stack en local (voir README) ;
- **Production** : **SnapDeploy** — 4 conteneurs Docker (web + 3 API) avec HTTPS automatique, et **Neon** pour PostgreSQL managé.

## 5.2 Déploiement effectif

La plateforme est déployée et accessible :

| Service | URL |
|---------|-----|
| Interface web | https://patrimoine-web.containers.snapdeploy.app |
| Auth API | https://patrimoine-auth.containers.snapdeploy.app |
| Patrimoine API | https://patrimoine-patrimoine.containers.snapdeploy.app |
| Operations API | https://patrimoine-operations.containers.snapdeploy.app |

- Base Neon PostgreSQL (projet `patrimoine-gouv`, région Francfort) : `auth_db`, `patrimoine_db`, `operations_db` — migrations et données de démonstration chargées ;
- Authentification JWT RS256 avec clés injectées par variables d'environnement (jamais commitées) ;
- Redéploiement continu : chaque push sur les branches `deploy/*` déclenche un rebuild.

## 5.3 Options d'hébergement

Le stack étant conteneurisé, il est déployable sur tout hébergeur acceptant Docker. Le déploiement actuel (SnapDeploy) et la procédure générique sont documentés dans `docs/DEPLOIEMENT.md`.

## 5.3 Exploitation

- Healthchecks `GET /api/health` sur chaque service ;
- Logs applicatifs Laravel (`storage/logs`) et conteneurs Docker ;
- Sauvegardes PostgreSQL à planifier en production ;
- Rotation des clés JWT à prévoir.

---

# Chapitre 6 — Limites et perspectives

## 6.1 Limites identifiées

- **Pas de tests unitaires automatisés** : la validation est fonctionnelle/manuelle (à industrialiser avec PHPUnit/Pest) ;
- **Notifications absentes** : pas d'alertes e-mail pour maintenances en retard ou anomalies critiques ;
- **Plan gratuit SnapDeploy** : les conteneurs s'endorment après ~15 min d'inactivité (réveil ~1 min) ;
- **Pagination fixe** (20/page) sans sélecteur ;
- **Droits du responsable de service** : lecture globale plutôt que restreinte à son service (affinage possible via `service_id` dans le JWT) ;
- **Évolutions métier** : pas de workflow de validation hiérarchique des actes (réforme/cession validés par un second niveau).

## 6.2 Perspectives

- Notifications (e-mail/SMS) et rappels de maintenance ;
- Restreindre la visibilité par service pour le rôle `responsable` ;
- Circuit de validation des actes patrimoniaux ;
- Import/export (Excel/CSV) des inventaires ;
- Reporting avancé (Chart.js déjà prévu dans la stack) ;
- API publique/documentée (OpenAPI) pour des intégrations.

---

# Conclusion

Ce projet a abouti à une **plateforme complète et fonctionnelle** de gestion du patrimoine de l'État : quatre microservices Laravel, une interface web en français couvrant tout le cycle de vie des biens, une sécurité sérieuse (JWT RS256, RBAC, CSRF) et une documentation exhaustive (analyse, UML, MCD/MLD, API, tests, guides).

Au-delà des fonctionnalités, la valeur du projet réside dans la **traçabilité** : chaque bien raconte son histoire (acquisition → affectations → maintenances → réforme/cession), ce qui est précisément l'exigence première du contrôle patrimonial public. La base est saine pour les évolutions futures (notifications, workflows de validation, reporting).

---

# Annexes

## A. Documents livrés

| Document | Fichier |
|----------|---------|
| Analyse du besoin | `docs/analyse-besoin.md` |
| Fiche technique | `docs/FICHE_TECHNIQUE.md` |
| Référence API | `docs/API.md` |
| Base de données (MCD/MLD) | `docs/database/` |
| Diagrammes UML | `docs/uml/` |
| Maquettes & captures | `docs/design/` |
| Guide utilisateur | `docs/GUIDE_UTILISATEUR.md` |
| Tests | `docs/TESTS.md` |
| Déploiement | `docs/DEPLOIEMENT.md` |
| Revue sécurité / code | `docs/SECURITY_REVIEW.md`, `docs/CODE_REVIEW.md` |
| Journal de développement | `docs/JOURNAL.md` |
| Proposition financière | `docs/PROPOSITION_FINANCIQUE.md` |
| Décisions d'architecture | `docs/DECISIONS.md` |

## B. Comptes de démonstration

Voir `README.md` § Comptes de test (4 rôles, mot de passe `password123`).

## C. Commandes utiles

```bash
docker compose up --build          # démarrer
docker compose exec <service> php artisan migrate --seed
docker compose logs -f web         # suivre les logs
```

---

*Duah Prince Yao AMANKWAAH — République Togolaise, novembre 2026.*
