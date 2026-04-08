---
description: fais npm run lint et corrige les erreurs
---

# Workflow: Auto Lint & Fix (Global)

**Déclencheur** : Commande `/lint`

**Étapes** :
1.  Exécute la commande de linting du projet (ex: `npm run lint`).
2.  Si des erreurs ou warnings sont détectés :
    * Applique les corrections nécessaires au code.
    * Relance l'étape 1.
3.  Répète la boucle jusqu'à ce que le linter ne renvoie plus aucune erreur.
4.  Confirme à l'utilisateur : "Linting terminé, le code est propre."