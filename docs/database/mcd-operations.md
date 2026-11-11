# MCD - Service Operations

## Entités

### Maintenance
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK logique vers patrimoine-service)
  - bien_libelle (string, copié pour résilience)
  - type (enum: preventive, corrective)
  - statut (enum: planifiee, en_cours, cloturee, annulee)
  - date_prevue (date)
  - date_debut (date, nullable)
  - date_fin (date, nullable)
  - cout (decimal, nullable)
  - prestataire (string, nullable)
  - description (text)
  - auteur_id (UUID, FK logique vers auth-service)
  - created_at (datetime)
  - updated_at (datetime)

### CampagneInventaire
- **Attributs :**
  - id (UUID, PK)
  - service_id (UUID, FK logique vers patrimoine-service)
  - periode_debut (date)
  - periode_fin (date)
  - statut (enum: ouverte, cloturee)
  - inspecteur_id (UUID, FK logique vers auth-service)
  - created_at (datetime)
  - updated_at (datetime)

### LigneInventaire
- **Attributs :**
  - id (UUID, PK)
  - campagne_id (UUID, FK)
  - bien_id (UUID, FK logique vers patrimoine-service)
  - bien_libelle (string, copié pour résilience)
  - constat (enum: present, absent, degrade)
  - observation (text, nullable)
  - constate_par_id (UUID, FK logique vers auth-service)
  - constate_at (datetime)
  - created_at (datetime)
  - updated_at (datetime)

### Anomalie
- **Attributs :**
  - id (UUID, PK)
  - bien_id (UUID, FK logique vers patrimoine-service)
  - bien_libelle (string, copié pour résilience)
  - type (enum: panne, degradation, perte)
  - description (text)
  - statut (enum: signalee, en_cours, resolue)
  - declare_par_id (UUID, FK logique vers auth-service)
  - created_at (datetime)
  - updated_at (datetime)

### JournalAudit
- **Attributs :**
  - id (UUID, PK)
  - acteur_id (UUID, FK logique vers auth-service)
  - action (string)
  - objet_type (string)
  - objet_id (UUID)
  - date (datetime)
  - detail (json, nullable)

## Relations

- CampagneInventaire peut avoir plusieurs LigneInventaire (1:N)
- LigneInventaire appartient à une CampagneInventaire (N:1)
- Bien peut avoir plusieurs Maintenances (N:1 via bien_id logique)
- Bien peut avoir plusieurs Anomalies (N:1 via bien_id logique)
- Bien peut apparaître dans plusieurs LigneInventaire (N:1 via bien_id logique)
- Maintenance a un auteur (N:1 vers utilisateur logique)
- CampagneInventaire a un inspecteur (N:1 vers utilisateur logique)
- LigneInventaire a un constateur (N:1 vers utilisateur logique)
- Anomalie a un déclarant (N:1 vers utilisateur logique)
- JournalAudit a un acteur (N:1 vers utilisateur logique)

## Contraintes

- Une campagne clôturée est immuable
- periode_fin >= periode_debut
- date_fin >= date_debut (maintenance)
- statut progression : planifiee → en_cours → cloturee
