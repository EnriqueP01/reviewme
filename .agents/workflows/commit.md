---
description: Commit & Push avec vérification Qualité (Lint/Test)
---

# Workflow: Commit & Push Automatique (V2 - Qualité)

**Déclencheur** : Commande `/commit`

**Étapes** :
1.  **Vérification Pré-Commit** : 
    *   Exécute un scan rapide (Lint) via `.agents/workflows/lint.md`.
    *   Si le lint échoue, propose de le corriger : "Erreurs de style détectées. Corriger avant commit ? (O/N)".
2.  **Analyse du Diff** :
    *   Exécute `git diff --staged`. Si vide, propose `git add .`.
    *   Détecte si des migrations ont été ajoutées (scope `db`).
    *   Détecte si des règles agent ont été modifiées (scope `agent`).
3.  **Génération du Message** :
    *   Utilise la convention **Conventional Commits**.
    *   Format : `type(scope): description`
    *   Inclut automatiquement les changements de `.agents/rules` ou `.agents/workflows` dans le scope `agent`.
4.  **Action** :
    *   Demande validation du message.
    *   Exécute `git commit` puis `git push`.