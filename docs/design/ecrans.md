# Description des Écrans

## 1. Écran de Connexion

### Layout
- Centré verticalement et horizontalement
- Largeur max : 400px
- Fond : blanc avec légère ombre

### Éléments
- **Titre** : "Gestion du Patrimoine de l'État" (3XL, Bold, neutral-900)
- **Sous-titre** : "Connectez-vous pour accéder à la plateforme" (Base, neutral-500)
- **Formulaire** :
  - Email (input text, large)
  - Mot de passe (input password, large)
  - Bouton "Se connecter" (primary, full width)
- **Lien** : "Mot de passe oublié ?" (texte, small, primary-500)
- **Info** : Texte de pied de page "© 2026 - République Togolaise" (small, neutral-400)

### État
- Validation des champs en temps réel
- Bouton désactivé si formulaire invalide
- Message d'erreur si identifiants incorrects

---

## 2. Tableau de Bord

### Layout
- Header : logo + nom utilisateur + menu déroulant (déconnexion)
- Contenu : grille de cartes KPI + graphiques

### Header
- Logo gauche (vert institutionnel)
- Titre "Tableau de bord" (center)
- Profil utilisateur droite (avatar + nom + menu)

### Cartes KPI (4 cartes)
- **Total biens** : nombre (4XL, Bold) + icône + pourcentage évolution
- **Valeur totale** : montant en FCFA (2XL, Bold) + icône
- **Biens affectés** : nombre + barre de progression
- **Maintenances en retard** : nombre (error) + bouton "Voir"

### Graphiques
- **Répartition par type** : diagramme circulaire (Chart.js)
- **Répartition par service** : diagramme à barres horizontales
- **État des biens** : diagramme à barres empilées

### Actions rapides
- Boutons : "Nouveau bien", "Nouvelle maintenance", "Campagne d'inventaire"

---

## 3. Liste des Biens

### Layout
- Header : titre + bouton "Nouveau bien" + filtres
- Tableau paginé

### Header
- Titre "Biens" (2XL, Bold)
- Bouton "Nouveau bien" (primary)
- Barre de recherche (input avec icône)
- Filtres : type, catégorie, service, état, statut (dropdowns)

### Tableau
Colonnes :
- Code inventaire (lien vers fiche)
- Désignation
- Catégorie
- Service actuel
- État (badge coloré)
- Statut (badge coloré)
- Valeur nette comptable
- Actions (menu : voir, modifier, affecter, maintenance)

### Pagination
- Bas de page : "Affichage 1-20 sur 156"
- Boutons : Précédent, 1, 2, 3, Suivant

---

## 4. Fiche d'un Bien (Onglets)

### Layout
- Header : breadcrumb + titre + actions
- Contenu : onglets + panneau

### Header
- Breadcrumb : Accueil > Biens > TG-VEH-2026-000123
- Titre : désignation du bien (2XL, Bold)
- Actions : Modifier, Historique, Rapport

### Onglets
1. **Informations**
   - Code inventaire
   - Désignation
   - Catégorie
   - Date d'acquisition
   - Mode d'acquisition
   - Valeur d'acquisition
   - Valeur nette comptable (calculée)
   - État
   - Statut
   - Service actuel
   - Champs spécifiques (selon type)

2. **Affectations**
   - Liste chronologique
   - Pour chaque : service, dates, motif, référence acte

3. **Maintenances**
   - Liste chronologique
   - Pour chaque : type, dates, coût, prestataire, statut

4. **Historique**
   - Timeline complète
   - Pour chaque : type, date, auteur, détail

### Actions onglet
- Bouton selon contexte : "Affecter", "Maintenance", "Réformer", "Céder"

---

## 5. Formulaire de Création de Bien

### Layout
- Header : titre + annuler / enregistrer
- Formulaire en sections

### Header
- Titre "Nouveau bien" (2XL, Bold)
- Boutons : Annuler (secondary), Enregistrer (primary)

### Sections
1. **Informations générales**
   - Désignation (input text, required)
   - Catégorie (select, required)
   - Type de bien (select, required)

2. **Acquisition**
   - Date d'acquisition (date picker, required)
   - Mode d'acquisition (select : achat, don, transfert, construction)
   - Valeur d'acquisition (input number, required)

3. **État initial**
   - État (select : neuf, bon, moyen, dégradé, hors service)
   - Service initial (select)

4. **Champs spécifiques**
   - Selon type de bien (immobilier, véhicule, équipement)

### Validation
- Validation en temps réel
- Messages d'erreur sous chaque champ
- Bouton enregistrer désactivé si invalide

---

## 6. Formulaire d'Affectation

### Layout
- Modal centrée

### Éléments
- Titre "Affecter le bien" (XL, Bold)
- Description du bien (card)
- Formulaire :
  - Service (select, required)
  - Date de début (date picker, required)
  - Motif (textarea)
  - Référence de l'acte (input text)
- Boutons : Annuler (secondary), Affecter (primary)

---

## 7. Formulaire de Maintenance

### Layout
- Modal centrée

### Éléments
- Titre "Planifier une maintenance" (XL, Bold)
- Description du bien (card)
- Formulaire :
  - Type (select : préventive, corrective)
  - Date prévue (date picker, required)
  - Description (textarea, required)
  - Prestataire estimé (input text)
- Boutons : Annuler (secondary), Planifier (primary)

### Clôture de maintenance
- Date de fin (date picker)
- Coût réel (input number)
- Prestataire (input text)
- Description finale (textarea)
- Boutons : Annuler, Clôturer

---

## 8. Campagne d'Inventaire

### Layout
- Header : titre + bouton
- Liste des campagnes + formulaire création

### Header
- Titre "Campagnes d'inventaire" (2XL, Bold)
- Bouton "Nouvelle campagne" (primary)

### Liste des campagnes
- Tableau : service, période, statut, inspecteur, actions
- Statuts : ouverte (success), clôturée (neutral)

### Formulaire création
- Service (select, required)
- Période (date range picker, required)
- Boutons : Annuler, Créer

### Saisie des constats
- Liste des biens du service
- Pour chaque :
  - Photo du bien (optionnel)
  - Constat (radio : présent, absent, dégradé)
  - Observation (textarea)
- Barre de progression : "12/50 biens constatés"
- Boutons : Sauvegarder brouillon, Clôturer campagne

### Rapport des écarts
- Tableau : code, désignation, constat, observation
- Sections : Biens absents, Biens dégradés
- Actions : Exporter PDF, Exporter CSV

---

## Design System Mobile

### Adaptations
- Header compact (hamburger menu)
- Tableaux en mode carte sur mobile
- Grille à 1 colonne
- Boutons full width
- Filtres dans drawer/sheet

### Navigation
- Bottom navigation bar : Accueil, Biens, Maintenances, Inventaire, Profil
