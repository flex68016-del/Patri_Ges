# Normalisation de la Base de Données

## Table Non Normalisée (Départ)

Imaginons une table unique "inventaire" contenant toutes les informations :

```sql
inventaire (
    bien_id,
    code_inventaire,
    designation,
    categorie_libelle,
    categorie_type,
    categorie_duree_amortissement,
    service_nom,
    service_code,
    service_type,
    affectation_date_debut,
    affectation_date_fin,
    affectation_motif,
    maintenance_date,
    maintenance_type,
    maintenance_cout,
    maintenance_prestataire,
    utilisateur_email,
    utilisateur_role,
    utilisateur_nom
)
```

**Problèmes :**
- Redondance massive (service, catégorie, utilisateur répétés pour chaque bien)
- Anomalies de mise à jour (changer le nom d'un service nécessite de modifier toutes les lignes)
- Anomalies d'insertion (ne peut pas créer un service sans bien)
- Anomalies de suppression (supprimer un bien supprime aussi les informations de service)

---

## Première Forme Normale (1FN)

**Définition :** Une table est en 1FN si :
1. Tous les attributs sont atomiques (indivisibles)
2. Chaque attribut contient une seule valeur
3. Il n'y a pas de groupes répétitifs

**Application :**
- La table est déjà en 1FN (pas de valeurs multiples, pas de groupes répétitifs)

**Dépendances fonctionnelles :**
- bien_id → code_inventaire, designation, categorie_libelle, ...
- code_inventaire → bien_id
- service_code → service_nom, service_type
- categorie_libelle → categorie_type, categorie_duree_amortissement
- utilisateur_email → utilisateur_role, utilisateur_nom

---

## Deuxième Forme Normale (2FN)

**Définition :** Une table est en 2FN si :
1. Elle est en 1FN
2. Tout attribut non clé dépend de la clé entière (pas de dépendance partielle)

**Application :**
- La clé primaire est `bien_id`
- Mais il y a des dépendances partielles :
  - `service_code → service_nom, service_type` (ne dépend pas de bien_id)
  - `categorie_libelle → categorie_type, categorie_duree_amortissement` (ne dépend pas de bien_id)
  - `utilisateur_email → utilisateur_role, utilisateur_nom` (ne dépend pas de bien_id)

**Solution :** Extraire les entités dépendantes dans des tables séparées

```sql
-- Table des biens
biens (
    bien_id,
    code_inventaire,
    designation,
    categorie_libelle,  -- FK vers categories
    service_code,       -- FK vers services
    ...
)

-- Table des services
services (
    service_code,
    service_nom,
    service_type
)

-- Table des catégories
categories (
    categorie_libelle,
    categorie_type,
    categorie_duree_amortissement
)

-- Table des utilisateurs
utilisateurs (
    utilisateur_email,
    utilisateur_role,
    utilisateur_nom
)
```

---

## Troisième Forme Normale (3FN)

**Définition :** Une table est en 3FN si :
1. Elle est en 2FN
2. Aucun attribut non clé ne dépend transitivement d'un autre attribut non clé

**Application :**
- Dans la table `categories` :
  - `categorie_libelle → categorie_type, categorie_duree_amortissement`
  - Pas de dépendance transitive (OK)

- Dans la table `services` :
  - `service_code → service_nom, service_type`
  - Pas de dépendance transitive (OK)

- Dans la table `biens` :
  - `bien_id → tous les attributs`
  - Les attributs `categorie_libelle` et `service_code` sont des clés étrangères, pas des attributs transitifs (OK)

**Conclusion :** Les tables sont en 3FN.

---

## Forme Normale de Boyce-Codd (BCNF)

**Définition :** Une table est en BCNF si :
1. Elle est en 3FN
2. Pour toute dépendance fonctionnelle A → B, A est une clé candidate

**Application :**
- Vérifions chaque table :

**Table `services` :**
- `service_code → service_nom, service_type`
- `service_code` est clé primaire (OK)

**Table `categories` :**
- `categorie_libelle → categorie_type, categorie_duree_amortissement`
- `categorie_libelle` est clé primaire (OK)

**Table `biens` :**
- `bien_id → tous les attributs`
- `bien_id` est clé primaire (OK)
- `code_inventaire → bien_id` (dépendance supplémentaire)
- `code_inventaire` est une clé candidate unique (OK)

**Conclusion :** Les tables sont en BCNF.

---

## Redondances Volontaires

Pour la communication inter-services, certaines redondances sont nécessaires :

### 1. Libellés copiés entre services

**Dans `operations.maintenances` :**
- `bien_id` (référence logique UUID)
- `bien_libelle` (copié du patrimoine-service)

**Raison :**
- Si le service patrimoine est indisponible, operations peut toujours afficher le libellé
- Évite un appel réseau pour chaque affichage de maintenance
- Le libellé est synchronisé lors de la création/mise à jour de la maintenance

### 2. Informations utilisateur dans chaque service

**Dans chaque service :**
- `utilisateur_id` (référence logique UUID)
- `utilisateur_email`, `utilisateur_role` (copiés de auth-service)

**Raison :**
- Le token JWT contient ces informations
- Chaque service peut les stocker localement pour l'audit
- Pas d'appel réseau à auth-service pour chaque action

### 3. Service courant dans biens

**Dans `patrimoine.biens` :**
- `service_courant_id` (référence vers services)

**Raison :**
- Requête fréquente : "biens du service X"
- Évite de joindre avec la table affectations pour chaque requête
- Mis à jour lors de chaque affectation

---

## Conclusion

Le schéma final est en BCNF avec des redondances volontaires documentées pour optimiser les performances inter-services et la résilience.
