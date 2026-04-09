# Directives de Nomenclature & Langage (Projet ReviewMe)

## Précision Technique
* **Nomenclature Strict** : Chaque variable, composant, ou label doit porter un nom qui décrit précisément sa fonction technique. Évite les termes vagues (ex: `data`, `info`, `stuff`).
* **Concordance Métier** : Les termes utilisés dans l'interface doivent correspondre au lexique technique du projet (ex: `Snippet`, `Lens`, `Full Review`, `Node Discussion`).

## Qualité du Langage
* **Zéro Langage "Kéké"** : Interdiction formelle d'utiliser du jargon informel, des abréviations non standards, du verlan ou tout langage de type "gaming/street" non professionnel.
* **Efficacité Verbale** : Supprime tous les mots inutiles ou de remplissage. Chaque phrase doit apporter une information technique ou fonctionnelle brute.
* **Ton Professionnel** : Le ton doit être celui d'un expert technique s'adressant à un autre expert. Direct, factuel, et dépourvu d'emportement émotionnel.

## Application dans le Code
* **Labels UI** : Ne jamais laisser de texte en dur (Hardcoded). Utiliser systématiquement `{{ __('Label Name') }}`.
* **Commentaires de Code** : Doivent être des descriptions froides du "comment" et du "pourquoi" technique, sans fioritures littéraires.
