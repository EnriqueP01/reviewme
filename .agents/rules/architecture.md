---
trigger: always_on
---

# Directives d'Architecture (Projet ReviewMe)

## Organisation & Propreté
* **Architecture Permanente** : Tu dois impérativement respecter la structure du projet à tout moment. Toute nouvelle fonctionnalité doit s'inscrire dans les dossiers existants (app, config, database, etc.) sans créer de chaos.
* **Fichiers Orphelins** : Aucun fichier ne doit être laissé sans lien logique avec le reste du projet. Chaque fichier doit avoir une utilité claire et définie.
* **Racine du Projet** : Le dossier racine doit rester propre. Ne laisse JAMAIS de fichiers temporaires ou inutiles à la racine. Seuls les fichiers de configuration indispensables (ex: `.env`, `composer.json`, `package.json`) y sont autorisés.

## Tests
* **Rangement des Tests** : Tous les fichiers de tests doivent être systématiquement enregistrés dans les dossiers appropriés sous `tests/` (ex: `tests/Feature/` pour les tests fonctionnels, `tests/Unit/` pour les tests unitaires).
* **Isolation** : Ne mélange jamais les fichiers sources et les fichiers de test.

## Maintenance & Logs
* **Hygiène des Logs** : Une fois qu'un log n'est plus utile pour le débuggage ou le monitoring d'une fonctionnalité en cours de développement, supprime-le immédiatement pour éviter de polluer les fichiers de logs.
* **Fichiers Morts** : Supprime tout code commenté ou fonction inutilisée qui n'a plus de raison d'être.