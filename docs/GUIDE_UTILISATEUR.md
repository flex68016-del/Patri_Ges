# Guide Utilisateur - Gestion du Patrimoine de l'État

## 1. Introduction

Ce guide vous explique comment utiliser l'application de gestion du patrimoine de l'État. L'application permet de gérer les biens publics (bâtiments, terrains, véhicules, équipements), de suivre les maintenances et de réaliser des inventaires.

---

## 2. Accès à l'Application

### 2.1 URL de Connexion

- **Développement local :** http://localhost:8000
- **Production :** URL fournie après déploiement sur Render

### 2.2 Identifiants de Connexion

Utilisez les identifiants fournis par l'administrateur système.

**Comptes de démonstration (seeders) :**
- Administrateur : admin@patrimoine.gouv / password123
- Gestionnaire : gestionnaire@patrimoine.gouv / password123
- Responsable : responsable@patrimoine.gouv / password123
- Inspecteur : inspecteur@patrimoine.gouv / password123

---

## 3. Connexion

1. Ouvrez votre navigateur et accédez à l'URL de l'application
2. Entrez votre email et votre mot de passe
3. Cliquez sur "Se connecter"
4. Vous serez redirigé vers le tableau de bord

---

## 4. Tableau de Bord

Le tableau de bord affiche :
- **Total biens** : Nombre total de biens enregistrés
- **Valeur totale** : Valeur d'acquisition cumulée de tous les biens
- **Biens affectés** : Nombre de biens affectés aux services avec barre de progression
- **Maintenances en retard** : Nombre de maintenances non effectuées

### 4.1 Graphiques

- **Répartition par type** : Graphique circulaire montrant la répartition des biens par catégorie (véhicules, immeubles, équipements, mobilier)
- **Répartition par statut** : Graphique à barres montrant le nombre de biens par statut (actif, en maintenance, réformé, cédé)

### 4.2 Actions Rapides

- **Nouveau bien** : Créer un nouveau bien
- **Nouvelle maintenance** : Créer une nouvelle maintenance
- **Campagne d'inventaire** : Créer une nouvelle campagne d'inventaire

---

## 5. Gestion des Biens

### 5.1 Liste des Biens

Pour accéder à la liste des biens :
1. Cliquez sur "Biens" dans le menu de navigation
2. La liste affiche tous les biens avec leurs informations principales

#### Filtres

- **Recherche** : Recherche par code inventaire ou désignation
- **Catégorie** : Filtrer par type de bien (véhicules, immeubles, équipements)
- **Service** : Filtrer par service actuel
- **Statut** : Filtrer par statut (actif, en maintenance, réformé, cédé)

#### Colonnes

- Code inventaire
- Désignation
- Catégorie
- Service actuel
- Statut
- Valeur d'acquisition
- Actions (Voir, Modifier)

### 5.2 Créer un Bien

1. Cliquez sur "Nouveau bien" dans le tableau de bord ou dans la liste des biens
2. Remplissez le formulaire :
   - **Code inventaire** : Identifiant unique du bien (ex: VH-2026-001)
   - **Désignation** : Nom du bien (ex: Toyota Land Cruiser 2023)
   - **Catégorie** : Sélectionnez la catégorie (véhicules, immeubles, équipements, mobilier)
   - **Date d'acquisition** : Date d'acquisition du bien
   - **Mode d'acquisition** : Achat, don, legs, transfert
   - **Valeur d'acquisition** : Montant en FCFA
   - **État** : Neuf, bon, moyen, mauvais, hors service
   - **Service actuel** : Service auquel le bien est affecté
   - **Description** : Description détaillée (optionnel)
3. Cliquez sur "Créer le bien"

### 5.3 Voir un Bien

1. Dans la liste des biens, cliquez sur "Voir"
2. La fiche bien s'affiche avec 4 onglets :

#### Onglet Général
- Informations générales (code, désignation, catégorie, service)
- Valorisation (valeur d'acquisition, date d'acquisition, VNC)
- Statut actuel

#### Onglet Technique
- Caractéristiques techniques
- État
- Durée de vie estimée

#### Onglet Affectations
- Historique des affectations
- Service actuel

#### Onglet Maintenance
- Historique des maintenances
- Dernières interventions

### 5.4 Modifier un Bien

1. Dans la fiche bien, cliquez sur "Modifier"
2. Modifiez les champs nécessaires
3. Cliquez sur "Enregistrer les modifications"

### 5.5 Affecter un Bien

1. Dans la fiche bien, cliquez sur "Affecter"
2. Sélectionnez le nouveau service
3. Sélectionnez la date d'affectation
4. Cliquez sur "Affecter"

### 5.6 Réformer un Bien

1. Dans la fiche bien, cliquez sur "Réformer"
2. Confirmez la réforme
3. Le bien passe en statut "réformé"

---

## 6. Gestion des Maintenances

### 6.1 Liste des Maintenances

Pour accéder à la liste des maintenances :
1. Cliquez sur "Maintenances" dans le menu de navigation
2. La liste affiche toutes les maintenances

#### Filtres

- **Statut** : Planifié, en cours, terminé, annulé
- **Type** : Préventive, corrective

#### Colonnes

- Bien concerné
- Type (préventive/corrective)
- Date prévue
- Statut
- Coût estimé
- Actions (Clôturer, Voir)

### 6.2 Créer une Maintenance

1. Cliquez sur "Nouvelle maintenance" dans le tableau de bord
2. Remplissez le formulaire :
   - **Bien** : Sélectionnez le bien concerné
   - **Type** : Préventive ou corrective
   - **Date prévue** : Date de la maintenance
   - **Coût estimé** : Montant estimé en FCFA
   - **Description** : Description de l'intervention
   - **Prestataire** : Prestataire externe (optionnel)
3. Cliquez sur "Créer"

### 6.3 Clôturer une Maintenance

1. Dans la liste des maintenances, cliquez sur "Clôturer"
2. Confirmez la clôture
3. Entrez le coût réel (optionnel)
4. Cliquez sur "Clôturer"

---

## 7. Gestion des Inventaires

### 7.1 Liste des Campagnes d'Inventaire

Pour accéder à la liste des campagnes :
1. Cliquez sur "Inventaires" dans le menu de navigation
2. La liste affiche toutes les campagnes d'inventaire

#### Colonnes

- Titre de la campagne
- Période (date début - date fin)
- Inspecteur responsable
- Statut
- Progression
- Actions (Clôturer, Voir)

### 7.2 Créer une Campagne d'Inventaire

1. Cliquez sur "Campagne d'inventaire" dans le tableau de bord
2. Remplissez le formulaire :
   - **Titre** : Nom de la campagne (ex: Inventaire annuel 2026)
   - **Description** : Description de la campagne
   - **Date de début** : Date de début de l'inventaire
   - **Date de fin** : Date de fin de l'inventaire
   - **Inspecteur** : Sélectionnez l'inspecteur responsable
3. Cliquez sur "Créer"

### 7.3 Clôturer une Campagne

1. Dans la liste des campagnes, cliquez sur "Clôturer"
2. Confirmez la clôture
3. La campagne passe en statut "terminé"

---

## 8. Déconnexion

Pour vous déconnecter :
1. Cliquez sur votre nom en haut à droite
2. Cliquez sur "Déconnexion"
3. Vous serez redirigé vers la page de connexion

---

## 9. Bonnes Pratiques

### 9.1 Gestion des Biens

- Utilisez des codes inventaire cohérents (ex: VH-2026-001 pour véhicules)
- Décrivez précisément les biens pour faciliter la recherche
- Mettez à jour régulièrement l'état des biens
- Affectez systématiquement les biens aux services

### 9.2 Gestion des Maintenances

- Planifiez les maintenances préventives régulièrement
- Documentez toutes les interventions
- Clôturez les maintenances après réalisation
- Comparez les coûts estimés et réels

### 9.3 Gestion des Inventaires

- Planifiez les inventaires annuels
- Utilisez des périodes suffisantes pour couvrir tous les biens
- Assignez des inspecteurs compétents
- Clôturez les campagnes après validation

---

## 10. Dépannage

### 10.1 Problèmes de Connexion

- Vérifiez votre email et mot de passe
- Contactez l'administrateur si vous avez oublié vos identifiants
- Assurez-vous que le service est accessible

### 10.2 Données Non Affichées

- Vérifiez votre connexion internet
- Actualisez la page
- Contactez l'administrateur si le problème persiste

### 10.3 Erreurs de Création/Modification

- Vérifiez que tous les champs obligatoires sont remplis
- Vérifiez que les données sont valides
- Contactez l'administrateur si le problème persiste

---

## 11. Support

Pour toute question ou problème :
- Contactez l'administrateur système
- Consultez la documentation technique
- Consultez le journal de développement

---

## 12. Glossaire

- **Bien** : Actif du patrimoine de l'État (bâtiment, véhicule, équipement)
- **VNC** : Valeur Net Comptable (valeur après amortissement)
- **Maintenance préventive** : Maintenance planifiée pour éviter les pannes
- **Maintenance corrective** : Maintenance suite à une panne
- **Campagne d'inventaire** : Opération de comptage et de vérification des biens
- **Affectation** : Attribution d'un bien à un service
- **Réforme** : Mise hors service d'un bien
- **Cession** : Transfert de propriété d'un bien

---

**Version du guide :** 1.0

**Date de mise à jour :** 15 Novembre 2026
