# Fullstack Challenge

Ce test technique est conçu pour évaluer vos compétences en développement fullstack. Il s'agit d'un projet de gestion d'annonces immobilières et leur diffusion vers différentes plateformes externes (SeLoger, LeBonCoin, etc.).

Le test est prévu pour être réalisé en 1h à 2h environ. Il ne s'agit pas d'implémenter une solution complète et parfaite, mais plutôt de démontrer votre approche technique, votre capacité à concevoir une architecture modulaire et extensible, et votre compréhension des bonnes pratiques de développement.

Le code existant fournit déjà les entités de base et la structure du projet, vous permettant de vous concentrer sur l'implémentation des fonctionnalités demandées sans avoir à configurer l'environnement de développement depuis zéro.


## Installation

Ce projet a été initialisé depuis le template [Symfony Docker](https://github.com/dunglas/symfony-docker?tab=readme-ov-file#symfony-docker) et Vite (React + TypeScript).

1. Si ce n'est pas déjà fait, [installez Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Exécutez `docker compose build --no-cache` pour construire des images à jour
3. Exécutez `docker compose up --pull always -d --wait` pour démarrer le projet
    - Les dépendances devraient être installées automatiquement, si ce n'est pas le cas, exécutez `docker compose exec php composer install` et `docker compose exec php yarn install`
    - Si vous avez une erreur de type `pull access denied for app-php...`, vous pouvez l'ignorer (voir l'[issue](https://github.com/dunglas/symfony-docker/issues/664#issuecomment-2336412043))
4. Ouvrez `https://localhost` dans votre navigateur web préféré et [acceptez le certificat TLS auto-généré](https://stackoverflow.com/a/15076602/1352334)
5. Exécutez `docker compose exec php php bin/console doctrine:migrations:migrate` pour créer la base de données et les tables
6. Exécutez `docker compose exec php php bin/console doctrine:fixtures:load` pour charger les données initiales

## Objectifs

Ce projet combine une API Symfony et une interface React. Des entités représentant des annonces immobilières et leur diffusion sur diverses passerelles (SeLoger, LeBonCoin, etc.) sont déjà en place. Le test se divise en deux parties :

### Backend (API Symfony)

#### 1. Architecture d'export des annonces
- Concevoir une architecture technique modulaire et élégante pour gérer l'export des annonces
- Inutile d'implémenter de vrais appels API dans les classes des passerelles, vous pouvez simuler les réponses API
- Stocker l'historique complet des exports en base de données avec statuts et métadonnées (en simulant comme vu au-dessus)
    - Un export représente une passerelle et une annonce exportée à un moment T, inutile de garder l'historique des différents status, simplement le statut final ou planifié par exemple
- Choisir une approche appropriée: synchrone ou asynchrone selon votre analyse des besoins
- Documenter votre choix d'architecture si possible (un schéma ou diagramme serait apprécié)

#### 2. Extensibilité
- L'architecture doit permettre l'ajout facile de nouvelles passerelles
- Utiliser des design patterns adaptés pour garantir cette extensibilité

#### 3. Interface d'utilisation
- Créer une méthode pour déclencher les exports (API REST, commande CLI, interface web...), aller au plus simple
- Développer une route API pour récupérer les exports (avec filtrage et tri serait un plus)

### Frontend (React)

#### 1. Tableau de bord des exports
- Créer un composant affichant l'historique des exports avec leur statut
- Intégrer ce composant dans l'interface existante
- Afficher des informations que vous jugerez pertinentes

#### 2. Expérience utilisateur (optionnel)
- Implémenter la pagination des résultats
- Ajouter des options de tri et de filtrage
- Gérer les états de chargement et les erreurs

Cette partie est optionnelle, mais est un plus si vous avez le temps.

### Conseils techniques
- Utilisez la stack déjà configurée (Symfony / React, TypeScript, TailwindCSS, ShadCN)
- Vous pouvez ajouter des dépendances si nécessaire
- Le respect des normes de qualité du code est attendu (PHP CS Fixer, ESLint et TypeScript sont déjà configurés)
- Nous valoriserons votre aptitude à comprendre et à vous intégrer dans la base de code existante
- Les principales commandes sont déjà répertoriées dans le fichier `Makefile` (`make help` pour lister les commandes disponibles)
