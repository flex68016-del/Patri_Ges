# MLD - Service Operations (operations_db)

## Tables

### maintenances
```sql
CREATE TABLE maintenances (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID NOT NULL,
    bien_libelle VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('preventive', 'corrective')),
    statut VARCHAR(50) NOT NULL CHECK (statut IN ('planifiee', 'en_cours', 'cloturee', 'annulee')),
    date_prevue DATE NOT NULL,
    date_debut DATE,
    date_fin DATE,
    cout DECIMAL(15,2),
    prestataire VARCHAR(255),
    description TEXT,
    auteur_id UUID NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (date_fin IS NULL OR date_fin >= date_debut)
);

CREATE INDEX idx_maintenances_bien_id ON maintenances(bien_id);
CREATE INDEX idx_maintenances_statut ON maintenances(statut);
CREATE INDEX idx_maintenances_date_prevue ON maintenances(date_prevue);
CREATE INDEX idx_maintenances_auteur_id ON maintenances(auteur_id);
```

### campagnes_inventaire
```sql
CREATE TABLE campagnes_inventaire (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    service_id UUID NOT NULL,
    periode_debut DATE NOT NULL,
    periode_fin DATE NOT NULL,
    statut VARCHAR(50) NOT NULL CHECK (statut IN ('ouverte', 'cloturee')),
    inspecteur_id UUID NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (periode_fin >= periode_debut)
);

CREATE INDEX idx_campagnes_inventaire_service_id ON campagnes_inventaire(service_id);
CREATE INDEX idx_campagnes_inventaire_statut ON campagnes_inventaire(statut);
CREATE INDEX idx_campagnes_inventaire_periode ON campagnes_inventaire(periode_debut, periode_fin);
```

### lignes_inventaire
```sql
CREATE TABLE lignes_inventaire (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    campagne_id UUID NOT NULL,
    bien_id UUID NOT NULL,
    bien_libelle VARCHAR(255) NOT NULL,
    constat VARCHAR(50) NOT NULL CHECK (constat IN ('present', 'absent', 'degrade')),
    observation TEXT,
    constate_par_id UUID,
    constate_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campagne_id) REFERENCES campagnes_inventaire(id) ON DELETE CASCADE
);

CREATE INDEX idx_lignes_inventaire_campagne_id ON lignes_inventaire(campagne_id);
CREATE INDEX idx_lignes_inventaire_bien_id ON lignes_inventaire(bien_id);
CREATE INDEX idx_lignes_inventaire_constat ON lignes_inventaire(constat);
```

### anomalies
```sql
CREATE TABLE anomalies (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    bien_id UUID NOT NULL,
    bien_libelle VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('panne', 'degradation', 'perte')),
    description TEXT NOT NULL,
    statut VARCHAR(50) NOT NULL CHECK (statut IN ('signalee', 'en_cours', 'resolue')),
    declare_par_id UUID NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_anomalies_bien_id ON anomalies(bien_id);
CREATE INDEX idx_anomalies_statut ON anomalies(statut);
CREATE INDEX idx_anomalies_declare_par_id ON anomalies(declare_par_id);
```

### journaux_audit
```sql
CREATE TABLE journaux_audit (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    acteur_id UUID NOT NULL,
    action VARCHAR(255) NOT NULL,
    objet_type VARCHAR(100),
    objet_id UUID,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    detail JSONB
);

CREATE INDEX idx_journaux_audit_acteur_id ON journaux_audit(acteur_id);
CREATE INDEX idx_journaux_audit_action ON journaux_audit(action);
CREATE INDEX idx_journaux_audit_date ON journaux_audit(date);
CREATE INDEX idx_journaux_audit_objet ON journaux_audit(objet_type, objet_id);
```

## Notes

- `bien_id`, `auteur_id`, `inspecteur_id`, `constate_par_id`, `declare_par_id` sont des références logiques (pas de FK physique) vers les services correspondants
- `bien_libelle` est copié pour résilience inter-services
- Les campagnes clôturées sont immuables (géré par l'application, pas par trigger)
