---
description: Auto-correction Laravel (Pint) & JS (ESLint)
---

# Workflow: Auto Lint & Fix (Laravel-Ready)

**Déclencheur** : Commande `/lint`

**Étapes** :
1.  **PHP Style (Laravel Pint)** :
    *   Exécute `vendor/bin/pint` (ou `d-artisan pint` si Docker).
    *   Corrige automatiquement les écarts de style PSR-12/Laravel.
2.  **JS Style (ESLint/Prettier)** :
    *   Exécute `npm run lint -- --fix`.
3.  **Analyse Statique (PHPStan)** :
    *   Exécute `vendor/bin/phpstan analyse`.
    *   Si des erreurs de type sont trouvées, les lister précisément pour correction manuelle ou assistée.
4.  **Confirmation** :
    *   Affiche un résumé des fichiers formatés.