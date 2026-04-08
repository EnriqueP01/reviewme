---
trigger: always_on
---

# Directives Globales (Projet ReviewMe)


## Langue
* **Français** : Tu dois toujours formuler ta réponse finale, tes explications et tes rapports en **français**, même si le code est en anglais.


## Style de Code & Documentation
* **Méthode KISS** : "Keep It Simple, Stupid". Évite la sur-ingénierie. Préfère toujours la solution la plus simple, lisible et maintenable.
* **Linter & Formatage** : Respecte TOUJOURS les fichiers de configuration de style présents dans le projet (`.editorconfig`, `.styleci.yml`, `phpstan.neon`, etc.).
* **Commentaires** : Ajoute des commentaires courts et concis au-dessus de chaque fonction et bloc logique important pour expliquer ce qu'il fait.


## Architecture & Traçabilité
* **Organisation** : Maintiens une architecture propre en permanence. Ne laisse jamais de fichiers en vrac à la racine.
* **Journal de Décisions** : Pour chaque changement architectural ou choix de librairie majeur, mets à jour ou propose une entrée dans le fichier `DECISIONS.md` à la racine du projet.
* **Rangement des Tests** : Les fichiers de tests doivent être rangés systématiquement dans les dossiers correspondants (ex: `tests/`), jamais mélangés aux fichiers sources.


## Sécurité & Robustesse
* **Validation Complète** :
    * Vérifie systématiquement les entrées des fonctions (inputs) pour éviter les erreurs silencieuses.
    * Vérifie l'intégrité des appels externes et valide les paramètres des routes/URL avant tout traitement.
