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

## 2026-04-08-12 : Optimisation Globale et Performance
- **Auteur** : Antigravity
- **Contexte** : Amélioration de la scalabilité et de la sécurité du projet.
- **Décision** :
    1. Introduction de `SearchPostsAction` pour découpler le filtrage des posts du composant Livewire.
    2. Mise en cache (10 min) des statistiques de profil et de la heatmap pour réduire la charge SQL.
    3. Nettoyage systématique (`e()`) des snippets de code lors de la création pour prévenir les injections XSS.
- **Statut** : Appliqué.

## 2026-04-08-13 : Protection de la Branche Main et Efficacité Radicale
- **Auteur** : EnriqueP01
- **Contexte** : Consolidation du workflow de développement et protection du design.
- **Décision** : Interdiction absolue de push sur `main`, adoption d'un cycle de branchement obligatoire et directive d'économie de tokens.
- **Statut** : Actif.

## 2026-04-08-14 : Evolution V2 - Collaborative Labs & Neural Link Paradigm
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de structurer la curation en groupes privés et d'élever le lexique vers un univers plus technologique.
- **Décision** :
    1. **Labs (Unités de Collaboration)** : Implémentation du système de groupes (`Labs`) avec rôles Moderateur/Membre et visibilité d'artefacts restreinte.
    2. **Refonte Lexicale** : Migration terminologique (Email -> `Neural Link Artifact`, Password -> `Secret Key`, Vibe/Post -> `Artefact`).
    3. **Wizard de Curation V2** : Support du multi-fichiers drag-and-drop, détection automatique du langage par extension et orchestration de métadonnées granulaires (buts de revue, améliorations).
- **Impact** : Transformation de ReviewMe en une plateforme de curation collaborative d'élite, sécurisée et contextuelle.

## 2026-04-08-15 : Évolution de l'Identité Utilisateur & Branding
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Décision** :
    - **Photo de Profil** : Implémentation du stockage local des avatars (`profile_photo_path`) avec fallback sur GitHub/UI-Avatars.
    - **Identité de Curation** : Stylisation du champ identifiant avec police monospacée et préfixe `@` obligatoire pour renforcer l'esthétique dév.
    - **Branding** : Remplacement du logo textuel par l'actif `logo.png` (branding premium) et ajout d'icônes contextuelles (crayon pour l'édition).
    - **Search UX** : Refonte de la barre de recherche du feed avec effets de focus dégradé et expansion dynamique.
- **Impact** : Personnalisation accrue des profils et cohérence visuelle haut de gamme.

## 2026-04-09-16 : Optimisation de l'Architecture & Outillage de Qualité
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Présence de code mort (PostService) et absence de scripts de maintenance automatisée.
- **Décision** : 
    - **Cleanup** : Suppression du service `PostService` au profit d'une architecture 100% orientée Actions.
    - **Qualité (DX)** : Ajout des scripts `lint` et `format` (ESLint/Prettier/Pint) dans `package.json` et `composer.json`.
    - **Tests** : Initialisation de la structure `tests/Unit` et ajout des premiers tests unitaires pour les Actions métier.
- **Impact** : Réduction de la dette technique, uniformisation de la logique métier et automatisation de la qualité du code.

## 2026-04-09-17 : Audit de Performance & Extension de la Couverture de Tests
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'optimiser le Feed pour la montée en charge et de sécuriser la logique de réputation.
- **Décision** : 
    - **Optimisation SQL** : Ajout d'index stratégiques sur `posts` et refonte du Eager Loading (`latestSnippet`) pour réduire l'empreinte mémoire.
    - **Sécurisation métier** : Extension des tests unitaires sur les Actions de réaction et de réputation.
- **Impact** : Feed ultra-réactif (réduction des requêtes SQL) et zéro régression possible sur le système de Karma.

## 2026-04-09-18 : Environnement de Test Haute-Fidélité (MasterSeeder)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'un jeu de données réaliste pour tester l'intégralité des flux collaboratifs (Labs, conversations, multi-fichiers).
- **Décision** : Création du `MasterTestSeeder` incluant des utilisateurs multi-profils, des Labs avec rôles et des threads de revue de code simulés.
- **Impact** : Validation facilitée de l'expérience utilisateur complète et documentation simplifiée pour les nouveaux développeurs.
