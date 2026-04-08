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
- **Impact** : Expérience utilisateur bilingue et immersive, prête pour une audience internationale.

## 2026-04-08-10 : Implémentation du Système HUD et Heatmap d'Activité

- **Contexte** : Besoin de renforcer l'engagement utilisateur et de fournir des retours systémiques clairs.
- **Décision** :
    - Création d'un composant de notification "Toast HUD" écoutant des événements Livewire globaux.
    - Ajout d'une Heatmap de contribution (Sync Density) sur le profil.
    - Enrichissement du moteur Audio FX pour supporter les notifications système.
- **Statut** : ✅ Implémenté
- **Impact** : Gamification du profil et retour utilisateur instantané (audio + visuel) sur les actions critiques.

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

## 2026-04-08-07 : Migration vers une Architecture Orientée Actions (Action-Domain)
### Statut
Accepté
### Contexte
L'application utilisait initialement une architecture MVC standard, concentrant la logique métier complexe dans les contrôleurs et les composants Livewire ("Fat Controllers"). Cela compliquait la réutilisation de la logique et les tests unitaires isolés.
### Décision
Adoption du pattern **Action** pour encapsuler chaque opération métier dans une classe dédiée unique (`app/Actions`).
#### Règles de mise en œuvre :
1. **Responsabilité Unique (SRP)** : Un contrôleur ne doit gérer que la requête/réponse. Toute manipulation de données passe par une Action.
2. **Organisation par Domaine** : Les actions sont regroupées par domaine métier (`Auth`, `Profile`, `Posts`, `Reactions`).
3. **Interface Standard** : Utilisation de signatures de méthode explicites (ex: `execute()`) avec typage strict pour garantir la robustesse.
4. **Découplage UI/Métier** : Les composants Livewire délèguent systématiquement le traitement lourd aux Actions, permettant de tester la logique sans dépendance au frontend.
### Conséquences
- **Maintenabilité** : Code plus lisible et facile à faire évoluer.
- **Réutilisabilité** : Les mêmes actions peuvent être appelées depuis le Web, une API, ou des commandes CLI Artisan.
- **Sécurité** : Utilisation systématique de transactions SQL dans les actions complexes (`CreatePostAction`).
- **Testabilité** : Couverture de tests unitaires simplifiée sur des classes pures sans état HTTP.

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



---

## 2026-04-08-06 : Optimisation Performance & Stabilité Livewire 3
### Statut
Accepté
### Choix Technique : Architecture SPA via `wire:navigate`
**Décision** : Activation globale de `wire:navigate` sur l'ensemble de la navigation.
**Raison** : Réduit drastiquement le temps de perception du chargement en ne téléchargeant que le contenu utile (AJAX) sans recharger les assets (CSS/JS).

### Choix Technique : Résolution du Conflit d'Instances Alpine.js
**Décision** : Suppression de l'instanciation manuelle d'Alpine dans `app.js`.
**Raison** : Livewire 3 injecte et gère sa propre version d'Alpine.js. Une double instanciation provoquait des comportements erratiques sur les composants interactifs (boutons de vote, menus déroulants).

### Choix Technique : Protection de l'Inégrité du DOM (Code Highlighter)
**Décision** : Injection de caractères invisibles (Zero-Width Space) dans les attributs `wire:` et `x-` au sein des blocs de code affichés.
**Raison** : Empêche le moteur Livewire de tenter de parser et d'exécuter des attributs contenus dans les snippets de code présentés pour revue, évitant ainsi des erreurs fatales de corruption du DOM.

---

## 2026-04-08-08 : Sécurisation, Recherche & Expérience Audio FX
### Statut
Accepté
### Contexte
Le système de réputation initial était limité (pas de gestion des retraits de votes) et la plateforme manquait de contrôles d'accès granulaires. L'expérience utilisateur manquait également de retour haptique/sonore pour renforcer l'aspect "Premium".
### Décision
Mise en œuvre d'une série d'améliorations structurelles et sensorielles pour finaliser la robustesse du produit.

#### 1. Système de Karma Avancé :
*   **Barème Différencié** : +10 points pour les feedbacks positifs (`mindblown`, `clean`, `security`) et -2 points pour les feedbacks `optimisable`.
*   **Logique Atomique** : Migration de la logique de réputation vers une action dédiée `UpdateUserReputationAction`. Le calcul gère désormais les "Unvotes" (retrait de points) et les "Switches" de types (bascule propre entre positif et négatif).

#### 2. Sécurité & Autorisations (Policies) :
*   **Adoption des Policies** : Création de `PostPolicy` et `ReviewPolicy`.
*   **Règle de Propriété** : Seuls les auteurs originaux peuvent désormais supprimer leurs reviews ou modifier leurs publications. Application de ces règles via `authorize()` dans les composants Livewire/Actions.

#### 3. Recherche & Filtrage :
*   **Recherche Debounced** : Intégration d'un champ de recherche dans le Feed utilisant `wire:model.live.debounce.300ms` filtrant par titre, description et nom de l'auteur.

#### 4. Audio FX & UX Sensorielle :
*   **Digital Soundscape** : Création d'un service JS `window.fx` générant des sons procéduraux (Oscillateurs Web Audio API) pour les interactions clés (survol, capture de code, vote, succès).
*   **Code Highlighting** : Adoption manuelle d'une palette "Tokyo Night" dans le composant `code-block` pour simuler un rendu IDE haut de gamme sans dépendance lourde externe.

### Conséquences
*   **Intégrité des Données** : Le score de réputation (Karma) reflète désormais fidèlement l'état réel de la base de données.
*   **Accessibilité** : Meilleur retour utilisateur grâce aux signaux sonores et haptiques.
*   **Sécurité** : Protection contre les manipulations non autorisées via les routes Livewire.
