# Journal de Décisions Architecturales (ADR) - ReviewMe

## 2026-04-08-01 : Adoption du Design System "The Monolith & The Lens"
### Statut 
Accepté
### Contexte
L'interface utilisateur initiale manquait de caractère professionnel et de hiérarchie visuelle. Le besoin d'un standard "SaaS Premium" (type Linear/Vercel) a été identifié pour élever la plateforme de revue de code.
### Décision
Refonte complète de l'UI basée sur le design system "The Monolith & The Lens" via la stack TALL (Tailwind, Alpine.js, Laravel, Livewire).
#### Principes Clés :
1. **Background Dynamique** : Utilisation d'une grille SVG interactive réagissant au curseur (effet "Lens").
2. **Morphologie des Boutons** : Passage d'un état "Pill" (arrondi) à un état "Square" (angles droits) au survol pour symboliser la précision technique (configurable via les props `pill` et `static`).
3. **Texture & Grain** : Injection de bruits numériques légers (Noise Texture) sur les surfaces interactives pour casser l'aspect plat du web traditionnel.
4. **Cinétique Fluide** : Utilisation systématique de `cubic-bezier(0.4, 0, 0.2, 1)` pour toutes les transitions (150ms-300ms).
### Conséquences
- Amélioration significative du contraste et de la lisibilité des données (WCAG AA).
- Structure de composants Blade plus robuste et réutilisable.
- Expérience utilisateur plus "vivante" grâce aux micro-interactions Alpine.js.

---

## 2026-04-08-02 : Conteneurisation & Orchestration (US35/US36)
### Statut
Accepté
### Choix Technique : Docker Multi-Stage Build
**Décision** : Utiliser un `Dockerfile` multi-étapes pour séparer les étapes de build (Composer, Node/Vite) du runtime final.
**Raison** : Permet de générer des images très légères basées sur PHP-FPM Alpine, tout en garantissant des environnements de build reproductibles.

### Choix Technique : Orchestration Web/App/DB
**Décision** : Découpler le serveur Web (Nginx), l'application (PHP-FPM) et la base de données (MySQL) via Docker Compose.
**Raison** : C'est le standard industriel qui facilite le passage en production et permet une maintenance isolée de chaque service.

---

## 2026-04-08-03 : Implémentation Core ReviewMe
### Choix Technique : GitHub Login Obligatoire
**Décision** : Utiliser uniquement GitHub OAuth pour l'authentification.
**Raison** : Plateforme dédiée aux développeurs, simplifie la gestion des avatars et pseudos.

### Choix Technique : Stockage SQLite (Local)
**Décision** : Utilisation temporaire de SQLite pour le développement local.
**Raison** : L'extension `pdo_pgsql` n'est pas activée chez l'utilisateur. PostgreSQL reste la cible pour la production.

### Choix Architecture : Versioning via Snippets
**Décision** : Séparer les `snippets` du modèle `Post`.
**Raison** : Permet de conserver l'historique des modifications de code (V1, V2...) sans dupliquer les métadonnées.

### Choix UI : Mode Sombre Monolith
**Décision** : Forcer le mode sombre par défaut avec une palette Monolith (Surface #12131b).
**Raison** : Préférence majeure de la cible (développeurs) et esthétique premium immédiate.

---

## 2026-04-08-04 : Internationalisation & Système de Réputation / Métadonnées
### Choix Technique : Système de localisation JSON (i18n)
**Décision** : Implémenter un système de traduction multilingue (FR/EN) via des fichiers JSON (`lang/en.json`, `lang/fr.json`).
**Raison** : Flexibilité maximale pour l'ajout de langues et centralisation des chaînes de texte.

### Choix Technique : Système de Réputation Automatisé
**Décision** : Implémentation d'un "hook" Eloquent sur le modèle `Reaction` (+10 points par réaction).
**Raison** : Gamification immédiate de la plateforme, valorisant la curation de qualité.

### Choix Architecture : Enrichissement des Métadonnées "Vibe"
**Décision** : Ajout de champs techniques obligatoires (`goal`, `context`, `lens`) au modèle `Post`.
**Raison** : Cadre le feedback en forçant l'auteur à définir ses intentions (Performance, Élégance, Lisibilité).

---

## 2026-04-08-05 : Finalisation du Workflow de Publication & Landing Page
### Statut
Accepté
### Choix Technique : Normalisation de la Résolution d'Auth
**Décision** : Utilisation systématique de la Facade `Auth::id()` au lieu du helper `auth()->id()` dans les composants Livewire complexes.
**Raison** : Évite les erreurs de résolution de méthode ("Undefined method id") rencontrées dans certains environnements PHP/Livewire lors de l'injection de dépendances.

### Choix Produit : Réactivation de la Landing Page
**Décision** : Rétablissement de la `welcome.blade.php` en tant que point d'entrée (`/`) et réactivation des routes d'inscription standard.
**Raison** : Permettre une présentation marketing du projet et l'ouverture progressive de la plateforme au-delà de GitHub OAuth pour les phases de test.


