# MLD - Service Auth (auth_db)

## Tables

### users
```sql
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL CHECK (role IN ('administrateur', 'gestionnaire', 'responsable', 'inspecteur')),
    service_id UUID,
    actif BOOLEAN DEFAULT true,
    email_verified_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_service_id ON users(service_id);
CREATE INDEX idx_users_role ON users(role);
```

### connexions
```sql
CREATE TABLE connexions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    utilisateur_id UUID NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    connecte_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deconnecte_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_connexions_utilisateur_id ON connexions(utilisateur_id);
CREATE INDEX idx_connexions_connecte_at ON connexions(connecte_at);
```

## Notes

- `service_id` est une référence logique (pas de FK physique) vers patrimoine-service
- Les mots de passe sont hachés avec Argon2id ou bcrypt
- `email_verified_at` nullable pour la vérification d'email (optionnel dans MVP)
