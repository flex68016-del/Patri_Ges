# Référence API

Documentation des API REST des trois microservices. Toutes les réponses sont en JSON.

## Convention générale

- **Base URLs** : `http://localhost:8001` (auth), `:8002` (patrimoine), `:8003` (operations)
- **Authentification** : header `Authorization: Bearer <JWT>` (RS256, durée de vie 8 h)
- **Obtention du token** : `POST /api/login` sur auth-service
- **Format d'erreur** : `{"success": false, "message": "...", "errors": {...}}` (422 pour validation)
- **Rate limiting** : 100 req/min par IP sur les routes authentifiées, 5 req/min sur `/api/login`

## Rôles

| Rôle | Droits d'écriture |
|------|-------------------|
| `administrateur` | Tout (utilisateurs, référentiels, réforme, cession) |
| `gestionnaire` | Biens, affectations, maintenances, campagnes, anomalies |
| `responsable` | Lecture seule (sauf anomalies non autorisées en écriture) |
| `inspecteur` | Lignes d'inventaire, anomalies |

---

## 1. Auth Service (`:8001`)

### `POST /api/login`

Connexion. Retourne un JWT RS256.

**Body :**
```json
{ "email": "admin@patrimoine.gouv", "password": "password123" }
```

**Réponse 200 :**
```json
{
  "success": true,
  "data": {
    "token": "eyJhbGciOi...",
    "user": { "id": "uuid", "email": "...", "nom": "...", "prenom": "...", "role": "administrateur" }
  }
}
```

### `POST /api/logout` *(JWT)*

Déconnexion (côté API : simple accusé de réception).

### `GET /api/me` *(JWT)*

Utilisateur courant.

### `GET /api/users` *(JWT, rôle `administrateur`)*

Liste des utilisateurs : `id, email, nom, prenom, role, statut, derniere_connexion_at`.

### `POST /api/users` *(JWT, rôle `administrateur`)*

Crée un utilisateur.

| Champ | Règle |
|-------|-------|
| `nom`, `prenom` | requis, string ≤ 255 |
| `email` | requis, email unique |
| `password` | requis, ≥ 8 caractères |
| `role` | `administrateur`, `gestionnaire`, `responsable`, `inspecteur` |

Statut initial : `actif`.

### `GET /api/health`

`{"status": "ok"}`

---

## 2. Patrimoine Service (`:8002`)

### Biens

#### `GET /api/biens` *(JWT)*

Liste paginée (20/page). Filtres : `search` (code/désignation), `categorie_id`, `service_id`, `statut`. Inclut `categorie` et `service`. Ne retourne que `actif = true`.

#### `GET /api/biens/{id}` *(JWT)*

Détail complet : relations `categorie`, `service`, `immobilier`, `vehicule`, `equipement`, `affectations`, `mouvements`, `cession` + `valeur_net_comptable` calculée.

#### `POST /api/biens` *(JWT, `gestionnaire`/`administrateur`)*

| Champ | Règle |
|-------|-------|
| `code` | requis, unique, ≤ 50 |
| `designation` | requis, ≤ 255 |
| `categorie_id` | requis, UUID existant |
| `service_id` | nullable, UUID |
| `date_acquisition` | requis, date |
| `valeur_acquisition` | requis, numérique ≥ 0 |
| `emplacement` | nullable, ≤ 255 |
| `statut` | optionnel : `disponible` (défaut), `affecte`, `en_maintenance`, `reforme`, `cede` |

Crée automatiquement un mouvement `creation`.

#### `PUT /api/biens/{id}` *(JWT, `gestionnaire`/`administrateur`)*

Modification partielle : `designation`, `statut`, `service_id`, `emplacement`.

#### `POST /api/biens/{id}/affecter` *(JWT, `gestionnaire`/`administrateur`)*

| Champ | Règle |
|-------|-------|
| `service_id` | requis, UUID |
| `date_debut` | requis, date |
| `motif` | nullable |

Clôture l'affectation active précédente et passe le bien à `affecte`.

#### `POST /api/biens/{id}/reformer` *(JWT, `administrateur`)*

`motif` requis. Statut → `reforme` + mouvement.

#### `POST /api/biens/{id}/ceder` *(JWT, `administrateur`)*

| Champ | Règle |
|-------|-------|
| `type` | `vente`, `don`, `destruction` |
| `date` | requis, date |
| `valeur_cession` | nullable, numérique ≥ 0 |

Crée une cession et passe le bien à `cede` (`actif = false`).

#### `GET /api/biens/{id}/historique` *(JWT)*

Mouvements du bien, triés par date décroissante.

### Référentiels

#### `GET /api/services`, `GET /api/services/{id}` *(JWT)*

Champs : `code, nom, description, responsable, email, telephone, actif`.

#### `POST /api/services` *(JWT, `administrateur`)*

`code`, `nom` requis ; `description`, `responsable`, `email`, `telephone` optionnels.

#### `GET /api/categories`, `GET /api/categories/{id}` *(JWT)*

Champs : `code, nom, type_bien, description, duree_vie, actif`.
`type_bien` : `immobilier`, `vehicule`, `equipement`.

#### `POST /api/categories` *(JWT, `administrateur`)*

`code`, `nom`, `type_bien`, `duree_vie` (entier ≥ 1) requis ; `description` optionnel.

### Affectations

#### `GET /api/affectations`, `GET /api/affectations/{id}` *(JWT)*

Historique des affectations (`service_id, bien_id, date_debut, date_fin, motif, actif`).

---

## 3. Operations Service (`:8003`)

### Maintenances

#### `GET /api/maintenances` *(JWT)*

Liste paginée. Filtres : `statut` (`planifie`, `en_cours`, `termine`, `annule`), `type` (`preventive`, `corrective`).

#### `POST /api/maintenances` *(JWT, `gestionnaire`/`administrateur`)*

| Champ | Règle |
|-------|-------|
| `bien_id` | requis, UUID |
| `type` | `preventive`, `corrective` |
| `description` | requis |
| `date_planifiee` | requis, date |
| `date_effective` | nullable, date |
| `cout` | nullable, numérique ≥ 0 |
| `statut` | `planifie`, `en_cours`, `termine`, `annule` |
| `technicien` | nullable |

#### `POST /api/maintenances/{id}/cloturer` *(JWT, `gestionnaire`/`administrateur`)*

`date_effective` et `cout` requis → statut `termine`.

### Campagnes d'inventaire

#### `GET /api/campagnes-inventaire`, `GET /api/campagnes-inventaire/{id}` *(JWT)*

Inclut la relation `lignes`. Filtres : `statut`, `service_id`.

#### `POST /api/campagnes-inventaire` *(JWT, `gestionnaire`/`administrateur`)*

`titre`, `date_debut`, `date_fin` (≥ début), `statut`, `inspecteur_id` requis ; `description` optionnel.

#### `POST /api/campagnes-inventaire/{id}/cloturer` *(JWT, `gestionnaire`/`administrateur`)*

Statut → `termine`.

#### `GET /api/campagnes-inventaire/{id}/lignes` *(JWT)*

Constats de la campagne.

#### `POST /api/campagnes-inventaire/{id}/lignes` *(JWT, `gestionnaire`/`administrateur`/`inspecteur`)*

| Champ | Règle |
|-------|-------|
| `bien_id` | requis, UUID |
| `bien_libelle` | optionnel (code + désignation) |
| `constat` | `present`, `absent`, `degrade` |
| `observation` | optionnel |

### Anomalies

#### `GET /api/anomalies`, `GET /api/anomalies/{id}` *(JWT)*

Filtres : `statut` (`signale`, `en_cours`, `resolu`, `clos`), `type` (`disparition`, `degradation`, `inventaire_ecart`, `autre`).

#### `POST /api/anomalies` *(JWT, `gestionnaire`/`administrateur`/`inspecteur`)*

| Champ | Règle |
|-------|-------|
| `bien_id` | requis, UUID |
| `type` | `disparition`, `degradation`, `inventaire_ecart`, `autre` |
| `description` | requis |
| `gravite` | `faible`, `moyenne`, `elevee`, `critique` |
| `statut` | `signale`, `en_cours`, `resolu`, `clos` |
| `date_constatation` | requis, date |

`declare_par_id` est renseigné automatiquement depuis le JWT.

#### `POST /api/anomalies/{id}/resoudre` *(JWT, `gestionnaire`/`administrateur`/`inspecteur`)*

Statut → `resolu` + `date_resolution` = aujourd'hui.

---

## 4. Interface Web (`:8000`)

Routes de session (cookie) — l'utilisateur se connecte via `/login`, le JWT est conservé en session serveur et transmis aux API.

| Route | Rôle requis | Fonction |
|-------|-------------|----------|
| `GET /login`, `POST /login` | public | Connexion |
| `POST /logout` | connecté | Déconnexion |
| `GET /dashboard` | connecté | Indicateurs et activité |
| `GET /biens`, `/biens/create`, `/biens/{id}`, `/biens/{id}/edit` | lecture / `gestionnaire`,`administrateur` (écriture) | Biens |
| `POST /biens/{id}/affecter` | `gestionnaire`,`administrateur` | Affectation |
| `POST /biens/{id}/reformer`, `/ceder` | `administrateur` | Réforme / cession |
| `GET/POST /maintenances`, `GET/POST /maintenances/{id}/cloturer` | lecture / `gestionnaire`,`administrateur` | Maintenances |
| `GET/POST /inventaires`, `/{id}/constats`, `/{id}/lignes`, `/{id}/rapport`, `/{id}/cloturer` | lecture / `gestionnaire`,`administrateur`,`inspecteur` (constats) | Inventaires |
| `GET/POST /anomalies`, `/{id}/resoudre` | lecture / `gestionnaire`,`administrateur`,`inspecteur` | Anomalies |
| `GET/POST /admin/users`, `/admin/references/*` | `administrateur` | Administration |

---

## 5. Schéma d'authentification inter-services

```
Client web ──POST /api/login──▶ auth-service (signe JWT RS256, private.pem)
Client web ──Bearer JWT──▶ patrimoine/operations
                              │
                              ├─ décode avec public.pem (signature)
                              └─ GET auth-service/api/me (validation + utilisateur)
```

Le middleware `jwt` vérifie : signature RS256, expiration, `statut = actif` de l'utilisateur. Le middleware `role` applique les restrictions par endpoint.
