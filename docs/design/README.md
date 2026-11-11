# Documentation de Design

Ce dossier contient la documentation de design pour le système de gestion du patrimoine de l'État.

## Contenu

- **tokens.md** : Tokens de design (couleurs, typographie, espacements, rayons, ombres, transitions, breakpoints)
- **ecrans.md** : Description détaillée de chaque écran (layout, éléments, états, interactions)

## Pour l'utilisateur

Vous pouvez utiliser cette documentation pour créer les maquettes Figma :

### Étapes dans Figma

1. **Créer les variables**
   - Allez dans Variables (Local)
   - Créez les collections de couleurs, typographie, espacements, rayons, ombres
   - Utilisez les valeurs spécifiées dans tokens.md

2. **Créer les styles**
   - Styles de texte basés sur la typographie
   - Styles d'effet pour les ombres

3. **Créer les composants de base**
   - Boutons (primary, secondary, danger)
   - Inputs (text, password, select, date)
   - Badges (pour états et statuts)
   - Cards
   - Modales

4. **Créer les écrans**
   - Suivez les descriptions dans ecrans.md
   - Utilisez les composants de base
   - Appliquez les tokens
   - Créez les variantes mobile/desktop

5. **Prototype**
   - Créez des liens entre les écrans
   - Définissez les transitions (ease-out, 150-300ms)
   - Testez le parcours de démonstration (section 3.10 du brief)

## Couleur d'accent

Vert institutionnel : `#2E7D32`

## Style

- Institutionnel, sobre, lisible
- Grille stricte
- Beaucoup d'espace
- Hiérarchie claire
- Pas de dégradés, pas de glassmorphism gratuit
- Motion simple (150-300ms, ease-out)

## Note

Le serveur Figma MCP n'avait pas les droits d'édition nécessaires pour créer les maquettes directement. Cette documentation sert de référence complète pour créer les maquettes manuellement dans Figma.
