# 🤝 Guide du Contributeur

Bienvenue dans l'équipe ReviewMe ! Pour maintenir une qualité de code exceptionnelle et une collaboration fluide, nous suivons des protocoles stricts.

## 🌿 Workflow Git (Le Cycle Antigravity)

**Règle d'or : JAMAIS de push direct sur `main` ou `dev`.**

1.  **Extraction** : Créez une branche à partir de `dev`.
    ```bash
    git checkout dev
    git pull origin dev
    git checkout -b feat/nom-de-votre-tache
    ```
2.  **Atomisation** : Un commit doit correspondre à une action logique.
    ```bash
    git commit -m "feat(scope): description concise"
    ```
3.  **Intégration** : Fusionnez avec `dev` via un merge non-fast-forward (`--no-ff`).
    ```bash
    git checkout dev
    git merge --no-ff feat/nom-de-votre-tache -m "merge: fusion de ..."
    git push origin dev
    ```

---

## 💬 Langue et Communication

- **Commentaires & Code** : Anglais (standard technique).
- **Documentation & Rapports** : **Français** obligatoire (selon les directives du Tech Lead).
- **Commit Messages** : Suivez le format [Conventional Commits](https://www.conventionalcommits.org/).

---

## 🏛️ Nomenclature et Style

- **PHP** : Standard PSR-12, vérifié par Laravel Pint.
- **Actions** : Chaque logique métier doit être une classe `Final` avec une méthode `execute()`.
- **Livewire** : Utilisez les attributs `#[Layout]`, `#[Locked]` et `#[Computed]` pour une meilleure lisibilité.
- **CSS** : Utilisez les classes Tailwind et respectez le système de tokens défini dans `tokens.css`.

---

## 📓 Journal de Décisions (ADR)

Toute modification structurelle ou choix technologique majeur doit être consigné dans le fichier [DECISIONS.md](../DECISIONS.md) à la racine du projet. Lisez toujours ce fichier avant de proposer un changement d'architecture.
