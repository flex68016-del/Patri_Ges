# Plan d'Index

## Service Auth (auth_db)

### users
- `idx_users_email` : sur `email` (UNIQUE) - pour la connexion
- `idx_users_service_id` : sur `service_id` - pour filtrer par service
- `idx_users_role` : sur `role` - pour filtrer par rôle

### connexions
- `idx_connexions_utilisateur_id` : sur `utilisateur_id` - pour l'historique des connexions
- `idx_connexions_connecte_at` : sur `connecte_at` - pour les audits et statistiques

---

## Service Patrimoine (patrimoine_db)

### services
- `idx_services_code` : sur `code` (UNIQUE) - pour les références
- `idx_services_parent_id` : sur `parent_id` - pour la hiérarchie
- `idx_services_type` : sur `type` - pour filtrer par type

### categories
- `idx_categories_type_bien` : sur `type_bien` - pour filtrer par type de bien

### biens
- `idx_biens_code_inventaire` : sur `code_inventaire` (UNIQUE) - pour la recherche par code
- `idx_biens_categorie_id` : sur `categorie_id` - pour filtrer par catégorie
- `idx_biens_service_courant_id` : sur `service_courant_id` - pour les biens d'un service (FR-11)
- `idx_biens_etat` : sur `etat` - pour filtrer par état
- `idx_biens_statut` : sur `statut` - pour filtrer par statut
- `idx_biens_date_acquisition` : sur `date_acquisition` - pour les statistiques et amortissement

### biens_immobiliers
- `idx_biens_immobiliers_bien_id` : sur `bien_id` (UNIQUE) - pour la jointure avec biens

### biens_vehicules
- `idx_biens_vehicules_bien_id` : sur `bien_id` (UNIQUE) - pour la jointure avec biens
- `idx_biens_vehicules_immatriculation` : sur `immatriculation` (UNIQUE) - pour la recherche par plaque

### biens_equipements
- `idx_biens_equipements_bien_id` : sur `bien_id` (UNIQUE) - pour la jointure avec biens
- `idx_biens_equipements_numero_serie` : sur `numero_serie` - pour la recherche par numéro de série

### affectations
- `idx_affectations_bien_id` : sur `bien_id` - pour l'historique d'un bien (FR-07)
- `idx_affectations_service_id` : sur `service_id` - pour les biens affectés à un service
- `idx_affectations_date_debut` : sur `date_debut` - pour la chronologie
- `idx_affectations_auteur_id` : sur `auteur_id` - pour l'audit

### mouvements
- `idx_mouvements_bien_id` : sur `bien_id` - pour l'historique (FR-07)
- `idx_mouvements_type` : sur `type` - pour filtrer par type de mouvement
- `idx_mouvements_date` : sur `date` - pour la chronologie
- `idx_mouvements_auteur_id` : sur `auteur_id` - pour l'audit

### cessions
- `idx_cessions_bien_id` : sur `bien_id` (UNIQUE) - pour la jointure avec biens
- `idx_cessions_date` : sur `date` - pour les statistiques

---

## Service Operations (operations_db)

### maintenances
- `idx_maintenances_bien_id` : sur `bien_id` - pour l'historique d'un bien
- `idx_maintenances_statut` : sur `statut` - pour les maintenances en retard (FR-14)
- `idx_maintenances_date_prevue` : sur `date_prevue` - pour les maintenances à venir (FR-03)
- `idx_maintenances_auteur_id` : sur `auteur_id` - pour l'audit

### campagnes_inventaire
- `idx_campagnes_inventaire_service_id` : sur `service_id` - pour les campagnes d'un service
- `idx_campagnes_inventaire_statut` : sur `statut` - pour les campagnes ouvertes
- `idx_campagnes_inventaire_periode` : sur `periode_debut, periode_fin` - pour les périodes

### lignes_inventaire
- `idx_lignes_inventaire_campagne_id` : sur `campagne_id` - pour les lignes d'une campagne
- `idx_lignes_inventaire_bien_id` : sur `bien_id` - pour l'historique d'inventaire d'un bien
- `idx_lignes_inventaire_constat` : sur `constat` - pour le rapport des écarts (FR-06)

### anomalies
- `idx_anomalies_bien_id` : sur `bien_id` - pour l'historique d'anomalies d'un bien
- `idx_anomalies_statut` : sur `statut` - pour les anomalies en cours
- `idx_anomalies_declare_par_id` : sur `declare_par_id` - pour l'audit

### journaux_audit
- `idx_journaux_audit_acteur_id` : sur `acteur_id` - pour l'historique d'un acteur
- `idx_journaux_audit_action` : sur `action` - pour filtrer par type d'action
- `idx_journaux_audit_date` : sur `date` - pour la chronologie
- `idx_journaux_audit_objet` : sur `objet_type, objet_id` - pour l'historique d'un objet

---

## Index Composites (pour les requêtes fréquentes)

### Recherche multi-critères de biens
```sql
CREATE INDEX idx_biens_recherche ON biens(categorie_id, service_courant_id, etat, statut);
```
Utilisé pour : FR-03 (recherche, filtres)

### Historique des mouvements avec filtre temporel
```sql
CREATE INDEX idx_mouvements_historique ON mouvements(bien_id, date DESC);
```
Utilisé pour : FR-07 (historique des mouvements)

### Maintenances en retard
```sql
CREATE INDEX idx_maintenances_retard ON maintenances(statut, date_prevue)
WHERE statut IN ('planifiee', 'en_cours');
```
Utilisé pour : FR-14 (maintenances en retard)

---

## Notes sur la Performance

- Les index sont créés sur les colonnes fréquemment utilisées dans les WHERE, JOIN et ORDER BY
- Les index composites optimisent les requêtes multi-critères
- Les index partiels (WHERE) réduisent la taille pour les sous-ensembles fréquents
- Les index UNIQUE garantissent l'intégrité des données (RG-01)
- L'objectif est une réponse de liste < 500ms avec les données de démonstration (exigence non fonctionnelle)
