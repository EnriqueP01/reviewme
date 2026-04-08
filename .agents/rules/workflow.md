# Directives de Workflow Automatique (Vibe Coding Team)

## 1. Cycle de Modification Obligatoire
Pour CHAQUE demande de modification de code, tu dois impérativement suivre cet ordre strict :
1. **Synchronisation Amont** : 
    - `git fetch --all`
    - `git pull origin main` (ou la branche parente actuelle) pour éviter les décalages.
2. **Branchement Isolé** : 
    - Créer une nouvelle branche locale avec un nom normalisé (ex: `feat/nom-feature`, `fix/nom-bug`, `refactor/nom-cible`).
    - Ne jamais travailler directement sur une branche protégée sans consigne explicite.
3. **Fusion Préventive (Merge)** : 
    - Si de nouvelles branches distantes ont été détectées durant le `fetch`, analyse si elles impactent ton périmètre.
    - Effectue un `git merge origin/[branche-equipe]` si nécessaire pour garantir la compatibilité.
4. **Implémentation Ciblée** : 
    - Effectue la modification demandée en respectant les règles d'intégrité (voir section 2).
5. **Livraison & Push** : 
    - `git add .`
    - `git commit -m "type: description claire"` (respecte les Conventional Commits).
    - `git push origin [ma-nouvelle-branche]`.

## 2. Intégrité du Frontend & "No-Spill" Policy
* **Modification Chirurgicale** : Ne modifie JAMAIS une partie du frontend (classes CSS, balisage Blade, logique Alpine.js) qui n'a pas été explicitement mentionnée dans le prompt.
* **Isolation des Styles** : Utilise des classes utilitaires ou des composants isolés pour éviter tout effet de bord sur le reste de l'interface.
* **Vérification Visuelle** : Avant de valider, assure-toi que les composants adjacents n'ont pas bougé et que le design système global (couleurs, espacements) est respecté.

## 3. Traçabilité (DECISIONS.md)
* **Format d'Entrée GGF** (Global Governance Format) : Chaque décision doit être enregistrée avec l'auteur.
    - Format : `## YYYY-MM-DD-ID : [Titre]`
    - **Auteur** : [Ton Nom d'IA (Antigravity) ou le nom du compte de l'utilisateur (ex: EnriqueP01)]
    - **Contexte** : [...]
    - **Décision** : [...]
    - **Impact** : [...]
* **Ménage de Fusion** : Lors de chaque merge, effectue un tri chronologique (les plus récents en bas pour le journal de bord, ou selon l'ordre logique des dépendances). Assure-toi qu'aucune entrée n'est dupliquée.
