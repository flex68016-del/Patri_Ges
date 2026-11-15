# Revue de sécurité

Revue réalisée en novembre 2026 sur l'ensemble de la plateforme (architecture microservices Laravel 12 / PHP 8.4 / PostgreSQL 16).

## 1. Authentification

| Contrôle | État | Détail |
|----------|------|--------|
| Hachage des mots de passe | ✅ | `bcrypt` via `Hash::make` |
| Jetons JWT | ✅ | Signature **RS256** : clé privée confinée à auth-service, vérification par clé publique |
| Expiration des jetons | ✅ | 8 heures |
| Statut du compte | ✅ | Middleware vérifie `statut = actif` à chaque requête |
| Rate limiting login | ✅ | `throttle:5,1` sur `/api/login` |
| Journalisation connexions | ✅ | `derniere_connexion_at` mise à jour |

## 2. Autorisation (RBAC)

- Middleware `role` par groupe de routes, avec support multi-rôles (`role:a,b`) ;
- Réforme et cession : `administrateur` uniquement ;
- Référentiels et utilisateurs : `administrateur` uniquement ;
- Lignes d'inventaire et anomalies : `gestionnaire`, `administrateur`, `inspecteur` ;
- Côté web : middleware `EnsureAdmin` + `EnsureRole` alignés sur les permissions API ; liens et boutons masqués selon le rôle ;
- Vérifié en test : 403/201/401 conformes (voir `docs/TESTS.md` §4).

## 3. Protection des requêtes web

| Contrôle | État |
|----------|------|
| CSRF | ✅ `@csrf` sur tous les formulaires, `VerifyCsrfToken` actif (exception login/logout supprimée) |
| Sessions | ✅ Cookie de session serveur, invalidation + régénération du token au logout |
| Logout | ✅ POST uniquement (GET → 405), `Auth`-like flush session + `regenerateToken` |
| XSS | ✅ Échappement Blade `{{ }}` par défaut |
| En-têtes | ✅ `Accept: application/json` sur le client API interne |

## 4. Injection et requêtes

- ORM Eloquent / Query Builder paramétrés partout ; aucune concaténation SQL avec entrées utilisateur ;
- Validation stricte des entrées (types, enums, UUID, dates) sur chaque endpoint ;
- Clés étrangères (`exists:`) vérifiées.

## 5. Secrets et configuration

- Clés JWT et `APP_KEY` injectées par variables d'environnement, **jamais commitées** ;
- `storage/app/jwt/*.pem` et `.env` dans `.gitignore` ;
- `APP_DEBUG=false` requis en production (documenté) ;
- `.pgpass` non commité.

## 6. Surface d'attaque

- Healthchecks publics limités à `{"status":"ok"}` ;
- Throttle global 100 req/min sur les API ;
- Pas de données sensibles dans les messages d'erreur (message générique + `errors` de validation) ;
- Services API sans session/queue DB (drivers `file`/`sync`) : pas de tables superflues exposées.

## 7. Points à renforcer (recommandations)

- [ ] Activer HTTPS partout en production (derrière reverse proxy) ;
- [ ] Restreindre le CORS (`Access-Control-Allow-Origin: *` actuellement permissif) ;
- [ ] Ajouter des en-têtes de sécurité (CSP, X-Frame-Options, HSTS) ;
- [ ] Verrouiller `APP_DEBUG=false` et les logs en production ;
- [ ] Rotation périodique des clés JWT ;
- [ ] Auditer les dépendances (`composer audit`) dans la CI ;
- [ ] Restreindre `responsable` à son `service_id` (actuellement lecture globale).

## 8. Conclusion

La plateforme applique les contrôles essentials (authentification forte, RBAC, CSRF, validation, secrets externalisés). Les recommandations ci-dessus relèvent du durcissement pré-production.
