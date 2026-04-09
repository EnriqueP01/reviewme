# Règle : Mise à jour du Changelog (Journal des Modifications)

Cette règle définit l'obligation de maintenir le journal des modifications sur le site (`changelog.blade.php`) à chaque ajout de fonctionnalité majeure ou modification structurelle.

## 🛠️ Processus de Mise à Jour
Pour chaque déploiement incluant une nouvelle "grosses feature" ou une refactorisation importante :

1.  **Fichier Cible** : `resources/views/livewire/changelog.blade.php`.
2.  **Structure** : Ajoute un nouveau bloc `<div class="relative ...">` en haut de la timeline.
3.  **Contenu** :
    *   **Date** : Utilise le format YYYY-MM-DD (ex: {{ date('Y-m-d') }} ou date fixe).
    *   **Version** : Incrémente le numéro de version selon SemVer (ex: v1.2.0 -> v1.3.0).
    *   **Description** : Liste les changements majeurs avec les préfixes :
        *   `+` pour les ajouts.
        *   `~` pour les améliorations.
        *   `-` pour les corrections.
4.  **Internationalisation** : Toutes les chaînes de caractères de la nouvelle entrée doivent être encapsulées dans `{{ __('...') }}` et traduites dans `lang/fr.json` et `lang/en.json`.

## Principes Appliqués
- **Visibilité Utilisateur** : Le changelog est la vitrine de l'évolution du projet pour les utilisateurs.
- **Atomicité** : La mise à jour du changelog doit faire partie du même commit/PR que la fonctionnalité concernée.
- **Droit à l'Oubli** : Ne documente pas les micro-correctifs (typos, CSS mineur) pour ne pas polluer la timeline.

## Exemple d'Entrée
```html
<div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active animate-in fade-in zoom-in-95 duration-700">
    <!-- Icon -->
    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-primary/40 bg-surface shadow-[0_0_20px_rgba(190,194,255,0.2)]">
        <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
    </div>
    <!-- Content -->
    <div class="w-[calc(100%-4rem)] md:w-[45%] glass-panel p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all duration-500 shadow-2xl">
        <time class="font-mono text-[10px] uppercase tracking-widest text-primary font-black">2026-04-10</time>
        <div class="text-xl font-bold mb-3 tracking-tight">{{ __('v1.3.0 - New Feature Name') }}</div>
        <ul class="space-y-2 text-xs text-on-surface-variant/70 italic">
            <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Description of the big feature.') }}</li>
        </ul>
    </div>
</div>
```
