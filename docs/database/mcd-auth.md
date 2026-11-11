# MCD - Service Auth

## Entités

### Utilisateur
- **Attributs :**
  - id (UUID, PK)
  - email (string, unique)
  - password_hash (string)
  - nom (string)
  - role (enum: administrateur, gestionnaire, responsable, inspecteur)
  - service_id (UUID, FK logique vers patrimoine-service)
  - actif (boolean)
  - email_verified_at (datetime, nullable)
  - created_at (datetime)
  - updated_at (datetime)

### Connexion (Journal des connexions)
- **Attributs :**
  - id (UUID, PK)
  - utilisateur_id (UUID, FK)
  - ip_address (string)
  - user_agent (string)
  - connecte_at (datetime)
  - deconnecte_at (datetime, nullable)

## Relations

- Un utilisateur peut avoir plusieurs connexions (1:N)
- Une connexion appartient à un seul utilisateur (N:1)

## Contraintes

- email unique
- role dans la liste des valeurs autorisées
- actif = true pour connexion autorisée
