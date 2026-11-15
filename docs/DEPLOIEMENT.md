# Guide de déploiement

Le projet est déployé en production sur **SnapDeploy** (conteneurs Docker gratuits) avec **Neon** pour PostgreSQL. Ce document décrit le déploiement effectif et la procédure générique.

## 0. Déploiement actuel (production)

| Service | URL | Branche de déploiement |
|---------|-----|------------------------|
| Interface web | https://patrimoine-web.containers.snapdeploy.app | `deploy/web` |
| Auth API | https://patrimoine-auth.containers.snapdeploy.app | `deploy/auth-service` |
| Patrimoine API | https://patrimoine-patrimoine.containers.snapdeploy.app | `deploy/patrimoine-service` |
| Operations API | https://patrimoine-operations.containers.snapdeploy.app | `deploy/operations-service` |
| Base de données | Neon PostgreSQL (projet `patrimoine-gouv`, Francfort) | — |

**Caractéristiques :**

- 4 conteneurs `small` (512 MB) — limite du plan gratuit SnapDeploy
- HTTPS automatique sur `*.containers.snapdeploy.app`
- Sleep après ~15 min d'inactivité, réveil en ~1 min à la première visite
- Base Neon : `auth_db`, `patrimoine_db`, `operations_db` (migrations + seeders exécutés)
- Déploiement continu : chaque push sur une branche `deploy/*` déclenche un rebuild

**Variables d'environnement** sont configurées dans le dashboard SnapDeploy (clés JWT en base64, `APP_KEY`, `DB_*` Neon avec `DB_SSLMODE=require`, URLs inter-services).

## 1. Prérequis

- Un dépôt Git public/privé (GitHub)
- Un hébergeur : PaaS Docker (Render, Railway, Fly.io…), VPS, ou cloud (Oracle Cloud Always Free)
- Une base PostgreSQL accessible (managée ou conteneur)

## 2. Architecture à déployer

| Composant | Image | Variables clés |
|-----------|-------|----------------|
| web | `services/web/Dockerfile` (via Dockerfile racine sur `deploy/web`) | `AUTH_SERVICE_URL`, `PATRIMOINE_SERVICE_URL`, `OPERATIONS_SERVICE_URL`, `APP_KEY`, `SESSION_DRIVER` |
| auth-service | `services/auth-service/Dockerfile` (via `deploy/auth-service`) | `DB_*` (auth_db), `JWT_PRIVATE_KEY`, `JWT_PUBLIC_KEY`, `APP_KEY` |
| patrimoine-service | `services/patrimoine-service/Dockerfile` (via `deploy/patrimoine-service`) | `DB_*` (patrimoine_db), `AUTH_SERVICE_URL`, `JWT_PUBLIC_KEY`, `APP_KEY` |
| operations-service | `services/operations-service/Dockerfile` (via `deploy/operations-service`) | `DB_*` (operations_db), `AUTH_SERVICE_URL`, `JWT_PUBLIC_KEY`, `APP_KEY` |
| PostgreSQL | Neon managé | `DB_*` + `DB_SSLMODE=require` |

## 3. Étapes

### 3.1 Base de données

Créer PostgreSQL puis les trois bases :

```sql
CREATE DATABASE auth_db;
CREATE DATABASE patrimoine_db;
CREATE DATABASE operations_db;
```

### 3.2 Clés JWT et APP_KEY

```bash
# Générer les clés RSA
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem

# Encoder en base64 pour les variables d'environnement
base64 -w0 private.pem   # → JWT_PRIVATE_KEY
base64 -w0 public.pem    # → JWT_PUBLIC_KEY

# Clé applicative Laravel
php artisan key:generate --show
```

### 3.3 Variables d'environnement

Chaque service a besoin de :

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=<base du service>
DB_USERNAME=...
DB_PASSWORD=...
DB_SSLMODE=require        # si base managée type Neon
```

Services API en plus : `JWT_PRIVATE_KEY` (auth seulement), `JWT_PUBLIC_KEY` (les 3), `AUTH_SERVICE_URL` (patrimoine/operations).

Service web en plus : `AUTH_SERVICE_URL`, `PATRIMOINE_SERVICE_URL`, `OPERATIONS_SERVICE_URL`, `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`.

### 3.4 Déploiement

#### Sur SnapDeploy (déploiement actuel)

1. Une branche `deploy/<service>` par composant, avec un **Dockerfile à la racine** qui copie `services/<service>/` ;
2. Créer un conteneur par service dans SnapDeploy (taille `small`, 512 MB) ;
3. Lier la branche correspondante — le premier build démarre automatiquement ;
4. Renseigner les variables d'environnement (§3.3) ;
5. Vérifier `GET /api/health` sur les trois API et `GET /login` sur le web.

#### Sur un autre hébergeur Docker

1. Pousser le code sur GitHub ;
2. Créer un projet/service Docker par composant (ou un blueprint) avec comme contexte `services/<service>/` ;
3. Renseigner les variables d'environnement ;
4. Lancer les migrations : `php artisan migrate --seed` dans chaque service API ;
5. Vérifier `GET /api/health` sur les trois API et `GET /login` sur le web.

### 3.5 Points d'attention

- `php artisan serve --no-reload` est requis (les variables d'environnement Docker sont sinon filtrées) — déjà configuré dans les Dockerfiles ;
- Les services API sont **stateless** : `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` ;
- Ne jamais commiter `storage/app/jwt/*.pem` ni `.env` ;
- Le port d'écoute suit `$PORT` (injection hébergeur) ;
- Les Dockerfiles doivent créer `storage/framework/{views,cache/data,sessions}`, `storage/logs` et `bootstrap/cache` (répertoires non commités car vidés par Git) ;
- Sur SnapDeploy : pas de `rootDir` via l'API — utiliser un Dockerfile racine par branche `deploy/*`.

## 4. Options d'hébergement sans carte bancaire

| Option | Limites | Adéquation |
|--------|---------|------------|
| SnapDeploy | 4 conteneurs × 512 MB, sleep 15 min, 100 h/mois | ✅ Bonne (1 conteneur par service) |
| EthioDeploy Free | 1 projet, 256 MB, sleep 30 min | ⚠️ Nécessite un conteneur « all-in-one » |
| VPS (Oracle Always Free) | VM ARM 2 OCPU/12 GB | ✅ Idéale mais carte exigée à l'inscription |

## 5. Post-déploiement

- Tester le login avec les comptes de démonstration ;
- Vérifier qu'une création web apparaît dans l'API ;
- Mettre à jour les mots de passe de démonstration ;
- Planifier les sauvegardes PostgreSQL ;
- Activer un domaine + HTTPS.
