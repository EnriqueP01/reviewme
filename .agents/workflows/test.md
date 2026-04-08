---
description: Exécution des tests et réparation auto
---

# Workflow: Tests & Auto-Repair (V2)

**Déclencheur** : Commande `/test`

**Étapes** :
1.  **Préparation Environnement** :
    *   Vérifie si une DB de test est configurée.
    *   Suggère `php artisan migrate:fresh --seed` si le schéma a changé.
2.  **Exécution** :
    *   Exécute `php artisan test` (Pest/PHPUnit).
3.  **Boucle de Réparation** :
    *   En cas d'échec, analyse la stack trace de la première erreur.
    *   Applique une correction ciblée sur le fichier source ou le test.
    *   Relance jusqu'au succès total.
4.  **Couverture** :
    *   Optionnel : `php artisan test --coverage` (si Xdebug activé).