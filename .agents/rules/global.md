---
trigger: always_on
---

# Directives Globales (Projet ReviewMe)

## Langue
* **Français** : Tu dois toujours formuler ta réponse finale, tes explications et tes rapports en **français**, même si le code est en anglais.
* **Concision** : Pas d'emojis. Réponses concises, directes et sans formules de politesse (optimisation de tokens).
* **Efficacité RADICALE** : 
    * Évite les commandes ou étapes redondantes. 
    * Chaque action doit avoir un but précis et un coût en tokens minimal. 
    * Ne fais pas de recherches exploratoires inutiles si la solution technique est évidente.
    * Combine les commandes Git (add/commit/push) ou terminal quand c'est possible.

## Style de Code & Documentation
* **Méthode KISS** : "Keep It Simple, Stupid". Évite la sur-ingénierie. Préfère toujours la solution la plus simple, lisible et maintenable.
* **Linter & Formatage** : Respecte TOUJOURS les fichiers de configuration de style présents dans le projet (`.editorconfig`, `.styleci.yml`, `phpstan.neon`, etc.).
* **Commentaires** : Ajoute des commentaires courts et concis au-dessus de chaque fonction et bloc logique important pour expliquer ce qu'il fait.

## Architecture & Traçabilité
* **Workflow (STRICT)** : Interdiction ABSOLUE de push directement sur `main`. Respecte le cycle : `git checkout -b branch-name` -> `commit` -> `merge main` -> `push`.
* **Action Atomique** : Chaque tâche ou prompt doit avoir sa propre branche dédiée.
* **Journal de Décisions** : Pour chaque changement, mets à jour `DECISIONS.md` avec le format strict incluant l'**Auteur** et le contexte technique.
* **Intégrité Frontend (Strict No-Spill Policy)** : 
    * Ne modifie JAMAIS un élément visuel (Logo, animations CSS, palettes de couleurs, espacements) si ce n'est pas l'objet explicite du prompt.
    * Interdiction de supprimer ou d'altérer des animations existantes ou des assets graphiques (SVG, PNG) sans demande directe.
    * Toute "amélioration esthétique" spontanée est considérée comme une régression. Reste focalise sur la logique et la stabilité.
* **Organisation** : Maintiens une architecture propre. Les tests doivent être rangés dans `tests/`.

## Sécurité, Robustesse & Ops
* **Validation Complète** :
    * Vérifie systématiquement les entrées des fonctions (inputs) pour éviter les erreurs silencieuses.
    * Vérifie l'intégrité des appels externes et valide les paramètres des routes/URL avant tout traitement.
* **Environnement** : Vérifie systématiquement si une modif nécessite l'ajout d'une variable dans `.env` et mets à jour `.env.example`.
* **Migrations** : Toute modification de la structure de données doit passer par une migration Laravel propre.
* **Logs & Erreurs** : Utilise les Logs Laravel (`Log::info()`, `Log::error()`) pour les actions critiques et implémente une gestion d'erreurs robuste (Try/Catch).
* **Internationalisation (i18n)** : À chaque modification ou ajout sur le frontend (Blade, JavaScript, etc.), tu dois impérativement extraire les chaînes de texte vers `lang/en.json` et `lang/fr.json` et utiliser `{{ __('...') }}` ou `lang()` pour l'affichage. Ne laisse aucune chaîne en dur.
