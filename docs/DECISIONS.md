# Décisions d'architecture

## Design et Interface

### Contexte
Le fichier HTML exporté de Figma ne contient que le code de chargement du bundler JavaScript, pas le design réel des maquettes. Impossible d'extraire les couleurs, typographie, espacements, composants graphiques et structure des 14 écrans directement de ce fichier.

### Décision
L'utilisateur a demandé de reproduire fidèlement les 14 écrans de la maquette Figma, mais le fichier HTML fourni ne contient pas les données visuelles nécessaires.

**Options disponibles :**
1. Utiliser le lien Figma direct (https://www.figma.com/design/n0Ykk3lKV5zLpNfeRheqxe/Maquette-Patrimoine-Etat?node-id=0-1) - nécessite l'accès au serveur Figma MCP
2. Adapter l'interface selon les exigences du brief (section 2.2) :
   - Couleur d'accent unique : vert institutionnel (emerald green)
   - Design sobre et institutionnel
   - Typographie : Inter
   - Transitions : 200-250 ms
   - Responsive mobile d'abord
   - Contrastes AA, accessibilité

### Alternative écartée
Extraction directe des tokens de design depuis le fichier HTML exporté (impossible - fichier ne contient que le bundler).

### Besoin d'accès Figma
Pour reproduire fidèlement les 14 écrans, l'accès direct au fichier Figma est nécessaire. Le serveur Figma MCP est disponible mais n'a pas pu être initialisé (erreur de validation des paramètres).

## Hébergeur
**Contexte :** Besoin d'un hébergeur gratuit pour la démonstration.
**Décision :** Render choisi comme hébergeur gratuit.
**Raison :** Support Docker, générosité de temps d'exécution, interface simple.
**Alternative écartée :** Railway (crédits limités), Fly.io (complexité régions).

## Accès Figma
**Contexte :** Besoin de créer des maquettes pour l'interface.
**Décision :** Alternative documentée dans docs/design/ (tokens, descriptions, HTML statiques).
**Raison :** Le serveur Figma MCP n'a pas les droits d'édition nécessaires pour créer un fichier.
**Alternative écartée :** Attendre les droits Figma (bloquerait le projet).
**Note :** L'utilisateur devra créer les maquettes Figma lui-même en se basant sur la documentation fournie.

## Taux journalier
**Contexte :** Besoin d'un taux pour la proposition financière.
**Décision :** 75 000 FCFA/jour.
**Raison :** Taux intermédiaire standard demandé par l'utilisateur.
**Alternative écartée :** Aucune.

## Auteur documents
**Contexte :** Nom à faire figurer sur les documents.
**Décision :** Duah Prince Yao AMANKWAAH.
**Raison :** Nom indiqué dans le brief et confirmé par l'utilisateur.
**Alternative écartée :** Aucune.

## Identité visuelle
**Contexte :** Couleur d'accent et identité visuelle pour l'interface.
**Décision :** Vert institutionnel comme couleur d'accent, identité sobre.
**Raison :** Direction institutionnelle demandée dans le brief, vert institutionnel recommandé.
**Alternative écartée :** Dégradés violet/bleu (vibe coder interdit).
