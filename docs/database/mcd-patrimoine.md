# MCD - Service Patrimoine

## Entités

### Service
- **Attributs :**
  - id (UUID, PK)
  - code (string, unique)
  - nom (string)
  - type (enum: ministere, direction, service)
  - parent_id (UUID, FK auto-référence, nullable)
  - actif (boolean)
  - created_at (datetime)
  - updated_at (datetime)

### Categorie
- **Attributs :**
  - id (UUID, PK)
  - libelle (string, unique)
  - type_bien (enum: immobilier, vehicule, equipement)
  - duree_amortissement (integer, en années)
  - amortissable (boolean)
  - created_at (datetime)
  - updated_at (datetime)

### Bien
- **Attributs :**
  - id (UUID, PK)
  - code_inventaire (string, unique)
  - designation (string)
  - categorie_id (UUID, FK)
  - date_acquisition (date)
  - mode_acquisition (enum: achat, don, transfert, construction)
  - valeur_acquisition (decimal)
  - etat (enum: neuf, bon, moyen, degrade, hors_service)
  - statut (enum: en_stock, affecte, en_maintenance, reforme, cede)
  - service_courant_id (UUID, FK vers Service)
  - actif (boolean)
  - created_at (datetime)
  - updated_at (datetime)

### BienImmobilier (spécialisation)
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK vers Bien, unique)
  - adresse (string)
  - superficie (decimal, en m²)
  - titre_foncier (string, nullable)
  - coordonnees_gps (string, nullable)

### BienVehicule (spécialisation)
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK vers Bien, unique)
  - immatriculation (string, unique)
  - marque (string)
  - modele (string)
  - kilometrage (integer)

### BienEquipement (spécialisation)
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK vers Bien, unique)
  - numero_serie (string, unique)
  - marque (string)

### Affectation
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK)
  - service_id (UUID, FK)
  - date_debut (date)
  - date_fin (date, nullable)
  - motif (string)
  - reference_acte (string)
  - auteur_id (UUID, FK logique vers auth-service)
  - created_at (datetime)
  - updated_at (datetime)

### Mouvement (journal d'historique)
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK)
  - type (enum: creation, affectation, transfert, maintenance_debut, maintenance_fin, reforme, cession)
  - date (datetime)
  - auteur_id (UUID, FK logique vers auth-service)
  - detail (json)

### Cession
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK, unique)
  - type (enum: vente, don, destruction)
  - date (date)
  - valeur_cession (decimal)
  - valide_par_id (UUID, FK logique vers auth-service)
  - created_at (datetime)
  - updated_at (datetime)

## Relations

- Service peut avoir plusieurs services enfants (auto-référence 1:N)
- Service peut avoir plusieurs biens courants (1:N)
- Service peut avoir plusieurs affectations (1:N)
- Categorie peut avoir plusieurs biens (1:N)
- Bien peut avoir une spécialisation (1:0..1 vers BienImmobilier, BienVehicule, ou BienEquipement)
- Bien peut avoir plusieurs affectations (1:N)
- Bien peut avoir plusieurs mouvements (1:N)
- Bien peut avoir une cession (0..1:1)
- Affectation appartient à un bien (N:1)
- Affectation appartient à un service (N:1)
- Mouvement appartient à un bien (N:1)
- Cession appartient à un bien (N:1)

## Contraintes

- code_inventaire unique
- immatriculation unique (pour véhicules)
- numero_serie unique (pour équipements)
- date_fin >= date_debut (affectation)
- Un bien ne peut avoir qu'une seule affectation active à un instant donné
- Un bien réformé ou cédé ne peut plus être affecté
