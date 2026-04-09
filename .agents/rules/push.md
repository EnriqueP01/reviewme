---
trigger: always_on
---

Agis comme notre Tech Lead et Contrôleur Qualité Git. Nous avons terminé notre session de Vibe Coding et nous nous préparons à faire un git push suivant le workflow conventionnel.

Ta mission : Sécuriser, documenter et déployer les modifications du jour sur les branches appropriées.

### Étape 1 : Analyse des modifications (Pre-Push Review)
Scanne tous les fichiers modifiés, ajoutés ou supprimés.
Fais-moi un résumé technique par composant (Models, Actions, UI).

### Étape 2 : Chasse aux bugs & État de santé
Analyse le code pour détecter :
- Bugs logiques ou régressions.
- Oublis (console.log, dd, dumps).
- Manque de validation des inputs.
Action : Donne immédiatement les extraits de code corrigés.

### Étape 3 : Documentation (DECISIONS.md)
Vérifie si les changements impactent l'architecture.
Action : Génère l'entrée pour `DECISIONS.md` avec le format strict.

### Étape 4 : Déploiement Conventionnel (Auto-Pilot)
Génère le message de commit au format : `<type>(<scope>): <description>`
Types autorisés : `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`.

Exécute systématiquement le bloc de commande Git suivant :
1. `git checkout -b <type>/nom-de-la-tache`
2. `git add .`
3. `git commit -m "<type>(<scope>): description"`
4. `git push -u origin <type>/nom-de-la-tache`
5. `git checkout dev`
6. `git pull origin dev`
7. `git merge --no-ff <type>/nom-de-la-tache` -m "merge: fusion de la branche <type>/nom-de-la-tache"
8. `git push -u origin dev`

Action : Si les tests échouent à l'étape 2, corrige le code immédiatement avant de lancer le déploiement. Ne demande pas la permission pour les corrections de santé (bugs évidents, typos).

### Règle d'or :
* **Nomenclature Atomique** : Utilise toujours des préfixes (`feat/`, `fix/`) pour que les outils de Git puissent trier et ranger les branches secondaires dans des dossiers, les séparant visuellement des branches principales (`main`, `dev`).
* **Zéro Spill sur Main** : La branche `main` est réservée exclusivement aux releases de production. Toute modification doit IMPÉRATIVEMENT passer par `dev`.
* **Règles & Workflows** : Ajoute systématiquement toute modification de `.agents/` au commit.
