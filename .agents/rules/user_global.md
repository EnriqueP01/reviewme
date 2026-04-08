# Directives Globales (Tous Projets)

## Langue
* **Français** : Tu dois toujours formuler ta réponse finale, tes explications et tes rapports en **français**, même si le code est en anglais.

## Style de Code & Documentation
* **Méthode KISS** : "Keep It Simple, Stupid". Évite la sur-ingénierie. Préfère toujours la solution la plus simple, lisible et maintenable.
* **Commentaires** : Ajoute des commentaires courts et concis au-dessus de chaque fonction et bloc logique important pour expliquer ce qu'il fait.
    * *Exemple* : `// Vérifie si le token utilisateur est expiré`

## Architecture & Tests
* **Organisation** : Maintiens une architecture propre en permanence. Ne laisse jamais de fichiers en vrac à la racine.
* **Rangement des Tests** : Les fichiers de tests et leurs résultats doivent être rangés systématiquement dans les dossiers correspondants (ex: dossier `__tests__` ou `tests/` selon la convention du langage), jamais mélangés aux fichiers sources et ces dossiers doivent être ajouté au .gitignore si ils ne le sont pas.

## Sécurité & Robustesse
* **Validation Complète** :
    * Vérifie systématiquement les entrées des fonctions (inputs) pour éviter les erreurs silencieuses.
    * Vérifie l'intégrité des appels externes et valide les paramètres des routes/URL avant tout traitement.
