# Guide de déploiement

Le projet est conteneurisé : il peut être déployé sur tout hébergeur acceptant des images Docker.

## 1. Prérequis

- Un dépôt Git public/privé (GitHub)
- Un hébergeur : PaaS Docker (Render, Railway, Fly.io…), VPS, ou cloud (Oracle Cloud Always Free)
- Une base PostgreSQL accessible (managée ou conteneur)

## 2. Architecture à déployer

| Composant | Image | Variables clés |
|-----------|-------|----------------|
| web | `services/web/Dockerfile` | `AUTH_SERVICE_URL`, `PATRIMOINE_SERVICE_URL`, `OPERATIONS_SERVICE_URL`, `APP_KEY`, `SESSION_DRIVER` |
| auth-service | `services/auth-service/Dockerfile` | `DB_*` (auth_db), `JWT_PRIVATE_KEY_B64`, `JWT_PUBLIC_KEY_B64`, `APP_KEY` |
| patrimoine-service | `services/patrimoine-service/Dockerfile` | `DB_*` (patrimoine_db), `AUTH_SERVICE_URL`, `JWT_PUBLIC_KEY_B64`, `APP_KEY` |
| operations-service | `services/operations-service/Dockerfile` | `DB_*` (operations_db), `AUTH_SERVICE_URL`, `JWT_PUBLIC_KEY_B64`, `APP_KEY` |
| PostgreSQL | `postgres:16-alpine` | `POSTGRES_PASSWORD` — 3 bases : `auth_db`, `patrimoine_db`, `operations_db` |

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
base64 -w0 private.pem   # → JWT_PRIVATE_KEY_B64
base64 -w0 public.pem    # → JWT_PUBLIC_KEY_B64

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

Services API en plus : `JWT_PRIVATE_KEY_B64` (auth seulement), `JWT_PUBLIC_KEY_B64` (les 3), `AUTH_SERVICE_URL` (patrimoine/operations).

Service web en plus : `AUTH_SERVICE_URL`, `PATRIMOINE_SERVICE_URL`, `OPERATIONS_SERVICE_URL`, `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`.

### 3.4 Déploiement

1. Pousser le code sur GitHub ;
2. Créer un projet/service Docker par composant (ou un blueprint) ;
3. Renseigner les variables d'environnement ;
4. Lancer les migrations : `php artisan migrate --seed` dans chaque service API ;
5. Vérifier `GET /api/health` sur les trois API et `GET /login` sur le web.

### 3.5 Points d'attention

- `php artisan serve --no-reload` est requis (les variables d'environnement Docker sont sinon filtrées) — déjà configuré dans les Dockerfiles ;
- Les services API sont **stateless** : `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` ;
- Ne jamais commiter `storage/app/jwt/*.pem` ni `.env` ;
- Le port d'écoute suit `$PORT` (injection hébergeur).

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
