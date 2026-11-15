# Décisions d'architecture

Journal des décisions techniques et organisationnelles du projet, avec leur contexte, la décision retenue, sa justification et les alternatives écartées.

---

## Architecture microservices

**Contexte :** La plateforme gère trois domaines métier distincts (identité, patrimoine, opérations) devant pouvoir évoluer et être déployés indépendamment.

**Décision :** Architecture en 4 services Laravel + gateway Nginx : `auth-service`, `patrimoine-service`, `operations-service`, `web`, chacun avec sa propre base PostgreSQL.

**Raison :** Isolation des contextes bornés, scalabilité indépendante, alignement avec l'exigence de découpage du brief.

**Alternatives écartées :** Monolithe Laravel (plus simple mais ne répond pas à l'exigence d'architecture microservices) ; découpage plus fin (coût de complexité disproportionné pour le périmètre).

---

## Service web sans base de données

**Contexte :** Le service `web` sert l'interface utilisateur et doit consommer les API métier.

**Décision :** `web` est un Backend-for-Frontend sans base propre : il conserve le JWT en session serveur et appelle les API via un client HTTP interne (`ApiClient`).

**Raison :** Évite la duplication des données et des règles métier ; l'interface ne contient aucune logique de persistance.

**Alternatives écartées :** Données en localStorage côté navigateur (exposition du token) ; base partagée entre services (couplage fort).

---

## Authentification JWT RS256

**Contexte :** Les services doivent s'authentifier mutuellement sans session partagée.

**Décision :** Jetons JWT signés en RS256 ; la clé privée est confinée à `auth-service`, les autres services vérifient la signature avec la clé publique et valident l'utilisateur via `GET /api/me`.

**Raison :** Signature asymétrique — vérification locale possible sans exposer la clé de signature ; validation de l'utilisateur actif à chaque requête.

**Alternatives écartées :** Jetons HS256 (clé symétrique partagée entre services, moins sûre) ; sessions serveur partagées (couplage infrastructurel).

---

## Base de données par service

**Contexte :** Trois domaines métier aux cycles de vie et volumes différents.

**Décision :** Une base PostgreSQL par service (`auth_db`, `patrimoine_db`, `operations_db`), communication inter-services par UUID sans clé étrangère physique.

**Raison :** Isolation des données, migrations indépendantes, cohérence avec le découpage microservices.

**Alternatives écartées :** Schéma unique partagé (couplage, migrations conflictuelles) ; base par table (complexité inutile).

---

## Spécialisation des biens

**Contexte :** Les biens sont de trois types (immobilier, véhicule, équipement) avec des attributs spécifiques.

**Décision :** Table `biens` commune + tables `biens_immobiliers`, `biens_vehicules`, `biens_equipements` en relation 1:1, le type étant porté par la catégorie.

**Raison :** Attributs propres à chaque type sans alourdir la table principale ; requêtes simples.

**Alternatives écartées :** Colonnes nullable pour tous les types (table large, intégrité faible) ; table unique avec JSON (perte des contraintes).

---

## Journal des mouvements en ajout seul

**Contexte :** Le contrôle patrimonial exige la traçabilité complète de chaque bien.

**Décision :** Table `mouvements` alimentée automatiquement à chaque transition (création, affectation, transfert, maintenance, réforme, cession), jamais modifiée ni supprimée.

**Raison :** Historique infalsifiable, exigence d'audit.

**Alternatives écartées :** Champs d'historique sur `biens` (perte des événements intermédiaires).

---

## Identité visuelle

**Contexte :** Interface destinée à l'administration publique togolaise.

**Décision :** Vert institutionnel (`#2E7D32`, déclinaisons `#047857`, `#065f46`), typographie Inter, design sobre : grille stricte, espacements généreux, transitions courtes (150–300 ms), pas de dégradés ni d'effets superflus.

**Raison :** Sobriété et lisibilité exigées pour un outil institutionnel ; contraste AA.

**Alternatives écartées :** Identités colorées ou « startup » (inadaptées au contexte administratif).

---

## Taux journalier et paiement
**Contexte :** Proposition financière de la mission (6 jours).
**Décision :** 200 000 FCFA/jour, soit 1 200 000 FCFA au total ; règlement en deux fois : acompte de 60 % (720 000 FCFA) à la signature, solde de 40 % (480 000 FCFA) à la livraison.
**Raison :** Conditions convenues pour la mission.

## Auteur des documents

**Contexte :** Nom à faire figurer sur les livrables.

**Décision :** Duah Prince Yao AMANKWAAH.

---

## Déploiement

**Contexte :** La stack conteneurisée doit être déployée sur un hébergeur accessible.

**Décision :** Déploiement effectué sur **SnapDeploy** (4 conteneurs `small` gratuits, un par service) avec **Neon** pour PostgreSQL managé (projet `patrimoine-gouv`, 3 bases). La procédure générique multi-hébergeurs reste documentée dans `docs/DEPLOIEMENT.md`.

**Raison :** SnapDeploy est la seule plateforme gratuite sans carte bancaire acceptant 4 conteneurs Docker (limite exacte du besoin) ; Neon fournit le PostgreSQL managé gratuitement.

**Alternatives écartées :** Dépendre d'un seul hébergeur (fragilité) ; fusionner les services en un conteneur unique (perte de l'isolation microservices, risque mémoire).

---

## Persistance des sessions web

**Contexte :** Le service `web` est sans état propre et doit conserver le JWT entre les requêtes.

**Décision :** Sessions serveur Laravel (`SESSION_DRIVER=file`), JWT conservé en session, jamais exposé au navigateur.

**Raison :** Le token ne quitte pas le serveur ; protection contre le vol via XSS.

**Alternatives écartées :** `localStorage` (vulnérable au XSS) ; cookies portant le JWT (surface de vol élargie).
