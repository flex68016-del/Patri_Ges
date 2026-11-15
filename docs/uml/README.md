# Diagrammes UML

Ce dossier contient les diagrammes UML du projet de gestion du patrimoine de l'État.

Les versions **rasterisées PNG** destinées à la soutenance se trouvent dans [`png/`](png/) (13 images générées avec Mermaid CLI).

## Diagramme de cas d'utilisation global

**Explication :** Ce diagramme présente l'ensemble des cas d'utilisation du système, regroupés par acteur (Administrateur, Gestionnaire du patrimoine, Responsable de service, Inspecteur). Il montre les interactions principales entre les acteurs et le système, ainsi que les dépendances entre cas d'utilisation (par exemple, consulter un bien inclut la consultation de son historique).

## Diagramme de cas d'utilisation - Service Auth

**Explication :** Ce diagramme se concentre sur le service d'authentification. Il détaille les actions spécifiques à la gestion des identités et des accès : connexion, déconnexion, gestion des utilisateurs, des rôles et des services. L'administrateur a les droits de gestion, tandis que les utilisateurs standard peuvent se connecter et gérer leur mot de passe.

## Diagramme de cas d'utilisation - Service Patrimoine

**Explication :** Ce diagramme couvre le service patrimoine, cœur du système. Il montre comment le Gestionnaire du patrimoine gère les biens (création, modification, affectation, transfert, réforme, cession), comment le Responsable de service consulte les biens de son service, et comment l'Inspecteur accède aux données en lecture seule pour les audits.

## Diagramme de cas d'utilisation - Service Opérations

**Explication :** Ce diagramme représente le service opérations qui gère la maintenance et les inventaires. Le Gestionnaire planifie et clôture les maintenances, le Responsable de service déclare des anomalies, et l'Inspecteur conduit les campagnes d'inventaire et produit les rapports d'écarts.

## Diagramme de classes du domaine

**Explication :** Ce diagramme modélise les entités métier et leurs relations. Il montre la structure des données : Services, Catégories, Biens (avec spécialisations pour immobilier, véhicule, équipement), Affectations, Mouvements, Cessions, Utilisateurs, Maintenances, Campagnes d'inventaire, Lignes d'inventaire et Journal d'audit. Les relations (1:1, 1:N, N:M) sont explicitées pour guider la conception de la base de données.

## Diagramme de séquence - Connexion

**Explication :** Ce diagramme décrit le flux de connexion d'un utilisateur. L'utilisateur saisit ses identifiants, le service web les transmet au service d'authentification, qui vérifie le mot de passe, génère un JWT (RS256) et journalise la connexion. Le service web conserve le jeton en session serveur et l'utilise pour appeler les API métier. La déconnexion invalide la session et régénère le jeton CSRF.

## Diagramme de séquence - Affectation d'un bien

**Explication :** Ce diagramme montre le processus d'affectation d'un bien à un service. Le Gestionnaire soumet le formulaire, le service patrimoine valide les données, clôture l'affectation active précédente (si elle existe), crée la nouvelle affectation, met à jour le service courant du bien, crée un mouvement d'historique, et enregistre l'action dans le journal d'audit. Tout est effectué dans une transaction.

## Diagramme de séquence - Clôture d'une maintenance

**Explication :** Ce diagramme illustre le processus de clôture d'une maintenance. Le gestionnaire saisit la date effective et le coût final ; le service opérations vérifie les données, passe la maintenance au statut « terminé » et conserve la traçabilité complète (date planifiée, date effective, coût).

## Diagramme de séquence - Clôture d'une campagne d'inventaire

**Explication :** Ce diagramme décrit la clôture d'une campagne d'inventaire. L'inspecteur ou le gestionnaire demande la clôture ; le service opérations vérifie que la campagne est ouverte, compte les constats enregistrés (présents, absents, dégradés) et passe la campagne au statut « terminé ». Le rapport des écarts reste consultable après clôture.

## Diagramme d'états - Statut d'un bien

**Explication :** Ce diagramme modélise les états possibles d'un bien au cours de son cycle de vie : disponible, affecté, en maintenance, réformé, cédé. Les transitions sont déclenchées par des événements métier (affectation, maintenance, réforme, cession). Une fois réformé ou cédé, le bien ne peut plus être affecté ni entretenu.

## Diagramme d'activités - Cycle de vie d'un bien

**Explication :** Ce diagramme montre le flux complet du cycle de vie d'un bien, de son acquisition à sa cession ou son archivage. Il illustre les décisions (affectation immédiate, maintenance demandée, réforme, transfert) et les actions correspondantes. Le bien peut passer par plusieurs affectations et maintenances avant d'être réformé puis cédé.

## Diagramme de composants

**Explication :** Ce diagramme présente l'architecture en microservices. L'interface Web (Laravel Blade + Alpine.js + Tailwind CSS) s'authentifie auprès du service Auth (JWT RS256) puis consomme les API Patrimoine et Opérations via un client HTTP interne. Chaque service possède sa propre base PostgreSQL. Chaque service API vérifie la signature du jeton avec la clé publique et valide l'utilisateur actif auprès du service Auth (`GET /api/me`) ; le contrôle des rôles est appliqué par middleware sur chaque endpoint. Les services communiquent entre eux par REST/JSON.

## Diagramme de déploiement

**Explication :** Ce diagramme montre deux environnements : développement (Docker Compose) et production (hébergeur acceptant Docker). En développement, tous les services (Nginx, 3 services Laravel, PostgreSQL) tournent dans des conteneurs Docker sur un réseau interne, avec un volume pour la persistance des données. En production, chaque service est déployé comme un conteneur indépendant derrière la gateway, avec une base de données PostgreSQL externe. L'architecture reste identique, seule l'infrastructure change.
