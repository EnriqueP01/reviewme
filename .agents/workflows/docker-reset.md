---
description: Réinitialisation complète Docker & DB
---

# Workflow: Hard Reset Environnement (/reset)

**Déclencheur** : Commande `/reset`

**Étapes** :
1.  **Confirmation** : "Cette action va effacer TOUTES les données de la base. Continuer ? (O/N)".
2.  **Nettoyage Docker** :
    *   `docker-compose down -v` (Supprime les volumes).
3.  **Boot** :
    *   `docker-compose up -d --build`.
4.  **Initialisation App** :
    *   `d-composer install`
    *   `d-artisan migrate:fresh --seed`
5.  **Succès** : Env propre et prêt à l'emploi.
