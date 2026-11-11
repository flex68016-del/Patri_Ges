# MLD - Service Patrimoine (patrimoine_db)

## Tables

### services
```sql
CREATE TABLE services (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code VARCHAR(50) UNIQUE NOT NULL,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('ministere', 'direction', 'service')),
    parent_id UUID,
    actif BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES services(id) ON DELETE SET NULL
);

CREATE INDEX idx_services_code ON services(code);
CREATE INDEX idx_services_parent_id ON services(parent_id);
CREATE INDEX idx_services_type ON services(type);
```

### categories
```sql
CREATE TABLE categories (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    libelle VARCHAR(255) UNIQUE NOT NULL,
    type_bien VARCHAR(50) NOT NULL CHECK (type_bien IN ('immobilier', 'vehicule', 'equipement')),
    duree_amortissement INTEGER NOT NULL,
    amortissable BOOLEAN NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_categories_type_bien ON categories(type_bien);
```

### biens
```sql
CREATE TABLE biens (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code_inventaire VARCHAR(50) UNIQUE NOT NULL,
    designation VARCHAR(255) NOT NULL,
    categorie_id UUID NOT NULL,
    date_acquisition DATE NOT NULL,
    mode_acquisition VARCHAR(50) NOT NULL CHECK (mode_acquisition IN ('achat', 'don', 'transfert', 'construction')),
    valeur_acquisition DECIMAL(15,2) NOT NULL,
    etat VARCHAR(50) NOT NULL CHECK (etat IN ('neuf', 'bon', 'moyen', 'degrade', 'hors_service')),
    statut VARCHAR(50) NOT NULL CHECK (statut IN ('en_stock', 'affecte', 'en_maintenance', 'reforme', 'cede')),
    service_courant_id UUID,
    actif BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (service_courant_id) REFERENCES services(id)
);

CREATE INDEX idx_biens_code_inventaire ON biens(code_inventaire);
CREATE INDEX idx_biens_categorie_id ON biens(categorie_id);
CREATE INDEX idx_biens_service_courant_id ON biens(service_courant_id);
CREATE INDEX idx_biens_etat ON biens(etat);
CREATE INDEX idx_biens_statut ON biens(statut);
CREATE INDEX idx_biens_date_acquisition ON biens(date_acquisition);
```

### biens_immobiliers
```sql
CREATE TABLE biens_immobiliers (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID UNIQUE NOT NULL,
    adresse TEXT,
    superficie DECIMAL(10,2),
    titre_foncier VARCHAR(255),
    coordonnees_gps VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE CASCADE
);

CREATE INDEX idx_biens_immobiliers_bien_id ON biens_immobiliers(bien_id);
```

### biens_vehicules
```sql
CREATE TABLE biens_vehicules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID UNIQUE NOT NULL,
    immatriculation VARCHAR(50) UNIQUE NOT NULL,
    marque VARCHAR(255),
    modele VARCHAR(255),
    kilometrage INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE CASCADE
);

CREATE INDEX idx_biens_vehicules_bien_id ON biens_vehicules(bien_id);
CREATE INDEX idx_biens_vehicules_immatriculation ON biens_vehicules(immatriculation);
```

### biens_equipements
```sql
CREATE TABLE biens_equipements (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID UNIQUE NOT NULL,
    numero_serie VARCHAR(255) UNIQUE,
    marque VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE CASCADE
);

CREATE INDEX idx_biens_equipements_bien_id ON biens_equipements(bien_id);
CREATE INDEX idx_biens_equipements_numero_serie ON biens_equipements(numero_serie);
```

### affectations
```sql
CREATE TABLE affectations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID NOT NULL,
    service_id UUID NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE,
    motif TEXT,
    reference_acte VARCHAR(255),
    auteur_id UUID NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    CHECK (date_fin IS NULL OR date_fin >= date_debut)
);

CREATE INDEX idx_affectations_bien_id ON affectations(bien_id);
CREATE INDEX idx_affectations_service_id ON affectations(service_id);
CREATE INDEX idx_affectations_date_debut ON affectations(date_debut);
CREATE INDEX idx_affectations_auteur_id ON affectations(auteur_id);
```

### mouvements
```sql
CREATE TABLE mouvements (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('creation', 'affectation', 'transfert', 'maintenance_debut', 'maintenance_fin', 'reforme', 'cession')),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    auteur_id UUID NOT NULL,
    detail JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id)
);

CREATE INDEX idx_mouvements_bien_id ON mouvements(bien_id);
CREATE INDEX idx_mouvements_type ON mouvements(type);
CREATE INDEX idx_mouvements_date ON mouvements(date);
CREATE INDEX idx_mouvements_auteur_id ON mouvements(auteur_id);
```

### cessions
```sql
CREATE TABLE cessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID UNIQUE NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('vente', 'don', 'destruction')),
    date DATE NOT NULL,
    valeur_cession DECIMAL(15,2),
    valide_par_id UUID NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES biens(id)
);

CREATE INDEX idx_cessions_bien_id ON cessions(bien_id);
CREATE INDEX idx_cessions_date ON cessions(date);
```

## Notes

- `auteur_id` et `valide_par_id` sont des références logiques (pas de FK physique) vers auth-service
- Les spécialisations (immobilier, véhicule, équipement) sont implémentées comme des tables séparées avec relation 1:1
- La suppression logique est gérée par le champ `actif` (pas de DELETE physique)
