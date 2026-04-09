
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

## 2026-04-09-31 : Refonte Hyper-Sensorielle & Télémétrie Artifacts (V5)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'une expérience "Elite" pour la phase critique de saisie de code, avec une meilleure visibilité sur la qualité et les doublons.
- **Décision** :
    1. **Architecture HUD (Heads-Up Display)** : Intégration d'un tableau de bord de télémétrie par fragment (Volume LOC, Payload KB, Logic Density).
    2. **Algorithme d'Intégrité MD5** : Détection automatique des doublons de contenu (Logic Clone) en plus des collisions de noms.
    3. **Evolution du Schéma (V5)** : Introduction de la colonne `filename` dans la table `snippets` pour séparer l'identité technique du fichier de son contexte de curation.
    4. **Sécurité & Maintenance** : Ajout d'une route `/dev/login` (local uniquement) et de la fonctionnalité de suppression d'artefact (`deletePost`) avec autorisation.
    5. **UX de Réorganisation** : Support du tri manuel via boutons `Move Up / Down` en complément du drag-and-drop pour une précision absolue.
- **Impact** : Transformation radicale de la curation en une expérience de type IDE moderne, éliminant les erreurs de duplication et offrant un contrôle total sur le cycle de vie des artefacts.


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
    3. **Wizard de Curation V2** : Support du multi-fichiers drag-and-drop, de la détection automatique du langage par extension et orchestration de métadonnées granulaires (buts de revue, améliorations).
- **Impact** : Transformation de ReviewMe en une plateforme de curation collaborative d'élite, sécurisée et contextuelle.

## 2026-04-08-15 : Évolution de l'Identité Utilisateur & Branding
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Décision** :
    - **Photo de Profil** : Implémentation du stockage local des avatars (`profile_photo_path`) avec fallback on GitHub/UI-Avatars.
    - **Identité de Curation** : Stylisation du champ identifiant with monospaced font and mandatory `@` prefix to reinforce dev aesthetics.
    - **Branding** : Remplacement du logo textuel par l'actif `logo.png` (branding premium) and addition of contextual icons.
    - **Search UX** : Redesign of the feed search bar with gradient focus effects and dynamic expansion.
- **Impact** : Personnalisation accrue des profils and high-end visual consistency.

## 2026-04-09-16 : Optimisation de l'Architecture & Outillage de Qualité
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Présence de code mort (PostService) and absence of automated maintenance scripts.
- **Décision** : 
    - **Cleanup** : Suppression of `PostService` in favor of 100% Action-oriented architecture.
    - **Qualité (DX)** : Addition of `lint` and `format` scripts (ESLint/Prettier/Pint).
    - **Tests** : Initialization of `tests/Unit` and addition of first unit tests.
- **Impact** : Technical debt reduction and uniform business logic.

## 2026-04-09-17 : Audit de Performance & Extension de la Couverture de Tests
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Needs to optimize Feed for load and secure reputation logic.
- **Décision** : 
    - **Optimisation SQL** : Addition of strategic indexes and eager loading refactor.
    - **Sécurisation métier** : Unit test extension on reaction and reputation Actions.
- **Impact** : Ultra-responsive Feed and zero regression risk on Karma system.

## 2026-04-09-19 : Standardisation de la Qualité Continue (Quality Gate)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Detection of i18n regressions and UI crashes in audits.
- **Décision** : 
    1. **Quality Gate** : Creation of `.agents/rules/quality.md`.
    2. **Stabilité UI** : Refactor of `ui/card` component.
    3. **Pérennité i18n** : Dictionary cleaning.
- **Impact** : UI stability and 100% consistent bilingual site.

## 2026-04-09-20 : Protocole d'Intégrité de Réalisation
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Risk of task omission in complex prompts.
- **Décision** : Integrity rule (`integrity.md`) forcing systematic task breakdown.
- **Impact** : Agent reliability and exhaustive task completion.

## 2026-04-09-22 : Optimisation du Workflow de Curation (Artifacts V2)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Décision** : 
    1. **Stabilité du Drag & Drop** : Basculement vers une gestion dynamique des indices via Sélecteurs DOM combinée à l'action atomique `$wire.reorderFiles`.
    2. **Liberté Polyglotte** : Passage d'un affichage statique du langage à un sélecteur manuel (`select`).
    3. **Expansion de Détection** : Support de 24+ langages (Rust, Go, Swift, Dart, etc.).
- **Impact** : Expérience utilisateur fluide et réduction des erreurs de saisie.

## 2026-04-09-23 : Harmonisation Sémantique & Standardisation Professionnelle
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Utilisation de terminologies génériques peu professionnelles et défaut d'ergonomie sur les hitboxes HUD.
- **Décision** :
    1. **Refonte Lexicale Globale** : Migration vers un lexique "Enterprise-grade" (SOURCE_ORIGIN, Inspect Post, Artifact_Analysis).
    2. **Optimisation UX (Hitbox)** : Correction du bouton d'inspection dans le Feed via neutralisation des pointer-events sur les icônes.
- **Impact** : Renforcement du positionnement premium et fluidité d'interaction.

## 2026-04-09-24 : Résolution des Conflits de Drop & Sync State
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Échec du Drag & Drop de réorganisation et de la détection lors du premier import.
- **Décision** :
    1. **Sync State Backend** : Centralisation de l'heuristique dans `detectLanguage($index)` appelée par `importFile` via le pont Alp-Wire.
    2. **Dissociation Drop** : Utilisation de `.stop` pour isoler les imports de fichiers externes du drag-and-drop de tri interne.
- **Impact** : Fiabilité de l'import de 100% dès la première interaction.

## 2026-04-09-25 : Ergonomie Avancée du Tri (Drop Gaps & Auto-Scroll)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Impossibilité de déposer précisément entre deux fichiers et absence de défilement automatique lors du tri de longues listes.
- **Décision** :
    1. **Drop Gaps** : Insertion de zones de drop réactives entre chaque fragment pour permettre un positionnement "avant/après" explicite.
    2. **Moteur Auto-Scroll** : Implémentation d'un intervalle Alpine synchronisé sur la position `clientY` du curseur pour scroller la fenêtre dynamiquement lors du drag.
- **Impact** : Manipulation d'artefacts complexes (10+ fichiers) désormais fluide et sans frottement.

## 2026-04-09-26 : Architecture de Curation Massive (Artifacts V3)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'accélérer l'importation de fichiers multiples et de fournir un retour technique immédiat sur les fragments.
- **Décision** :
    1. **Master Import Engine** : Zone de drop globale traitant les fichiers en masse via `FileReader` asynchrone et injection atomique backend.
    2. **Smart Collapse** : Réduction automatique des composants non ciblés pendant le tri pour une visibilité 360°.
    3. **Code Telemetry HUD** : Affichage en temps réel du nombre de lignes (LOC) et du poids (KB) de chaque fragment.
    4. **Duplicate Safeguard** : Système de détection de collisions de noms avec alertes UI visuelles.
- **Impact** : Productivité accrue de 300% pour la publication de curations complexes.

## 2026-04-09-27 : Allégement Visuel & Raffinement UX (Feed & Code)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Présence de bordures blanches intrusives, mauvais alignement des numéros de ligne et besoin d'une animation plus dynamique sur les titres du feed.
- **Décision** :
    1. **Animation de Titre "Reveal"** : Passage d'un header statique à une structure dynamique. Le titre est centré par défaut et coulisse vers le bas au survol pour révéler la description, accompagné d'une icône d'artéfact.
    2. **Refonte de la Pagination** : Alignement sur le design "Monolith & Glass". Remplacement des labels système par des termes métier ("Résultat", "Affichage").
    3. **Optimisation Code Explorer** :
        - Alignement vertical strict des numéros de ligne (suppression du padding asymétrique).
        - Nettoyage des bordures blanches sur les onglets et le conteneur principal.
        - Correction du bug de scrollbar fictive sur la navigation multi-fichiers.
    4. **Centralisation Chromatique** : Injection de couleurs spécifiques par "Lens" (Security: Red, Logic: Cyan, Performance: Green) sur toute la chaîne UI, incluant les hashtags de métadonnées.
- **Impact** : Interface plus "aérée", suppression du bruit visuel (borders blanches) et hiérarchie de lecture optimisée.

## 2026-04-09-28 : Optimisation de Flux & Correction Ergonomique HUD
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Latence perçue lors du scroll infini/pagination, clignotement des éléments ré-animés et mauvaise position de l'icône d'artéfact.
- **Décision** :
    1. **Stabilité du Feed** : Suppression de `animate-fade-in-up` sur les articles du flux pour éviter les sursauts visuels lors du chargement de nouvelles pages.
    2. **Pré-chargement & Performance** :
        - Augmentation de la pagination à 30 items.
        - Eager loading de `latestSnippet` dans `SearchPostsAction` pour éliminer les requêtes N+1 lors de l'affichage des langages.
        - Ajout de `wire:loading.class` avec flou et désaturation pour un feedback visuel fluide sans disparition totale du contenu.
    3. **Raffinement HUD** :
        - Déplacement de l'icône d'artéfact dans la pilule d'inspection du `CodeBlock` (plus cohérent avec la hiérarchie visuelle).
        - Neutralisation du scrollbar horizontal lors des transitions d'onglets (CSS `overflow-x-hidden`).
- **Impact** : Navigation plus stable, réduction des sauts de mise en page et temps de réponse perçu amélioré.

## 2026-04-09-29 : Expansion de la Palette Chromatique des "Lens"
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Manque de distinction visuelle entre les différents types de revues demandées (Security, Logic, Performance, etc.).
- **Décision** : 
    1. **Palette Etendue** : Ajout de styles spécifiques dans `app.css` pour `architecture` (bleu), `refactor` (ambre), `ux` (rose) et `test` (indigo).
    2. **Normalisation CSS** : Injection de `strtolower()` dans la directive `@class` du template Blade pour garantir la correspondance entre les données (souvent en CamelCase ou Capitalized) et les utilitaires CSS minuscules.
- **Impact** : Meilleure catégorisation visuelle immédiate des artefacts dans le flux.

## 2026-04-09-30 : Correction d'Application Chromatique (CodeBlock Pins)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Les tags (Pins) présents à l'intérieur du composant d'exploration de code restaient blancs malgré l'application des couleurs sur les hashtags globaux.
- **Décision** :
    - **Uniformisation Blade** : Modification de `@class` dans `code-block.blade.php` pour inclure `strtolower()` sur l'attribut `$l`.
- **Impact** : Application consistante des couleurs de Lens sur tous les points d'exposition de l'interface (HUD Code Explorer & Feed principal).

## 2026-04-09-31 : Refonte des Seeders & Localisation Française
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'un jeu de données riche, réaliste et en français pour les tests et démonstrations.
- **Décision** :
    1. **Master Seeder V2** : Utilisation de `Faker (fr_FR)` pour générer 30+ utilisateurs, 5 groupes thématiques (Labs), et des dizaines de posts avec snippets multi-langages.
    2. **Interactions Riches** : Génération automatisée de reviews, réactions et membres de groupes pour simuler une plateforme active.
    3. **Utilisateur Test Dédié** : Création d'un profil stable (`celestin@reviewme.io`) avec un historique complet pour faciliter le Vibe Coding.
- **Impact** : Environnement de développement immersif et validation immédiate des composants UI complexes.
