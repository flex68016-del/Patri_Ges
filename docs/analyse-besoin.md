# Analyse du besoin

## 3.1 Contexte et problème

L'État possède un patrimoine étendu et dispersé : bâtiments, terrains, véhicules, matériel informatique et de bureau, équipements. Dans beaucoup d'administrations, son suivi repose sur des registres papier et des tableurs : un bien est difficile à localiser, son affectation et son historique ne sont pas tracés, les maintenances sont oubliées, les inventaires sont lourds, la valeur comptable n'est pas suivie et les écarts sont découverts trop tard. Il en résulte des pertes, des doublons, des biens inutilisés ou non entretenus et un contrôle difficile.

## 3.2 Objectifs

- Disposer d'un référentiel unique et fiable des biens de l'État.
- Tracer la vie de chaque bien : acquisition, affectation, maintenance, réforme, cession.
- Responsabiliser les services (qui détient quoi, depuis quand).
- Faciliter les inventaires et détecter les écarts.
- Suivre la valeur des biens (amortissement linéaire).
- Fournir des indicateurs de pilotage (nombre, valeur, état, répartition par service, maintenances en retard).

## 3.3 Acteurs et rôles

| Rôle | Description | Droits principaux |
|------|-------------|-------------------|
| Administrateur | Gère l'accès à la plateforme | Utilisateurs, rôles, référentiels (services, catégories) |
| Gestionnaire du patrimoine | Agent de l'administration centrale | Tous les biens : création, modification, affectation, réforme, cession, validation |
| Responsable de service | Chef d'un ministère, d'une direction ou d'un service | Biens de son service : consultation, demandes de maintenance, déclaration d'anomalie, participation à l'inventaire |
| Inspecteur / auditeur | Contrôle | Lecture sur tout le périmètre, conduite des campagnes d'inventaire, rapports |

## 3.4 Périmètre

**Inclus (MVP) :**
- Authentification et rôles
- Référentiel des services et catégories
- Gestion des biens (immeubles, véhicules, équipements)
- Affectations et transferts
- Valorisation
- Maintenance
- Campagnes d'inventaire et écarts
- Réforme et cession
- Historique des mouvements
- Tableau de bord
- Interface web responsive

**Exclu :**
- Comptabilité publique réelle
- Interconnexion avec des systèmes de l'État
- Paiement
- Application mobile native
- Cartographie SIG
- Gestion documentaire avancée
- Notifications par e-mail ou SMS

## 3.5 Exigences fonctionnelles

### Service d'authentification (auth)

| ID | Exigence | Prio |
|----|----------|------|
| FR-AUTH-01 | Connexion par e-mail et mot de passe, émission d'un jeton signé | M |
| FR-AUTH-02 | Déconnexion et expiration des jetons, renouvellement | M |
| FR-AUTH-03 | Gestion des utilisateurs par l'administrateur (créer, modifier, désactiver) | M |
| FR-AUTH-04 | Rôles et permissions, rattachement d'un utilisateur à un service | M |
| FR-AUTH-05 | Changement de mot de passe | S |
| FR-AUTH-06 | Journal des connexions | C |

### Service patrimoine

| ID | Exigence | Prio |
|----|----------|------|
| FR-PAT-01 | Référentiel des services (hiérarchie ministère > direction > service) et des catégories de biens | M |
| FR-PAT-02 | Créer, modifier et consulter un bien ; code d'inventaire généré automatiquement (ex. `TG-VEH-2026-000123`) ; attributs spécifiques par type | M |
| FR-PAT-03 | Recherche, filtres (type, catégorie, service, état, statut) et pagination | M |
| FR-PAT-04 | Affectation d'un bien à un service, avec date, motif et référence de l'acte | M |
| FR-PAT-05 | Transfert d'un bien d'un service à un autre (clôture de l'affectation en cours) | M |
| FR-PAT-06 | Calcul de la valeur nette comptable (amortissement linéaire) | M |
| FR-PAT-07 | Mise en réforme d'un bien, puis cession (vente, don, destruction) avec validation | M |
| FR-PAT-08 | Historique complet et immuable des mouvements d'un bien | M |
| FR-PAT-09 | Export CSV de la liste des biens | S |
| FR-PAT-10 | Statistiques (nombre, valeur, répartition par type, état et service) | M |
| FR-PAT-11 | Coordonnées GPS d'un immeuble | C |

### Service opérations (maintenance et inventaire)

| ID | Exigence | Prio |
|----|----------|------|
| FR-OPS-01 | Planifier une maintenance (préventive ou corrective) sur un bien | M |
| FR-OPS-02 | Démarrer, clôturer une maintenance (date, coût, prestataire) | M |
| FR-OPS-03 | Liste des maintenances en retard et à venir | M |
| FR-OPS-04 | Ouvrir une campagne d'inventaire pour un service et une période | M |
| FR-OPS-05 | Saisir un constat par bien (présent, absent, dégradé) avec observation | M |
| FR-OPS-06 | Clôturer la campagne et produire le rapport des écarts | M |
| FR-OPS-07 | Déclaration d'anomalie ou de panne par un responsable de service | S |
| FR-OPS-08 | Statistiques (coûts de maintenance, taux de présence à l'inventaire) | S |

### Interface web

| ID | Exigence | Prio |
|----|----------|------|
| FR-WEB-01 | Connexion, déconnexion, navigation adaptée au rôle | M |
| FR-WEB-02 | Tableau de bord avec indicateurs et graphiques | M |
| FR-WEB-03 | Liste des biens avec filtres, fiche d'un bien à onglets (informations, affectations, maintenances, historique) | M |
| FR-WEB-04 | Formulaires de création, d'affectation, de maintenance, d'inventaire | M |
| FR-WEB-05 | Interface utilisable sur mobile et ordinateur | M |
| FR-WEB-06 | Messages d'erreur et de confirmation clairs en français | M |

## 3.6 Règles de gestion

- **RG-01** Le code d'inventaire d'un bien est unique et immuable.
- **RG-02** Un bien n'a qu'une seule affectation active à un instant donné.
- **RG-03** Un bien réformé ou cédé ne peut plus être affecté ni entretenu.
- **RG-04** Valeur nette comptable = valeur d'acquisition − amortissements cumulés (méthode linéaire sur la durée d'amortissement de la catégorie) ; jamais négative ; les terrains ne sont pas amortissables.
- **RG-05** Une cession exige une réforme préalable et la validation d'un gestionnaire du patrimoine.
- **RG-06** Tout changement d'état, de statut ou d'affectation crée un mouvement ; l'historique est en ajout seul (jamais modifié ni supprimé).
- **RG-07** Un responsable de service ne voit et ne modifie que les données de son service.
- **RG-08** L'inspecteur est en lecture seule, sauf pour la saisie des constats d'inventaire.
- **RG-09** Seul le gestionnaire du patrimoine crée, réforme et cède des biens ; seul l'administrateur gère les utilisateurs.
- **RG-10** Une campagne d'inventaire clôturée est immuable.
- **RG-11** Suppression logique uniquement : aucun bien n'est supprimé physiquement.
- **RG-12** Une maintenance démarrée passe le bien au statut « en maintenance » ; sa clôture le remet au statut précédent.
- **RG-13** La date d'affectation ne peut pas précéder la date d'acquisition.

## 3.7 Exigences non fonctionnelles

| Domaine | Exigence |
|---------|----------|
| Sécurité | Authentification par jeton signé, autorisations par rôle côté serveur, application des règles de la section 2.5 |
| Traçabilité | Journal d'audit des actions sensibles (qui, quoi, quand) |
| Performance | Pagination partout, index sur les colonnes filtrées, réponse d'une liste inférieure à 500 ms avec les données de démonstration |
| Disponibilité | Chaque service est indépendant ; l'interface affiche un message dégradé si un service est injoignable |
| Portabilité | Démarrage complet avec `docker compose up`, configuration par variables d'environnement |
| Maintenabilité | Tests automatisés, style imposé, documentation à jour |
| Utilisabilité | Responsive, accessible, français uniquement |
| Conformité | Données fictives uniquement ; pas de données personnelles réelles |

## 3.8 User stories

| ID | En tant que… | Je veux… | Afin de… | Prio |
|----|--------------|----------|----------|------|
| US-01 | Administrateur | créer un compte et lui attribuer un rôle et un service | donner un accès contrôlé | M |
| US-02 | Gestionnaire | enregistrer un nouveau bien avec ses caractéristiques | alimenter le référentiel | M |
| US-03 | Gestionnaire | affecter un bien à un service | savoir qui le détient | M |
| US-04 | Gestionnaire | transférer un bien vers un autre service | suivre ses déplacements | M |
| US-05 | Gestionnaire | voir la valeur nette comptable d'un bien | suivre sa valeur | M |
| US-06 | Gestionnaire | mettre un bien en réforme puis le céder | clôturer son cycle de vie | M |
| US-07 | Gestionnaire | consulter l'historique d'un bien | justifier chaque mouvement | M |
| US-08 | Gestionnaire | rechercher et filtrer les biens | retrouver rapidement un bien | M |
| US-09 | Gestionnaire | voir un tableau de bord | piloter le patrimoine | M |
| US-10 | Gestionnaire | exporter la liste des biens en CSV | l'utiliser hors plateforme | S |
| US-11 | Responsable de service | voir les biens de mon service | connaître ce dont je réponds | M |
| US-12 | Responsable de service | demander une maintenance ou déclarer une panne | faire réparer un bien | M |
| US-13 | Gestionnaire | planifier, démarrer et clôturer une maintenance | garder les biens en état | M |
| US-14 | Gestionnaire | voir les maintenances en retard | agir à temps | M |
| US-15 | Inspecteur | ouvrir une campagne d'inventaire pour un service | vérifier l'existant | M |
| US-16 | Inspecteur | saisir des constats bien par bien | enregistrer l'état réel | M |
| US-17 | Inspecteur | clôturer la campagne et obtenir le rapport des écarts | détecter les manquants | M |
| US-18 | Tout utilisateur | me connecter et me déconnecter en sécurité | protéger mes données | M |
| US-19 | Tout utilisateur | utiliser la plateforme sur téléphone | travailler sur le terrain | M |

## 3.9 Entités métier

À affiner en phase de conception, sans changer le périmètre.

- **Service** (`services`) : nom, code, type (ministère, direction, service), parent (auto-référence).
- **Catégorie** (`categories`) : libellé, type de bien (immobilier, véhicule, équipement), durée d'amortissement, amortissable oui/non.
- **Bien** (`biens`) : code d'inventaire, désignation, catégorie, date d'acquisition, mode d'acquisition (achat, don, transfert, construction), valeur d'acquisition, état (neuf, bon, moyen, dégradé, hors service), statut (en stock, affecté, en maintenance, réformé, cédé), service courant. Spécialisations : `biens_immobiliers` (adresse, superficie, titre foncier), `biens_vehicules` (immatriculation, marque, modèle, kilométrage), `biens_equipements` (numéro de série, marque).
- **Affectation** : bien, service, date de début, date de fin, motif, référence de l'acte, auteur.
- **Mouvement** (journal d'historique, ajout seul) : bien, type, date, auteur, détail.
- **Cession** : bien, type (vente, don, destruction), date, valeur de cession, validé par.
- **Utilisateur / Rôle** (service auth) : e-mail, mot de passe haché, rôle, `service_id` (référence logique sans clé étrangère inter-bases), actif.
- **Maintenance** (service opérations) : bien (référence logique + libellé copié), type, statut, date prévue, date de début, date de fin, coût, prestataire, description.
- **Campagne d'inventaire** : service, période, statut, inspecteur. Ligne d'inventaire : campagne, bien, constat (présent, absent, dégradé), observation.
- **Journal d'audit** (par service) : acteur, action, objet, date.

## 3.10 Scénario de démonstration (8 minutes)

`docker compose up` : les services et PostgreSQL démarrent ; `/health` répond.

Connexion en gestionnaire : tableau de bord.
Création d'un véhicule, code généré, valeur nette comptable affichée.
Affectation à une direction, puis transfert vers un autre service ; l'historique montre les deux mouvements.
Maintenance corrective : le statut passe à « en maintenance », puis la clôture le rétablit.
Connexion en inspecteur : campagne d'inventaire sur un service, constats, un bien déclaré absent, rapport des écarts.
Réforme puis cession d'un bien ancien, validation, historique à jour.
Tableau de bord mis à jour, démonstration d'un refus d'accès (un responsable de service tente de lire un autre service).

## 3.11 Risques

| Risque | Parade |
|--------|--------|
| Délai de 6 jours | MVP figé, ordre de coupe défini en 2.7 |
| Limites de l'hébergeur gratuit (mémoire, mise en veille) | Déployer un service minimal dès le 11/11, images légères, réveil des services avant la soutenance |
| Complexité des micro-services | 3 services métier seulement, REST synchrone, un jeton vérifié localement, une seule base physique |
| Données incohérentes entre services | Références logiques par UUID, libellés copiés, appels avec délai d'attente et reprise, erreur claire en cas d'échec |
| Données de démonstration pauvres | Seeders réalistes obligatoires (au moins 6 services, 60 biens, 15 mouvements par bien type, 10 maintenances, 1 campagne) |
