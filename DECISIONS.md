# Journal de Décisions Architecturales (ADR)

## ADR-2026-04-08-01 : Adoption du Design System "The Monolith & The Lens"

### Statut
Accepté

### Contexte
L'interface utilisateur initiale manquait de caractère professionnel et de hiérarchie visuelle. Le besoin d'un standard "SaaS Premium" (type Linear/Vercel) a été identifié pour élever la plateforme de revue de code.

### Décision
Refonte complète de l'UI basée sur le design system "The Monolith & The Lens" via la stack TALL (Tailwind, Alpine.js, Laravel, Livewire).

#### Principes Clés :
1. **Background Dynamique** : Utilisation d'une grille SVG interactive réagissant au curseur (effet "Lens").
2. **Morphologie des Boutons** : Passage d'un état "Pill" (arrondi) à un état "Square" (angles droits) au survol pour symboliser la précision technique.
3. **Texture & Grain** : Injection de bruits numériques légers (Noise Texture) sur les surfaces interactives pour casser l'aspect plat du web traditionnel.
4. **Cinétique Fluide** : Utilisation systématique de `cubic-bezier(0.4, 0, 0.2, 1)` pour toutes les transitions (150ms-300ms).

### Conséquences
- Amélioration significative du contraste et de la lisibilité des données (WCAG AA).
- Structure de composants Blade plus robuste et réutilisable.
- Expérience utilisateur plus "vivante" grâce aux micro-interactions Alpine.js.
