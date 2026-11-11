# Fiche Technique - Gestion du Patrimoine de l'État

## 1. Présentation Générale

**Nom du projet :** Gestion du Patrimoine de l'État

**Contexte :** Mission de développement d'une solution numérique de gestion du patrimoine de l'État pour la République Togolaise.

**Objectif :** Digitaliser la gestion des biens publics (bâtiments, terrains, véhicules, équipements) avec un système de suivi, d'inventaire et de maintenance.

**Auteur :** Duah Prince Yao AMANKWAAH

**Date de mission :** 10-15 Novembre 2026

---

## 2. Architecture Technique

### 2.1 Architecture Microservices

L'application adopte une architecture microservices pour la scalabilité et la maintenabilité :

- **auth-service** (port 8001) : Service d'authentification et d'autorisation
- **patrimoine-service** (port 8002) : Gestion du patrimoine (biens, catégories, services)
- **operations-service** (port 8003) : Gestion des opérations (maintenance, inventaire)
- **web** (port 8000) : Interface utilisateur web
- **gateway** (port 80) : Nginx comme point d'entrée unique

### 2.2 Stack Technologique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Backend | Laravel | 12.x |
| PHP | PHP | 8.2.12 |
| Base de données | PostgreSQL | 16 |
| Frontend | Blade, Alpine.js, Tailwind CSS | - |
| Graphiques | Chart.js | 4.4.0 |
| Authentification | JWT (RS256) | firebase/php-jwt |
| Conteneurisation | Docker, Docker Compose | - |
| Serveur web | Nginx | - |
| Gateway | Nginx | - |

### 2.3 Infrastructure

- **Conteneurisation** : Docker Compose pour le développement local
- **Déploiement** : Render (hébergement gratuit)
- **Base de données** : PostgreSQL 16 avec schemas séparés par service
- **Réseau** : Réseau interne Docker pour la communication inter-services

---

## 3. Fonctionnalités

### 3.1 Authentification

- Connexion avec email et mot de passe
- Génération de tokens JWT RS256
- Journalisation des connexions
- Gestion des rôles (administrateur, gestionnaire, responsable, inspecteur)
- Déconnexion avec journalisation

### 3.2 Gestion du Patrimoine

- **Référentiels** : Services, catégories de biens
- **Biens** : CRUD complet avec les informations suivantes :
  - Code inventaire
  - Désignation
  - Catégorie (véhicule, immobilier, équipement)
  - Date et mode d'acquisition
  - Valeur d'acquisition
  - État
  - Service actuel
- **Affectations** : Historique des affectations de biens aux services
- **Valorisation** : Calcul automatique de la valeur net comptable (VNC)
- **Réforme** : Processus de réforme des biens
- **Cession** : Processus de cession des biens

### 3.3 Maintenance

- **Maintenances préventives** : Planification et suivi
- **Maintenances correctives** : Réparation et suivi
- **Clôture** : Validation et coût réel
- **Historique** : Traçabilité complète

### 3.4 Inventaire

- **Campagnes d'inventaire** : Création et gestion
- **Lignes d'inventaire** : Saisie des résultats
- **Clôture** : Validation finale
- **Anomalies** : Signalement et résolution

### 3.5 Interface Web

- **Tableau de bord** : KPI et graphiques
- **Liste des biens** : Avec filtres et recherche
- **Fiche bien** : Détails avec onglets (général, technique, affectations, maintenance)
- **Formulaires** : Création et modification
- **Gestion des maintenances** : Liste et actions
- **Gestion des inventaires** : Liste et actions

---

## 4. Base de Données

### 4.1 Structure

3 bases de données PostgreSQL séparées :

- **auth_db** : users, connexions
- **patrimoine_db** : services, categories, biens, biens_immobiliers, biens_vehicules, biens_equipements, affectations, mouvements, cessions
- **operations_db** : maintenances, campagnes_inventaire, lignes_inventaire, anomalies, journaux_audit

### 4.2 Caractéristiques

- Identifiants UUID pour toutes les tables
- Index optimisés pour les requêtes fréquentes
- Normalisation 3FN
- Redondances volontaires documentées

### 4.3 Migration

Script d'initialisation : `db/init/01-init-databases.sql`

---

## 5. Sécurité

### 5.1 Authentification

- Tokens JWT RS256 avec clés RSA
- Stockage sécurisé des clés (non commitées)
- Expiration configurable des tokens

### 5.2 Autorisation

- Rôles basés sur le profil utilisateur
- Middleware de vérification JWT

### 5.3 Validation

- FormRequests Laravel pour la validation des entrées
- Sanitization des données utilisateur
- Protection CSRF activée

### 5.4 Audit

- Journal d'audit dans operations-service
- Traçabilité des actions sensibles

---

## 6. API REST

### 6.1 Auth Service

- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion
- `GET /api/me` - Profil utilisateur
- `GET /api/health` - Healthcheck

### 6.2 Patrimoine Service

- `GET /api/services` - Liste des services
- `GET /api/categories` - Liste des catégories
- `GET /api/biens` - Liste des biens (avec filtres)
- `POST /api/biens` - Créer un bien
- `PUT /api/biens/{id}` - Modifier un bien
- `POST /api/biens/{id}/affecter` - Affecter un bien
- `POST /api/biens/{id}/reformer` - Réformer un bien
- `POST /api/biens/{id}/ceder` - Céder un bien
- `GET /api/biens/{id}/historique` - Historique des mouvements

### 6.3 Operations Service

- `GET /api/maintenances` - Liste des maintenances
- `POST /api/maintenances` - Créer une maintenance
- `POST /api/maintenances/{id}/cloturer` - Clôturer une maintenance
- `GET /api/campagnes-inventaire` - Liste des campagnes
- `POST /api/campagnes-inventaire` - Créer une campagne
- `POST /api/campagnes-inventaire/{id}/cloturer` - Clôturer une campagne
- `GET /api/anomalies` - Liste des anomalies
- `POST /api/anomalies` - Signaler une anomalie
- `POST /api/anomalies/{id}/resoudre` - Résoudre une anomalie

---

## 7. Tests

### 7.1 Tests Unitaires

- **auth-service** : 13 tests, 31 assertions
- **patrimoine-service** : 11 tests, 17 assertions
- **operations-service** : 13 tests, 17 assertions

### 7.2 Couverture

- Tests des modèles Eloquent
- Tests des relations
- Tests des calculateurs (VNC)
- Tests des UUID

### 7.3 Sécurité

- Composer audit : 0 vulnérabilités détectées

---

## 8. Déploiement

### 8.1 Développement Local

```bash
# Cloner le dépôt
git clone <url>
cd Patrimoine_Gouv

# Générer les clés JWT
scripts\generate-jwt-keys.bat

# Configurer l'environnement
cp .env.example .env

# Démarrer les services
docker compose up --build

# Exécuter les migrations
docker compose exec auth-service php artisan migrate
docker compose exec patrimoine-service php artisan migrate
docker compose exec operations-service php artisan migrate

# Exécuter les seeders
docker compose exec auth-service php artisan db:seed
docker compose exec patrimoine-service php artisan db:seed
docker compose exec operations-service php artisan db:seed
```

### 8.2 Déploiement sur Render

- Configuration des services Render
- Variables d'environnement
- Clés JWT encodées en base64
- Base de données PostgreSQL gérée par Render

---

## 9. Documentation

- **UML** : 11 diagrammes Mermaid (cas d'utilisation, classes, séquences, états, activités, composants, déploiement)
- **Base de données** : MCD, MLD, normalisation, plan d'index
- **Design** : Tokens de design, description des écrans
- **API** : Documentation des endpoints REST
- **Journal** : Historique de développement

---

## 10. Maintenance

### 10.1 Logs

- Logs Laravel dans `storage/logs/`
- Logs Nginx dans les conteneurs
- Journal d'audit dans la base de données

### 10.2 Sauvegardes

- Sauvegardes automatiques PostgreSQL sur Render
- Script de sauvegarde manuel disponible

### 10.3 Mises à jour

- Dépendances Composer lockées
- Processus de mise à jour documenté

---

## 11. Performances

### 11.1 Optimisations

- Index sur les colonnes fréquemment interrogées
- Requêtes paginées
- Cache configurable

### 11.2 Scalabilité

- Architecture microservices
- Conteneurisation Docker
- Load balancing possible via le gateway

---

## 12. Points d'Attention

1. **Clés JWT** : Ne jamais commiter les clés privées
2. **Variables d'environnement** : Configurer correctement en production
3. **PHP 8.2** : Laravel 12 nécessite PHP 8.2 (non compatible avec PHP 8.3 pour Pint)
4. **Migrations** : Exécuter les migrations dans l'ordre
5. **Seeders** : Exécuter les seeders après les migrations

---

## 13. Conclusion

La solution technique est complète et opérationnelle avec :
- Architecture microservices moderne
- Sécurité JWT RS256
- Interface web réactive
- Tests unitaires couvrants
- Documentation complète
- Infrastructure Docker prête au déploiement

Le projet est prêt pour la mise en production sur Render après configuration des variables d'environnement et des clés JWT.
