# Ce Qu'il Reste à Faire - Projet Totalement Fonctionnel

## 📋 Résumé

Le projet est **90% terminé**. Voici ce qu'il reste concrètement pour que l'application soit **totalement fonctionnelle**.

---

## 🔧 Option A : PostgreSQL Local (sans Docker)

### Étape 1 : Réinitialiser le mot de passe PostgreSQL
**Méthode via pgAdmin** (que vous êtes en train de faire) :
1. Ouvrir pgAdmin
2. Connectez-vous avec le mot de passe actuel (ou réinitialisez)
3. Notez le nouveau mot de passe

### Étape 2 : Créer les bases de données
```bash
cd "C:\Program Files\PostgreSQL\16\bin"
createdb -U postgres patrimoine_auth
createdb -U postgres patrimoine_patrimoine
createdb -U postgres patrimoine_operations
createdb -U postgres patrimoine_web
```

### Étape 3 : Mettre à jour les fichiers .env
Modifier dans chaque service (auth-service, patrimoine-service, operations-service, web) :
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=patrimoine_auth  # ou patrimoine_patrimoine, etc.
DB_USERNAME=postgres
DB_PASSWORD=<votre_mot_de_passe>
```

### Étape 4 : Exécuter les migrations
```bash
cd services/auth-service
php artisan migrate:fresh

cd ../patrimoine-service
php artisan migrate:fresh

cd ../operations-service
php artisan migrate:fresh

cd ../web
php artisan migrate:fresh
```

### Étape 5 : Exécuter les seeders
```bash
cd services/auth-service
php artisan db:seed

cd ../patrimoine-service
php artisan db:seed

cd ../operations-service
php artisan db:seed
```

### Étape 6 : Générer les clés JWT
```bash
cd services/auth-service
mkdir -p storage/app/jwt
openssl genrsa -out storage/app/jwt/private.pem 2048
openssl rsa -in storage/app/jwt/private.pem -pubout -out storage/app/jwt/public.pem
```

### Étape 7 : Démarrer les 4 serveurs
```bash
cd services/auth-service
php artisan serve --port=8001

cd ../patrimoine-service
php artisan serve --port=8002

cd ../operations-service
php artisan serve --port=8003

cd ../web
php artisan serve --port=8000
```

### Étape 8 : Tester l'application
- Ouvrir http://localhost:8000
- Se connecter avec : admin@patrimoine.gouv / password123
- Tester les fonctionnalités

---

## 🐳 Option B : Docker Desktop

### Étape 1 : Installer Docker Desktop
- Télécharger depuis https://www.docker.com/products/docker-desktop/
- Installer et démarrer Docker Desktop

### Étape 2 : Démarrer l'infrastructure
```bash
cd C:\Users\ADN GOLFE 1\Projects\Patrimoine_Gouv
docker compose up --build
```

### Étape 3 : Vérifier les services
Docker gère automatiquement :
- PostgreSQL conteneurisé
- Migrations automatiques
- Seeders automatiques
- 4 services Laravel
- Génération des clés JWT

### Étape 4 : Tester l'application
- Ouvrir http://localhost:8000
- Se connecter avec : admin@patrimoine.gouv / password123
- Tester les fonctionnalités

---

## 🚀 Production sur Render (identique pour les deux options)

### Étape 1 : Créer un compte Render
- Aller sur https://render.com/
- S'inscrire et connecter GitHub

### Étape 2 : Créer les services Render
Dans Render Dashboard :
1. **New+** → **Web Service**
2. Connecter le dépôt GitHub
3. Créer 4 services :
   - `auth-service` : Root directory `services/auth-service`, Build Command `php artisan migrate --force`, Start Command `php artisan serve --host=0.0.0.0 --port=$PORT`
   - `patrimoine-service` : Root directory `services/patrimoine-service`, Build Command `php artisan migrate --force`, Start Command `php artisan serve --host=0.0.0.0 --port=$PORT`
   - `operations-service` : Root directory `services/operations-service`, Build Command `php artisan migrate --force`, Start Command `php artisan serve --host=0.0.0.0 --port=$PORT`
   - `web` : Root directory `services/web`, Build Command `php artisan migrate --force`, Start Command `php artisan serve --host=0.0.0.0 --port=$PORT`

### Étape 3 : Créer PostgreSQL Render
- **New+** → **PostgreSQL**
- Render fournit automatiquement : `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### Étape 4 : Configurer les variables d'environnement Render
Pour chaque service, ajouter :
```
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=<render-postgres-host>
DB_PORT=5432
DB_DATABASE=<render-postgres-db>
DB_USERNAME=<render-postgres-user>
DB_PASSWORD=<render-postgres-password>
AUTH_SERVICE_URL=<auth-service-url>
```

### Étape 5 : Générer les clés JWT sur Render
Via la console Render ou build script :
```bash
mkdir -p storage/app/jwt
openssl genrsa -out storage/app/jwt/private.pem 2048
openssl rsa -in storage/app/jwt/private.pem -pubout -out storage/app/jwt/public.pem
```

### Étape 6 : Exécuter les seeders sur Render
```bash
php artisan db:seed
```

### Étape 7 : Tester en production
- Accéder aux URLs Render fournies
- Vérifier que tout fonctionne

---

## ✅ Checklist Finale

- [ ] PostgreSQL local configuré OU Docker Desktop installé
- [ ] Bases de données créées
- [ ] Fichiers .env mis à jour (PostgreSQL)
- [ ] Migrations exécutées
- [ ] Seeders exécutés
- [ ] Clés JWT générées
- [ ] 4 serveurs Laravel démarrés
- [ ] Application testée en local
- [ ] Déploiement Render effectif (optionnel)

---

## 📝 Note Importante

**La configuration locale (PostgreSQL vs Docker) n'a AUCUN IMPACT sur la production Render.**

Sur Render, vous utiliserez TOUJOURS :
- ✅ PostgreSQL géré par Render
- ✅ Services Laravel déployés directement
- ✅ Variables d'environnement Render
- ✅ Migrations exécutées sur Render

**Le choix local ne sert qu'au développement et aux tests.**
