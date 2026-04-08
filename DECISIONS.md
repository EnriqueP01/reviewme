# Journal de Décisions Architecturales (ADR) - ReviewMe

## 2026-04-08-01 : Adoption du Design System "The Monolith & The Lens"
- **Auteur** : Antigravity & Team
- **Statut** : Accepté
- **Contexte** : L'interface utilisateur initiale manquait de caractère professionnel et de hiérarchie visuelle. Le besoin d'un standard "SaaS Premium" (type Linear/Vercel) a été identifié pour élever la plateforme de revue de code.
- **Décision** : Refonte complète de l'UI basée sur le design system "The Monolith & The Lens" via la stack TALL (Tailwind, Alpine.js, Laravel, Livewire).
    - **Background Dynamique** : Grille SVG interactive (effet "Lens").
    - **Morphologie des Boutons** : État "Square" au survol.
- **Impact** : Expérience utilisateur bilingue et immersive, prête pour une audience internationale.

## 2026-04-08-02 : Conteneurisation & Orchestration (US35/US36)
- **Auteur** : EnriqueP01
- **Statut** : Accepté
- **Décision** : Utiliser un `Dockerfile` multi-étapes et découpler Nginx, PHP-FPM et MySQL via Docker Compose.
- **Impact** : Déploiement standardisé et maintenance isolée des services.

## 2026-04-08-03 : Implémentation Core ReviewMe
- **Auteur** : EnriqueP01
- **Statut** : Accepté
- **Décision** : Chat GitHub OAuth obligatoire, stockage SQLite (local), et versioning des snippets séparé des Posts.
- **Impact** : Simplification de l'onboarding développeur et traçabilité du code.

## 2026-04-08-04 : Internationalisation & Système de Réputation
- **Auteur** : Antigravity
- **Statut** : Accepté
- **Décision** : Mise en œuvre du système i18n JSON et du Karma automatisé (+10 pts/réaction).
- **Impact** : Plateforme prête pour une audience globale et gamifiée.

## 2026-04-08-05 : Finalisation du Workflow de Publication & Landing Page
- **Auteur** : EnriqueP01
- **Statut** : Accepté
- **Décision** : Normalisation `Auth::id()`, rétablissement de la landing page marketing.
- **Impact** : Stabilité des sessions et meilleure présentation du produit.

## 2026-04-08-06 : Optimisation Performance & Stabilité Livewire 3
- **Auteur** : Antigravity
- **Statut** : Accepté
- **Décision** : Architecture SPA (`wire:navigate`), suppression double Alpine instances, et protection DOM via Zero-Width Space.
- **Impact** : Fluidité extrême et robustesse de l'affichage du code.

## 2026-04-08-07 : Migration vers l'Architecture Orientée Actions (Action-Domain)
- **Auteur** : Antigravity
- **Statut** : Accepté
- **Contexte** : Complexité croissante des contrôleurs.
- **Décision** : Adoption du pattern **Action** (`app/Actions`) pour isoler la logique métier.
- **Impact** : Code testable, réutilisable et découplé du frontend.

## 2026-04-08-08 : Sécurisation, Recherche & Expérience Audio FX
- **Auteur** : Antigravity
- **Statut** : Accepté
- **Décision** : 
    - **Karma atomique** : Migration de la logique vers `UpdateUserReputationAction`.
    - **Sécurité** : Intégration des Policies (`PostPolicy`, `ReviewPolicy`).
    - **Audio FX** : Création du service `window.fx` (Oscillateurs procéduraux).
    - **Performance** : Throttling de l'InteractiveGrid et indexation SQL.
- **Impact** : Intégrité des scores, sécurité renforcée et immersion sensorielle premium.

## 2026-04-08-09 : Micro-interactions & Verrouillage UI
- **Auteur** : EnriqueP01
- **Statut** : Accepté
- **Décision** : 
    - **Feedback Haptique** : Simulation via Web Audio API.
    - **Anti-Replay** : Verrouillage Alpine.js `isVoting` sur les boutons de vote.
- **Impact** : Sensation de "monolithe physique" et prévention du spam d'interactions.

## 2026-04-08-10 : Implémentation du Système HUD et Heatmap d'Activité
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de renforcer l'engagement utilisateur et de fournir des retours systémiques clairs.
- **Décision** : Création du composant Toast HUD et de la Heatmap de contribution (Sync Density).
- **Impact** : Gamification du profil et retour utilisateur instantané.

## 2026-04-08-11 : Unicité de l'Identité & Restauration du Design Originel
- **Auteur** : EnriqueP01
- **Statut** : ✅ Implémenté
- **Décision** : 
    - **Identité** : Migration de la table `users` pour rendre le champ `name` unique et ajout de la règle de validation `Rule::unique` dans `ProfileUpdateRequest`.
    - **Design** : Restauration du logo procédural ("R") et suppression des animations de transition globales (`fade-in-up`) jugées intrusives.
- **Impact** : Prévention des doublons d'identité et retour à une esthétique plus sobre et fidèle à l'intention initiale.

## 2026-04-08-12 : Optimisation Drastique du TTFB sur Windows
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Latence de 7s au premier octet (TTFB) due à la résolution DNS IPv6 de Windows et au verrouillage des sessions fichiers.
- **Décision** : 
    - Basculement de `localhost` vers `127.0.0.1` dans `.env`.
    - Migration du driver de session de `file` vers `cookie`.
    - Suppression de l'import redondant Alpine.js dans `app.js`.
- **Impact** : Réduction du temps de chargement de 80% (7s -> 1.2s).

