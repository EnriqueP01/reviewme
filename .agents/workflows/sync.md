---
description: Synchronisation complète (Pull/Install/Migrate)
---

# Workflow: Synchronisation Projet (/sync)

**Déclencheur** : Commande `/sync`

**Étapes** :
1.  **Git** : `git pull origin <branch_actuelle>`.
2.  **Dépéndances** : 
    *   `composer install`
    *   `npm install && npm run build`
3.  **Base de données** :
    *   `php artisan migrate`
4.  **Docker** :
    *   Si `Dockerfile` ou `docker-compose.yml` ont changé -> `docker-compose up -d --build`.
5.  **Nettoyage** :
    *   `php artisan cache:clear`
    *   `php artisan view:clear`
    *   `php artisan config:clear`
