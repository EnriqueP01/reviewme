# Directives de Qualité & Maintenabilité (ReviewMe)

## 1. Internationalisation (i18n) - STRICT
* **AUCUNE CHAÎNE EN DUR** : Il est formellement interdit de laisser du texte lisible par l'utilisateur directement dans le code (Blade, Vue, PHP, JS).
* **Extraction Systématique** : Toute nouvelle chaîne doit être ajoutée simultanément dans `lang/fr.json` et `lang/en.json`.
* **Standard d'Appel** : Utilise toujours `{{ __('Clef') }}` ou `@lang('Clef')`.

## 2. Robustesse des Composants (Defensive Coding)
* **Protection des Index** : Tout accès à un tableau de styles ou de configuration (ex: `$layers[$tonal]`) doit être sécurisé par un fallback.
    * *Correct* : `$layers[$tonal] ?? $layers['default']`
    * *Incorrect* : `$layers[$tonal]`
* **Validation des Props** : Utilise `@props` pour définir des valeurs par défaut saines pour tous les composants Blade.

## 3. Standard SEO & Social Sharing
* **Meta-Tags Obligatoires** : Chaque vue principale doit avoir :
    * Un titre unique via `@section('title')` ou `$header`.
    * Une meta-description pertinente.
* **Social Pulse** : Vérifie que les balises OpenGraph et Twitter Card sont cohérentes avec le contenu de la page.

## 4. Audit périodique
* À chaque fin de session de "Vibe Coding", lance un `/audit` pour vérifier que les nouvelles dépendances sont saines et que le score de performance reste optimal.
