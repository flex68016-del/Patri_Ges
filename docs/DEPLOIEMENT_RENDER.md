# Guide de Déploiement sur Render

## 1. Prérequis

- Compte Render (gratuit pour les projets open-source)
- Compte GitHub avec le dépôt du projet
- Clés JWT générées (voir scripts/generate-jwt-keys.bat)

---

## 2. Architecture sur Render

L'application sera déployée avec les services suivants :

1. **PostgreSQL** : Base de données (1 instance)
2. **auth-service** : Service d'authentification
3. **patrimoine-service** : Service de gestion du patrimoine
4. **operations-service** : Service des opérations
5. **web** : Interface web

---

## 3. Configuration de la Base de Données

### 3.1 Créer PostgreSQL

1. Connectez-vous à Render
2. Cliquez sur "New" → "PostgreSQL"
3. Configurez :
   - **Name** : patrimoine-db
   - **Database** : patrimoine
   - **User** : patrimoine_user
   - **Region** : Oregon (us-west) ou Frankfurt (eu-central)
   - **Plan** : Free
4. Cliquez sur "Create Database"

### 3.2 Récupérer les Informations de Connexion

1. Une fois créé, cliquez sur la base de données
2. Copiez l'URL de connexion (format : `postgres://user:password@host:port/database`)
3. Notez les informations suivantes :
   - Host
   - Port
   - Database
   - User
   - Password

---

## 4. Configuration des Services Laravel

### 4.1 Créer auth-service

1. Cliquez sur "New" → "Web Service"
2. Connectez votre compte GitHub
3. Sélectionnez le dépôt et la branche
4. Configurez :
   - **Name** : auth-service
   - **Environment** : Docker
   - **Docker Context** : `services/auth-service`
   - **Build Command** : (vide, Dockerfile gère le build)
   - **Start Command** : (vide, Dockerfile gère le start)
5. Variables d'environnement (voir section 5)
6. Cliquez sur "Create Web Service"

### 4.2 Créer patrimoine-service

Répétez les étapes 4.1 avec :
- **Name** : patrimoine-service
- **Docker Context** : `services/patrimoine-service`

### 4.3 Créer operations-service

Répétez les étapes 4.1 avec :
- **Name** : operations-service
- **Docker Context** : `services/operations-service`

### 4.4 Créer web

Répétez les étapes 4.1 avec :
- **Name** : web
- **Docker Context** : `services/web`

---

## 5. Variables d'Environnement

Pour chaque service Laravel, configurez les variables suivantes :

### 5.1 Variables Communes

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:<généré avec php artisan key:generate>
```

### 5.2 Variables Base de Données

```
DB_CONNECTION=pgsql
DB_HOST=<host de Render PostgreSQL>
DB_PORT=5432
DB_DATABASE=patrimoine
DB_USERNAME=<user de Render PostgreSQL>
DB_PASSWORD=<password de Render PostgreSQL>
```

### 5.3 Variables auth-service

```
JWT_ALGORITHM=RS256
JWT_PRIVATE_KEY=<clé privée encodée en base64>
JWT_PUBLIC_KEY=<clé publique encodée en base64>
```

### 5.4 Variables patrimoine-service

```
DB_SCHEMA=patrimoine_db
```

### 5.5 Variables operations-service

```
DB_SCHEMA=operations_db
```

### 5.6 Variables web

```
AUTH_SERVICE_URL=<URL de auth-service sur Render>
PATRIMOINE_SERVICE_URL=<URL de patrimoine-service sur Render>
OPERATIONS_SERVICE_URL=<URL de operations-service sur Render>
```

---

## 6. Génération des Clés JWT

### 6.1 Générer les Clés

```bash
# Windows
scripts\generate-jwt-keys.bat

# Linux/Mac
bash scripts/generate-jwt-keys.sh
```

### 6.2 Encoder en Base64

```bash
# Clé privée
base64 -w 0 storage/app/jwt/private.pem

# Clé publique
base64 -w 0 storage/app/jwt/public.pem
```

### 6.3 Ajouter aux Variables d'Environnement

Ajoutez les valeurs encodées aux variables JWT_PRIVATE_KEY et JWT_PUBLIC_KEY dans Render.

---

## 7. Exécution des Migrations

### 7.1 Méthode 1 : Via Build Hook

Ajoutez un build hook dans le Dockerfile de chaque service :

```dockerfile
RUN php artisan migrate --force
RUN php artisan db:seed --force
```

### 7.2 Méthode 2 : Via Console Render

1. Déployez les services
2. Accédez à la console du service
3. Exécutez :
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## 8. Configuration des CORS

Ajoutez les URLs des services Render dans la configuration CORS de chaque service Laravel :

```php
// config/cors.php
'paths' => ['api/*'],
'allowed_origins' => [
    'https://<web-service-url>.onrender.com',
],
```

---

## 9. Tests de Déploiement

### 9.1 Vérifier les Services

1. Auth-service : `https://<auth-service-url>.onrender.com/api/health`
2. Patrimoine-service : `https://<patrimoine-service-url>.onrender.com/api/health`
3. Operations-service : `https://<operations-service-url>.onrender.com/api/health`
4. Web : `https://<web-service-url>.onrender.com`

### 9.2 Tester l'Authentification

1. Accédez à l'URL de web
2. Connectez-vous avec un compte de test
3. Vérifiez que vous accédez au tableau de bord

### 9.3 Tester les API

Utilisez Postman ou curl pour tester les endpoints :

```bash
# Login
curl -X POST https://<auth-service-url>.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@patrimoine.gouv","password":"password123"}'

# Liste des biens
curl -X GET https://<patrimoine-service-url>.onrender.com/api/biens \
  -H "Authorization: Bearer <token>"
```

---

## 10. Monitoring

### 10.1 Logs Render

1. Accédez à la page du service sur Render
2. Cliquez sur "Logs"
3. Vérifiez les erreurs et avertissements

### 10.2 Métriques

Render fournit des métriques pour :
- CPU
- Mémoire
- Réseau
- Temps de réponse

---

## 11. Mises à Jour

### 11.1 Déployer une Nouvelle Version

1. Pushez les changements sur GitHub
2. Render détecte automatiquement le push
3. Le build se lance automatiquement
4. Le service est redémarré après le build

### 11.2 Rollback

1. Accédez à la page du service
2. Cliquez sur "Deployments"
3. Sélectionnez le déploiement précédent
4. Cliquez sur "Redeploy"

---

## 12. Sécurité

### 12.1 Secrets

- Ne jamais commiter les clés JWT
- Utiliser les variables d'environnement de Render
- Régénérer les clés si compromises

### 12.2 HTTPS

Render fournit automatiquement des certificats SSL Let's Encrypt gratuits.

### 12.3 Firewall

Render ne nécessite pas de configuration firewall supplémentaire.

---

## 13. Coûts

Avec le plan gratuit Render :

- **PostgreSQL** : Gratuit (90 jours, puis $7/mois)
- **Web Services** : Gratuits (750 heures/mois)
- **Bandwidth** : 100 GB/mois gratuits

**Coût estimé** : $7/mois après 90 jours (PostgreSQL uniquement)

---

## 14. Dépannage

### 14.1 Build Failed

- Vérifiez les logs de build
- Vérifiez les variables d'environnement
- Vérifiez que le Dockerfile est correct

### 14.2 Database Connection Failed

- Vérifiez les informations de connexion PostgreSQL
- Vérifiez que la base de données est accessible
- Vérifiez les variables d'environnement DB_*

### 14.3 502 Bad Gateway

- Vérifiez que le service est démarré
- Vérifiez les logs du service
- Vérifiez le port configuré

---

## 15. Conclusion

Une fois déployé, l'application sera accessible via l'URL du service web. Tous les services backend seront accessibles via leurs API respectives.

Pour toute question ou problème, consultez les logs Render ou la documentation technique.
