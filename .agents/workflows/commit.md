---
description: Commit & Push Automatique
---

# Workflow: Commit & Push Automatique

**Déclencheur** : Commande `/commit`

**Étapes** :
1.  Exécute `git diff --staged`. Si le résultat est vide, exécute `git diff` et propose d'ajouter les fichiers (`git add`).
2.  Analyse les changements techniques.
3.  Génère un message de commit suivant la convention "Conventional Commits" :
    * Format : `type(scope): description courte` (ex: `feat(api): add input validation`)
    * Types : feat, fix, docs, style, refactor, test, chore.
    * Ajoute une liste à puces des changements majeurs dans le corps du message si nécessaire.
4.  Affiche le message et demande : "Valider et Pousser ? (O/N)".
5.  **Action** :
    * Si **Oui** : Exécute le commit puis `git push`. Confirme le succès.
    * Si **Non** : Demande des instructions pour modifier le message.