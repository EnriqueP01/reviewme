---
trigger: "detect_docker_files"
---
# Directives Docker & Conteneurisation (ReviewMe)

## Standards de Conteneurisation
*   **Orchestration** : Utilise exclusivement `docker-compose` pour la gestion des services (App, Web, DB).
*   **Multi-Stage Build** : Le `Dockerfile` doit maintenir une structure multi-étapes pour minimiser la taille de l'image finale.
*   **Volumes** : Assure-toi que les dossiers `storage/`, `bootstrap/cache/` et `database/` (si SQLite) sont montés en volumes pour la persistance et les logs.

## Automatisation & Cycle de Vie
*   **Post-Merge / Pull** : Si un `git pull` ou un merge modifie les fichiers suivants, lance ou suggère immédiatement un rebuild (`docker-compose build`) :
    *   `Dockerfile`
    *   `docker-compose.yml`
    *   `composer.lock`
    *   `package-lock.json`
*   **Auto-Prune** : Après chaque build réussi ou mise à jour d'image, suggère systématiquement `docker image prune -f` pour libérer l'espace disque.
*   **Vérification de Santé** : En cas d'erreur 500 ou de problème de connexion DB, vérifie systématiquement le statut des conteneurs avec `docker-compose ps` et analyse les logs avec `docker-compose logs --tail=50`.

## Alias de Développement (Recommandés)
Pour toute commande interne, utilise ces raccourcis dans tes réponses :
*   **d-artisan** : `docker-compose exec app php artisan`
*   **d-composer** : `docker-compose exec app composer`
*   **d-npm** : `docker-compose run --rm node npm` (si service node présent)

## Aide au Debugging & Logs
*   **Analyse de Logs** : En cas d'échec de commande Docker ou d'erreur application, effectue systématiquement un scan des logs conteneurs pour fournir une explication technique immédiate et une solution de correction.
*   **Accès Shell** : Privilégie `docker-compose exec app bash` pour les sessions interactives.
