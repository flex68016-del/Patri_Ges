# Diagrammes UML

Ce dossier contient les diagrammes UML du projet de gestion du patrimoine de l'État.

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

**Explication :** Ce diagramme décrit le flux de connexion d'un utilisateur. L'utilisateur saisit ses identifiants, l'interface les transmet au service d'authentification, qui vérifie le mot de passe, génère un JWT (RS256) avec l'identifiant, le rôle et le service_id, l'enregistre dans le journal d'audit et renvoie le token. L'interface stocke le token en session chiffrée côté serveur (jamais dans localStorage).

## Diagramme de séquence - Affectation d'un bien

**Explication :** Ce diagramme montre le processus d'affectation d'un bien à un service. Le Gestionnaire soumet le formulaire, le service patrimoine valide les données, clôture l'affectation active précédente (si elle existe), crée la nouvelle affectation, met à jour le service courant du bien, crée un mouvement d'historique, et enregistre l'action dans le journal d'audit. Tout est effectué dans une transaction.

## Diagramme de séquence - Clôture d'une maintenance

**Explication :** Ce diagramme illustre le pattern saga simplifié pour la clôture d'une maintenance. Le service opérations appelle d'abord le service patrimoine pour restaurer le statut du bien (de "en maintenance" à son statut précédent). Si cet appel échoue, le service opérations annule sa propre transaction (rollback). Si l'appel réussit, le service opérations clôture la maintenance et enregistre l'action. Cela garantit la cohérence des données entre services.

## Diagramme de séquence - Clôture d'une campagne d'inventaire

**Explication :** Ce diagramme décrit la clôture d'une campagne d'inventaire. L'Inspecteur demande la clôture, le service opérations vérifie que la campagne est ouverte, compte les lignes d'inventaire (présents, absents, dégradés), met à jour le statut de la campagne à "clôturée", la marque comme immuable, enregistre l'action dans le journal d'audit et renvoie le rapport des écarts.

## Diagramme d'états - Statut d'un bien

**Explication :** Ce diagramme modélise les états possibles d'un bien au cours de son cycle de vie : En stock, Affecté, En maintenance, Réformé, Cédé. Les transitions sont déclenchées par des événements métier (affectation, maintenance, réforme, cession). Une fois réformé ou cédé, le bien ne peut plus revenir à un état actif.

## Diagramme d'activités - Cycle de vie d'un bien

**Explication :** Ce diagramme montre le flux complet du cycle de vie d'un bien, de son acquisition à sa cession ou son archivage. Il illustre les décisions (affectation immédiate, maintenance demandée, réforme, transfert) et les actions correspondantes. Le bien peut passer par plusieurs affectations et maintenances avant d'être réformé puis cédé.

## Diagramme de composants

**Explication :** Ce diagramme présente l'architecture en micro-services. L'interface Web (Laravel Blade + Alpine + Tailwind) communique via une passerelle Nginx avec les trois services métier (Auth, Patrimoine, Operations). Chaque service a sa propre base PostgreSQL dans un conteneur unique. L'authentification se fait par JWT RS256, vérifié localement par chaque service sans appel réseau. Les services communiquent entre eux via REST/JSON (opérations → patrimoine pour les mises à jour de statut).

## Diagramme de déploiement

**Explication :** Ce diagramme montre deux environnements : développement (Docker Compose) et production (Render). En développement, tous les services (Nginx, 3 services Laravel, PostgreSQL) tournent dans des conteneurs Docker sur un réseau interne, avec un volume pour la persistance des données. En production, chaque service est déployé comme un Web Service sur Render, avec une base de données PostgreSQL externe gérée par Render. L'architecture reste identique, seule l'infrastructure change.
