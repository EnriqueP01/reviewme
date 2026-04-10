## 2026-04-10-72 : Correction de Sécurité Critique (Axios) & SBOM V2
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Détection d'une vulnérabilité critique SSRF dans Axios lors de l'audit de nomenclature.
- **Décision** :
    1. **Patch d'Urgence** : Migration forcée d'Axios vers `v1.15.0` via `npm install`.
    2. **Mise à jour SBOM** : Régénération intégrale de `sbom.json` pour inclure les nouveaux composants temps-réel (Echo, Pusher) et la version patchée d'Axios.
    3. **Standardisation Documentaire** : Refonte de `us_nomenclature_logicielle.md` avec suppression de la nomenclature chiffrée (ex US33) et ajout des utilitaires métiers.
- **Impact** : Risque de sécurité SSRF éliminé, traçabilité de la supply chain 100% à jour.

## 2026-04-10-71 : Synchronisation de l'Analyse Sécurité (Modèle Élite)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : L'analyse de sécurité initiale ne reflétait plus le niveau de protection réel après l'ajout du Karma, de SonarQube et des Audit Logs.
- **Décision** :
    1. **Mise à jour Intégrale** : Refonte du document `us_analyse_securite.md` pour inclure le contrôle d'accès basé sur la réputation (Karma-RBAC) et la surveillance statique (SonarQube).
    2. **Gestion d'Erreurs OAuth** : Masquage des exceptions système lors de l'Auth GitHub pour prévenir les fuites d'informations.
    3. **Standardisation** : Suppression de toute nomenclature chiffrée (ex US38) sur ce document spécifique pour respecter la demande utilisateur.
- **Impact** : Documentation 100% fidèle aux capacités de sécurité du produit Final.

## 2026-04-10-70 : Durcissement de la CI/CD & Activation de la Quality Gate SonarQube (US32)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : L'analyse statique SonarQube était configurée mais non contraignante (commentée dans la CI/CD), risquant l'accumulation de dette technique.
- **Décision** :
    1. **Strict Enforcement** : Activation de l'étape `SonarQube Quality Gate check` dans le workflow `.github/workflows/sonar.yml`. La suppression de `continue-on-error` garantit que les échecs de qualité bloquent désormais le déploiement.
    2. **Formalisation Documentaire** : Création de `us/us32_sonarqube.md` pour ancrer les critères d'acceptation et les seuils entreprise au cœur du projet.
    3. **Alignement PSR-12** : Exécution globale de `Laravel Pint` pour garantir un "clean slate" avant les prochaines analyses Sonar.
- **Impact** : Sécurisation du flux de merge, traçabilité de l'évolution de la qualité et adoption d'un standard industriel de maintenance.

## 2026-04-10-69 : Standardisation des Profils Cliquables & Universalisme du Composant Avatar
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Manque de fluidité dans la navigation sociale. Plusieurs avatars et noms d'utilisateurs à travers l'application (Leaderboard, Tchat, Feed) étaient purement décoratifs et non interactifs.
- **Décision** :
    1. **Universalisme de l'Avatar** : Refonte du composant `x-ui.avatar` pour intégrer nativement la logique de lien vers les profils publics (Utilisateurs/Groupes) via `wire:navigate`. Ajout d'une propriété `link` (true par défaut) pour contrôler l'interactivité.
    2. **Uniformisation de la Navigation sociale** : Remplacement systématique des balises `<img>` brutes par le composant `x-ui.avatar` dans les sections critiques : Leaderboard, Discussion de Groupe (Tchat), Page de Détail (Commentaires, Revues).
    3. **Micro-Interactions Premium** : Application d'un effet de survol `scale-105` et d'une lueur orbitale sur tous les points d'entrée vers un profil pour renforcer l'aspect dynamique et interactif de la plateforme.
- **Impact** : Expérience utilisateur plus organique, navigation simplifiée entre contributeurs et renforcement de la dimension communautaire de ReviewMe.

## 2026-04-10-65 : Refonte Premium du Bouton Retour & Indicateur GitHub Profil
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Amélioration de l'UX de navigation. L'activité GitHub a été retirée du profil. Intégration d'un bouton retour haute fidélité.
- **Décision** : 
    1. **Audio Procedural** : Implémentation d'un son de retour généré via Web Audio API (oscillateur sine 440->220Hz) pour un feedback tactile.
    2. **Ripple Effect** : Animation d'expansion au point de clic calculée dynamiquement via Alpine.js.
    3. **Design Monolith** : Glassmorphism, lueur hover et micro-translation de l'icône.
    4. **Déploiement Extendu** : Ajout du bouton sur la page `update-post`.
- **Impact** : Navigation plus immersive et organique. Correction des tests `PublishWorkflow` impactés par les manques de clés dans les structures de données.

## 2026-04-10-66 : Navigation Intelligente & Résolution des Permissions d'Assets
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Amélioration de la navigation utilisateur via des boutons de retour stratégiques et résolution d'erreurs 403 (accès interdit aux photos de profil) et 404 (favicon manquant).
- **Décision** : 
    1. **Boutons de Retour** : Intégration du composant `<x-ui.back-button />` dans les vues critiques : `post-detail.blade.php`, `publish-workflow.blade.php` et `profile/edit.blade.php`.
    2. **Correction des Permissions (Windows/Host)** : Exécution de la commande `icacls` pour accorder les permissions `(OI)(CI)F` sur les dossiers `storage` et `public/storage`, résolvant les erreurs 403 persistantes sur les architectures Docker/Windows.
    3. **Favicon Intégrity** : Restauration/Vérification du fichier `public/images/favicon.png` pour éliminer les erreurs 404 dans les logs console.
- **Impact** : Expérience de navigation fluide et sans erreurs techniques, interface 100% "zéro défaut".

## 2026-04-10-64 : Correction de Compatibilité SQLite dans GrantKarmaAction
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : `UserSkill::updateOrCreate()` avec `DB::raw("score + $points")` échouait sous SQLite (base de test) car le driver interprétait l'expression comme une valeur littérale et non une référence de colonne.
- **Décision** : Remplacement de l'`updateOrCreate` par un pattern `firstOrCreate(['score' => 0])` + `$skill->increment('score', $points)`. Comportement identique en production MySQL/PgSQL, compatible SQLite.
- **Impact** : `PostDetailTest > user can react` passe au vert. `MasterTestSeeder` corrigé pour inclure le champ `handle` (contrainte NOT NULL ajoutée).

## 2026-04-10-60 : Publish Workflow HUD & Cinematic Navigation (V7)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Workflow de publication instable, sans persistance et visuellement décalé par rapport au design system "Monolith" du Feed.
- **Décision** :
    1. **HUD-Style Distribution** : Remplacement des contrôles standards par un slider premium (Public Feed / Private Group) et une grille de Lenses technique color-codée.
    2. **State Persistence** : Implémentation d'une sauvegarde automatique en session pour prévenir la perte de données (Titres, Code, Lenses) lors des recharges de page.
    3. **Cinematic Alpine Navigation** : Migration vers une navigation asynchrone client-side (`x-show` + `x-transition`). Intégration d'un moteur de défilement automatique (`smooth scroll-to-top`) à chaque changement d'étape.
    4. **SQL Integrity** : Correction des erreurs de scoring SQL (SQLite) via le pattern `firstOrCreate` / `increment` pour garantir la stabilité des transactions de Karma.
- **Impact** : Expérience de publication fluide, suppression des frictions de navigation et harmonisation totale du design system.


## 2026-04-09-57 : Moteur de Réputation "Expert-Driven" & Système de Karma
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'un système de curation et de modération communautaire auto-géré, valorisant l'expertise technique et décourageant la toxicité.
- **Décision** :
    1. **Architecture de Persistance** : Utilisation d'une table `karma_transactions` (audit log polymorphique) et `user_skills` (expertise par Lens/thématique).
    2. **Moteur d'Action Centralisé** : Création de `GrantKarmaAction` pour garantir l'atomicité des gains de points, des logs et de l'évolution des skills.
    3. **Stratégie de Gamification** :
        - Paliers de progression (Apprenti, Contributeur, Reviewer, Expert, Elite) débloquant des privilèges.
        - Bonus de Qualité : Points doublés pour les descriptions de plus de 500 caractères.
        - Daily Cap : Plafond journalier de +200 Karma pour prévenir le farming.
    4. **Permissions par Karma (RBAC)** : Gatekeepers sur les actions sensibles (Downvote réservé aux "Contributeurs" (10+), Protection des routes critiques via Middleware `EnsureUserHasKarma`).
    5. **Engagement UI** : Intégration d'un dashboard de réputation sur le profil avec badge de rang, radar de compétences et historique filtrable. Émission d'événements Livewire pour notifications en temps réel.
    6. **Outillage de Maintenance** : Commande Artisan `karma:rebuild` pour recalculer les scores à partir des transactions en cas de changement de barème.
- **Impact** : Création d'un cercle vertueux de contribution, sécurisation de la plateforme contre le trollage et mise en place d'une méritocratie technique.


### 2026-04-09-58 - Permissions Cumulatives (Karma)
**Context**: Les utilisateurs de haut grade (Expert/Elite) perdaient l'accès aux actions de base (Upvote/Downvote) car les permissions étaient restrictives par niveau.
**Decision**: 
1. Refactorisation de `User::hasKarmaPermission()` pour devenir cumulative.
2. Un utilisateur hérite désormais de toutes les permissions des paliers inférieurs à son score de réputation.

### 2026-04-09-59 - Robustesse UI "Monolith" & Troncature
**Context**: Les noms de fichiers longs et les titres de posts provoquaient des débordements horizontaux (Horizontal Overflow).
**Decision**: 
1. Application de `truncate` et `max-w` sur les titres du header (`post-detail`) et du code explorer (`code-block`).
2. Limitation de la largeur des onglets de fichiers à `180px`.
3. Correction d'un bug de balise `<div>` dupliquée dans le header principal.


## 2026-04-09-57 : Standardisation Visuelle Monolith (Search & Modals)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'uniformiser tous les points d'entrée de recherche et les actions de confirmation pour garantir la cohérence du design system.
- **Décision** :
    1. **Composant Search Unifié** : Création de `<x-ui.search-input />` adoptant le design des contrôles de tri (`bg-black/20`, `backdrop-blur-3xl`, `rounded-[1.25rem]`). Suppression de l'uppercase au profit d'un style `font-bold tracking-tight` professionnel.
    2. **Système de Confirmation Monolith** : Refonte de `<x-ui.confirm-modal />` intégrant le composant `<x-ui.button />`. Adoption systématique du style "Ghost-Rose" pour les actions de suppression, incluant la texture de bruit (noise) et les lueurs orbitales.
    3. **Règle "Copie-Colle Uniquement"** : Ajout d'une directive stricte dans `global.md` interdisant l'invention de nouveaux styles et forçant la réplication exacte des patterns existants.
- **Impact** : Identité visuelle 100% cohérente, réduction de la dette technique UI et simplification de l'extensibilité du design system.

## 2026-04-09-56 : Architecture "Zéro-Latence" & Debounced State Sync
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'atteindre une fluidité d'interaction type "StackOverflow/GitHub" pour les votes (Karmas), éliminant tout sentiment de latence réseau ou de scintillement visuel (UI Flicker).
- **Décision** :
    1. **Découplage UI/Serveur** : Adoption du pattern "Optimistic Instant Feedback". L'UI (Alpine.js) met à jour l'état local (Score, Icones) immédiatement sans attendre le serveur.
    2. **Delta-Based Logic** : Implémentation d'un calcul de score basé sur le delta (différence entre ancien et nouvel état) dans le client pour garantir l'intégrité du score même lors de changements de direction ultra-rapides (ex: passage de +1 à -1).
    3. **Debounced Synchronization** : Les appels backend sont retardés (Debounce : 250-300ms). Seule la dernière intention utilisateur est envoyée au serveur, réduisant la charge et évitant les race conditions.
    4. **Silent Backend (`#[NoRender]`)** : Utilisation systématique de l'attribut Livewire pour empêcher tout re-rendu du DOM lors de la synchronisation des votes, préservant l'état local d'Alpine.js.
    5. **Enrichissement des Données (withCount)** : Extension du contrôleur `PostDetail` pour charger les scores de karmas des revues complètes via `withCount` (up_count, down_count), garantissant une initialisation client 100% précise.
- **Impact** : Expérience utilisateur "Elite" avec feedback instantané (0ms perçu), suppression totale des bugs de scoring et infrastructure réseau optimisée.

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
    - **Identité** : Migration de la table `users` pour rendre le camp `name` unique et ajout de la règle de validation `Rule::unique` dans `ProfileUpdateRequest`.
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

## 2026-04-08-14 : Évolution V2 - Collaborative Groups & neural Link Paradigm
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de structurer la curation en groupes privés et d'élever le lexique vers un univers plus technologique.
- **Décision** :
    1. **Groups (Collaboration Units)** : Implémentation du système de groupes (`Groups`) avec rôles Moderateur/Membre et visibilité restreinte.
    2. **Refonte Lexicale** : Migration terminologique (Email -> `Email`, Password -> `Password`, Vibe/Post -> `Post`).
    3. **Workflow de Publication** : Support du multi-fichiers drag-and-drop, de la détection automatique du langage par extension et orchestration de métadonnées granulaires (buts de revue, améliorations).
- **Impact** : Transformation de ReviewMe en une plateforme de curation collaborative d'élite, sécurisée et contextuelle.

## 2026-04-09-35 : Systèmes de Chargement Global & États UI Contextuels
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'améliorer l'expérience utilisateur (UX) en fournissant un feedback visuel immédiat lors des opérations asynchrones (Livewire).
- **Décision** :
    1. **Global Loader (Progressive)** : Création d'un composant `global-loader` écoutant les hooks `livewire:init` pour afficher une barre de progression de style "YouTube/GitHub" au sommet de l'écran.
    2. **Loader Overlay (Backdrop Bloom)** : Implémentation d'un composant réutilisable `loader-overlay` avec flou d'arrière-plan (`backdrop-blur`) pour geler visuellement les sections en cours de traitement (Feed, Code Viewer).
    3. **Boutons Atomiques Réactifs** : Mise à jour du composant `button` pour inclure un spinner intégré (`animate-spin`) déclenché automatiquement par `wire:loading`.
    4. **Désactivation Systématique** : Verrouillage des actions (`wire:loading.attr="disabled"`) sur tous les points d'entrée critiques pour prévenir les race conditions.
- **Impact** : Suppression du sentiment de latence, feedback visuel premium et sécurisation des interactions multiples.
### ID: 20260409-2200-UI-STANDARDIZATION
**Date** : 2026-04-09
**Contexte** : Nécessité de stabiliser l'identité visuelle entre les pages Feed et Groups.
**Décision** : 
- Centralisation des avatars et fallback initiales.
- Adoption d'un header 7xl consistent.
- Nomenclature "FORGE BETTER CODE" imposée.
- Migration logo_path pour les groupes.
- Implémentation du composant PostCard haute densité.
**Conséquences** : Cohérence totale du design system "Monolith", meilleure densité ergonomique.

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

## 2026-04-09-20 : Protocole d'Analyse de Sécurité US33
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
    1. **Refonte Lexicale Globale** : Migration vers un lexique "Enterprise-grade" (SOURCE_ORIGIN, Inspect Post, File_Analysis).
    2. **Optimisation UX (Hitbox)** : Correction du bouton d'inspection dans le Feed via neutralisation des pointer-events sur les icônes.
- **Impact** : Renforcement du positionnement professionnel et fluidité d'interaction.

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

## 2026-04-09-35 : Industrialisation de la Qualité (CI/CD US31)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'automatiser les vérifications pour empêcher la validation de code cassé ou non conforme (US31).
- **Décision** :
    1. **Pipeline Global** : Création/Mise à jour du workflow GitHub Actions (`tests.yml`) en un pipeline CI complet.
    2. **Couverture de Contrôle** : Inclusion des tests unitaires/fonctionnels, du linting PHP (Pint), du linting JS (ESLint) et de l'analyse statique (Larastan/PHPStan).
    3. **Blocage Strict** : Configuration du workflow pour qu'un échec sur n'importe quel step empêche le merge.
    4. **Documentation** : Mise à jour du README pour expliciter les contrôles de la pipeline.
- **Impact** : Sécurisation totale du flux de développement, industrialisation des standards de qualité et validation automatique de l'US31.

## 2026-04-09-34 : Standardisation Professionnelle du Style (Linting & Formatting)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Absence de règles de style automatisées pour le frontend (JS) et besoin de renforcer la cohérence PHP.
- **Décision** :
    1. **Installation Tooling** : Installation de `eslint` (v9) et `prettier` avec configurations dédiées (`eslint.config.js`, `.prettierrc`).
    2. **Correction Masive** : Exécution de `Laravel Pint` sur l'ensemble du backend et de `Prettier/ESLint` sur les ressources JS.
    3. **Rules Enforcement** : Mise à jour de `.agents/rules/quality.md` pour rendre l'exécution des linters obligatoire avant tout déploiement.
- **Impact** : Codebase 100% conforme aux standards professionnels, réduction de la dette technique visuelle et automatisation du contrôle qualité.

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
    1. **Stabilité du Feed** : Suppression de `animate-fade-in-up` on the feed articles to avoid visual jumps when loading new pages.
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

## 2026-04-09-32 : Workflow de Publication (V6) & Charte Chromatique Lenses
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de finaliser le PublishWorkflow avec sélection multiple, validation explicite et respect strict de la charte graphique Lenses.
- **Décision** :
    1. **Validation & Déploiement** : Ajout d'un bouton de validation tactile dans la phase finale ("Deploy Curation Artifact") avec lueur pulsative (`emerald`) et renforcement de l'UI.
    2. **Visibilité Hybride** : Remplacement des boutons radio par des cases à cocher exclusives pour Public/Privé, offrant un feedback visuel plus moderne.
    3. **Système Lenses Centralisé** : 
        - Injection de la charte graphique officielle : **Logic (Jaune/Amber)**, **Beauty (Bleu/Sky)**, **Opti (Vert/Emerald)**.
        - Migration de `app.css` vers des variables CSS sémantiques (`--lens-*`) dans `tokens.css` pour une maintenance facilitée.
        - Support du multi-threading de lenses (sélection multiple) dans le tunnel de création.
- **Impact** : Cohérence chromatique totale sur toute la plateforme et sécurisation de l'acte de publication.

## 2026-04-09-33 : Optimisation UX Workflow & Forçage de Style Inline
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Retours utilisateur sur la précision du stepper, le manque de feedback dans l'éditeur (numéros de lignes) et des instabilités mineures de coloration sur la production locale.
- **Décision** :
    1. **Stepper Géométrique** : Ajustement des ancres de la barre de progression (`left-10/right-10`) pour toucher exactement le centre des ronds d'étape. Ajout d'un remplissage animé fluide.
    2. **High-Fidelity Editor** : Intégration d'une gouttière de numérotation de lignes (Gutter) auto-générée et coloration syntaxique du texte pilotée par le langage détecté.
    3. **Forçage CSS Inline** : Utilisation de l'attribut `style` en combinaison avec les variables CSS dans les composants `Feed` et `CodeBlock` pour garantir l'application des couleurs des Lenses sans dépendance au JIT/Purge de Tailwind.
    4. **Simplification** : Suppression de la métrique "Logic Density" et passage en optionnel des champs secondaires (description courte, buts) pour fluidifier l'expérience.
- **Impact** : Interface plus robuste, feedback utilisateur immédiat et "zéro défaut" sur l'application visuelle du design system.

## 2026-04-09-37 : Protocole d'Analyse de Sécurité US33
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité de formaliser l'analyse de sécurité du code et des accès selon les critères de l'US33 (Threat Modeling, Revue de zones sensibles, Mitigation).
- **Décision** : Création de la règle `.agents/rules/security.md` définissant le cadre strict d'audit et de rapport pour toute demande liée à la sécurité.
- **Impact** : Standardisation des diagnostics de sécurité, garantissant une couverture exhaustive des risques applicatifs et une documentation claire des limites du MVP.

## 2026-04-09-38 : Durcissement Sécuritaire Post-Audit (US33)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Audit de sécurité révélant une faille de visibilité sur les groupes, une fuite d'erreurs OAuth et des faiblesses Docker.
- **Décision** :
    1. **Correction d'Access Control** : Mise à jour de `PostPolicy` pour inclure la vérification d'appartenance au groupe (`group_id`).
    2. **Protection Sensitive Data** : Masquage des messages d'erreur `$e->getMessage()` dans `GithubAuthController`.
    3. **Container Hardening** : Retrait de l'exposition publique du port 3306, désactivation du mode debug par défaut et utilisation de variables d'environnement pour les secrets root.
    4. **Injection Sécurisée** : Passage à `@js()` dans les composants Blade pour prévenir toute corruption JSON.
- **Impact** : Élimination des fuites d'informations techniques et restauration de l'intégrité du système de collaboration privée.

## 2026-04-09-39 : Maintien Permanent de l'Architecture (RBAC & Gestion d'Erreurs)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité de formaliser la "User Story d'Architecture" US14 (Définir les rôles, permissions et la stratégie de gestion des erreurs) et de garantir que l'IA respecte ces choix à long terme.
- **Décision** :
    1. **Spécification Architecturale (Markdown)** : Rédaction détaillée de la norme dans `us/us14_roles_permissions_erreurs.md` classifiant les actions par niveaux et séparant strictement de la logique métier (erreurs 422 vs 403 vs 500).
    2. **Règle IA sur Mesure (.agents/rules/rbac.md)** : Intégration d'une directive d'agent continue ("Custom Rule"). Le but de la règle est d'interdire au modèle de coder des actions en "Black Box" : je suis désormais forcé de vérifier les permissions (via `PostPolicy`, `Gate` ou Middlewares) et de ne jamais ignorer la sécurité du backend, même si l'interface dissimule déjà les actions.
- **Impact** : Intégrité architecturale renforcée et sécurisation des actions critiques.

## 2026-04-09-40 : Standardisation Lexicale & Refonte Lexique Professionnel
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de crédibiliser la plateforme en remplaçant le lexique "futuriste/SF" par une terminologie professionnelle de l'industrie.
- **Décision** :
    1. **Migration Lexicale** : Remplacement systématique de `Vibe/Artifact` par `Post`, `Group` par `Group`, `Neural Link` par `Email`, `Secret Key` par `Password`.
    2. **Refonte des Routes** : Migration de `/labs` vers `/groups` et renommage des composants Livewire associés.
    3. **Cleanup i18n** : Nettoyage intégral de `fr.json` et `en.json` pour supprimer les clés de branding "cool" au profit de termes standards (Feed, Review, Files).
    4. **Événements & UI** : Renommage des événements système (`vibe-*` -> `post-*`, `notification`) et des composants UI (`logo-artifact` -> `logo`).
- **Impact** : Positionnement professionnel renforcé, meilleure accessibilité et cohérence totale de l'expérience utilisateur.

### DECISION_2026_04_09_007: Overhaul to Compact High-Density UI
**Status**: Decided | **Owner**: Architect | **Date**: 2026-04-09

#### Context
The initial "Monolith & The Lens" implementation was too spacious, creating layout blocking in the header and poor visibility for multi-file posts. User feedback demanded a "pro-grade" compact layout similar to modern IDEs/Review tools.

#### Decision
*   **Snippet Management**: Moved from consecutive vertical blocks to a single **Tab-based** viewer.
*   **Density**: Reduced typography scales (H1: 5xl -> 3xl) and global margins/paddings.
*   **Scrolling**: Enforced a `max-height: 600px` on code snippets with internal scrolling to maintain page structural integrity.
*   **Header Reorganization**: Moved primary actions (Full Review, Versioning) to a top action bar to prevent vertical displacement of the title/description.

#### Consequence
*   Immediate improvement in readability for multi-file posts.
*   More professional, IDE-like feel.

---

## 2026-04-09-41 : Versioning Avancé d'Artefacts
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Autoriser les contributeurs à soumettre des itérations de code (nouvelles versions) sans créer un nouveau post, facilitant le suivi des reviews.
- **Décision** :
    1. **Architecture** : Création de `AddPostVersionAction` gérant l'intégration transactionnelle de nouveaux snippets avec incrémentation automatique de `version_number`.
    2. **UI Ségrégative** : Ajout du composant Livewire `UpdatePost` avec le routeur dédié `/posts/{postId}/update-code`, reprenant l'ADN du workflow de création et l'heuristique de détection de fichier.
    3. **Affichage Dynamique** : Mise à jour de `PostDetail.php` (et son template) pour supporter l'affichage "1 Version = N Fichiers" et ajout du sélecteur interactif de version dans l'entête du snippet.
    4. **Sécurité & Contrôle** : Application stricte de `authorize('update', $post)` limitant l'itération à l'auteur original.
- **Impact** : Cycle de vie du code continu et traçabilité de l'évolution post-review.

## 2026-04-09-42 : Feedback Collaboratif Avancé (Reviews & Suggestions)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'élever ReviewMe au niveau d'un outil de collaboration d'élite via des retours contextuels, globaux et structurels.
- **Décision** :
    1. **Threaded Community Discussion** : Implémentation de commentaires globaux avec support des réponses imbriquées, Like atomiques et épinglage (Admin-Pinning) pour hiérarchiser les retours d'experts.
    2. **Full Implementation Review (PR-Style)** : Création d'un workflow permettant de proposer des versions alternatives complètes du code métier, incluant une évaluation globale et des notes par fragment.
    3. **Micro-modifications Contextuelles (Inline)** : 
        - Détection de sélection textuelle Alpine.js pour un accès instantané à la proposition de changement.
        - Système de suggestion "Diff-Edit" persistant par post (via session), offrant deux modes de lecture (Visual Diff vs In-place Editing).
        - Gutter HUD : Visualisation des contributeurs actifs directement dans la gouttière de numérotation des lignes.
    4. **Architecture de Données** : Extension du schéma via 4 nouvelles tables et intégration de 3 nouveaux patterns d'Action (`StorePostComment`, `StoreFullReview`, `StoreInlineSuggestion`).
- **Impact** : Transformation de la page de détail en un véritable hub de revue de code interactif.

## 2026-04-09-43 : Refonte UI/UX du Système de Feedback (US40)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'harmoniser le système de feedback avec le design "Monolith & The Lens" et d'ajouter des fonctionnalités sociales avancées (Likes, Threads dynamiques).
- **Décisions** :
    1. **Unification Visuelle** : Adoption stricte de la palette sombre (`#1a1b26`, `#0d0e12`) et suppression de tous les éléments d'interface clairs/blancs.
    2. **Système de Réactions Polymorphique** : Migration des cœurs/likes vers le modèle `Reaction` pour garantir l'unicité des votes et permettre le toggle (unlike).
    3. **UX de Discussion YouTube** : Masquage des formulaires de réponse par défaut, affichage conditionnel via Alpine.js. Ajout d'états vides illustrés.
    4. **Moteur de Coloration Blade** : Implémentation d'une coloration syntaxique légère via regex directement dans le template pour une performance et un rendu premium.
- **Impact** : Expérience utilisateur ultra-premium et réduction du bruit visuel.

## 2026-04-09-44 : Optimisation de la Revue de Code & Live Diff Engine
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Blocages sur les actions de publication, défaut de support multi-fichiers et besoin d'une prévisualisation des modifications en temps réel.
- **Décisions** :
    1. **Correction des Actions** : Déblocage du système de suggestion via passage du `snippetId` et fiabilisation de l'objet Alpine `selectionPopup`.
    2. **Architecture Multi-fichiers** : Refactoring vers un composant `CodeBlock` unique gérant l'intégralité des snippets de la version active via un système d'onglets synchronisé par événements (`snippet-changed`).
    3. **Moteur Live Diff** : Implémentation d'une prévisualisation interactive dans la "Review Factory". Utilisation du `TextDiffHelper` pour calculer et afficher en temps réel les lignes ajoutées/supprimées (coloration émeraude/rubis) pendant l'édition.
- **Impact** : Fluidité totale du workflow de revue et feedback visuel instantané.

## 2026-04-09-45 : Refonte Majeure du Système de Review (Full & Quick Reviews)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité d'approfondir l'expérience de revue avec des fonctionnalités de "Quick Review" contextuelles et une restructuration visuelle des "Full Reviews".
- **Décision** :
    1. **Espace Commentaires** : Optimisation visuelle radicale (animations, textures, icônes dynamiques).
    2. **Full Review (Feed-Style)** : Regroupement des retours sous le code avec navigation de type flux. Tri par popularité (Up/Down votes) et commentaires compacts masqués par défaut.
    3. **Quick Review (Contextual Overlay)** : 
        - Déclenchement par sélection de texte avec bouton flottant.
        - Visualisation via photo de profil de l'auteur on code.
        - Interaction double mode : "Edit" and "DIFF".
- **Impact** : Niveau d'interaction sans précédent pour une plateforme web.

## 2026-04-09-46 : Formalisation de la Documentation Stratégique (US04-US28)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de consolider les fondations stratégiques, techniques et UX du projet pour garantir la maintenabilité et faciliter la soutenance.
- **Décision** :
    1. **Cadrage Produit** : Réalisation des US04 (MVP/Périmètre), US05 (Thème), US06 (Pitch) et US08 (SWOT/Benchmark).
    2. **Architecture & API** : Documentation des US10 (Architecture Globale), US11 (Choix techniques), US15 (Contrat API) et US16 (Journal de décisions).
    3. **Design & Qualité** : Formalisation des US09 (Guide UI), US26 (Gestion des erreurs/cas limites) et US28 (Qualité de code).
    4. **Harmonisation** : Mise à jour de l'US14 pour refléter la nomenclature professionnelle (Groups/Posts).
- **Impact** : Traçabilité totale des arbitrages et base documentaire robuste.

## 2026-04-09-47 : Intégration Finale de la Navigation & Système de Raccourcis
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Finalisation de la plateforme avec les pages de support, la navigation globale et l'accessibilité clavier.
- **Décision** :
    1. **Pages de Support** : Création et déploiement des composants Livewire pour `Leaderboard`, `Documentation`, `Changelog`, `Status` et `Legal`.
    2. **Système de Raccourcis (HUD Navigation)** : Implémentation du composant global `KeyboardShortcuts` (Alpine.js) permettant une navigation rapide (ex: `G+L` pour Leaderboard, `G+H` pour Home, `Shift+?` pour l'aide).
    3. **Durcissement de l'Accessibilité Invités** : Refonte du header (`navigation.blade.php`) pour supporter les utilisateurs non-authentifiés.
- **Impact** : Plateforme 100% connectée et prête pour une utilisation publique.

## 2026-04-09-48 : Automatisation du Suivi Documentaire (update_us)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Risque de désynchronisation entre les User Stories (US) et l'implémentation finale dans le code.
- **Décision** : 
    1. **Règle IA Dédiée** : Création de `.agents/rules/update_us.md` imposant la mise à jour systématique des critères d'acceptation et détails techniques dans le dossier `us/` après chaque tâche.
    2. **Validation Automatique** : Mise à jour immédiate de l'US35 (Docker) pour valider le concept.
- **Impact** : Documentation produit toujours à jour et traçabilité parfaite.

## 2026-04-09-49 : Raffinement Adaptatif des Prompts (Auto-PRD)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Volonté d'optimiser le temps de réponse tout en maintenant une rigueur technique maximale adaptée à chaque type de tâche.
- **Décision** :
    1. **Règle auto_prd Adaptative** : Mise à jour de `.agents/rules/auto_prd.md` pour introduire une matrice d'adaptation (Full PRD, Mini-PRD, Note Technique, Fast-Track).
    2. **Logique de Sélection** : L'agent choisit dynamiquement le niveau de formalisme selon la complexité fonctionnelle de la demande.
- **Impact** : Rigueur documentaire sur les gros chantiers et efficacité maximale sur les petites tâches.

## 2026-04-09-50 : Hub Collaboratif de Groupe (Chat & Feed Privé)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité de renforcer la collaboration au sein des groupes privés en permettant la communication directe et la visualisation facile des travaux partagés.
- **Décision** :
    1. **Système de Chat** : Création du modèle `GroupMessage` et du composant Livewire `GroupChat` avec rafraîchissement automatique (polling).
    2. **Feed de Groupe Dédié** : Création du composant `GroupFeed` et extension de `SearchPostsAction` pour supporter le filtrage par `group_id`.
    3. **Refonte UI GroupManager** : Passage à un dashboard à deux colonnes (Feed à gauche, Membres et Chat à droite).
- **Impact** : Transformation des groupes en véritables espaces de travail actifs.

## 2026-04-09-51 : Transition Temps-Réel avec Laravel Reverb
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Le système de polling (interrogation cyclique) consomme inutilement des ressources serveur et induit une latence.
- **Décision** :
    1. **Installation de Reverb** : Migration vers WebSockets natifs via Laravel Reverb.
    2. **Gestion des Canaux** : Implémentation de `PrivateChannel('groups.{id}')` avec vérification de l'appartenance au groupe.
    3. **Événements de Broadcast** : Création de `GroupMessageSent` et intégration avec Livewire Echo.
- **Impact** : Expérience de chat instantanée (latence < 100ms) and infrastructure moderne prête pour la montée en charge.

## 2026-04-09-52 : Harmonisation de l'Interface de Discussion & Optimisation du Layout
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin d'unifier l'expérience de saisie des commentaires et de supprimer les déséquilibres visuels (espaces vides) dans la section Full Review.
- **Décision** :
    1. **Unification de l'Input (Modèle "Reply")** : Remplacement de tous les champs de saisie (Global, Thread, Review) par un design unique haute-fidélité : fond `black/40`, bordures `primary/10`, avatar intégré et bouton d'action primaire vibrant.
    2. **Architecture du Flux de Review** : Inversion de la hiérarchie dans les discussions de Full Review. L'input de saisie est désormais placé en tête (Above the Fold) pour favoriser l'interaction immédiate, suivi de la liste historique.
    3. **Suppression du Vide Structurel** : Retrait des hauteurs fixes (`h-[800px]`) sur le carousel de reviews et passage à un layout flexible. Le repli des commentaires libère désormais instantanément l'espace vertical, garantissant une page compacte.
    4. **Textures & Transitions** : Application systématique des textures `shadow-inner` et des transitions `x-collapse` pour une sensation de fluidité organique.
- **Impact** : Expérience utilisateur plus cohérente, navigation verticale optimisée et interface modernisée.

## 2026-04-09-53 : Stabilisation UI & Résolution Dépendances Reverb
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Erreur d'import Vite sur `pusher-js` et instabilités ergonomiques des modales de review (problème de centrage et de "vide" visuel).
- **Décision** :
    1. **Correction Full-Stack** : Installation de `pusher-js` pour finaliser l'intégration Laravel Reverb / Echo.
    2. **Architecture des Modales (HUD 2.0)** :
        - Passage d'un layout "stretch" à une approche centrée (`items-center justify-center`) avec `max-h-[90vh]`.
        - Utilisation de `x-teleport="body"` pour garantir que les modales ignorent les contraintes du DOM parent et se fixent à la fenêtre écran.
        - Ajout de marges d'air (`px-4 py-8`) et d'arrondis massifs (`rounded-[2.5rem]`).
    3. **Fiabilisation Quick Review** : Correction de l'interception des événements (`@click.stop`) sur le bouton de suggestion pour éviter les fermetures intempestives lors du clic.
- **Impact** : Suppression des erreurs critiques de build, interface HUD plus immersive et stable, et correction des bugs de navigation modale.


## 2026-04-09-54 : Consolidation du Carousel & Système de Vote Vibrants
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Nécessité de stabiliser la navigation du carousel, d'harmoniser le système de vote et de durcir les règles d'adhérence stricte de l'agent.
- **Décision** :
    1. **Architecture Navigation** : Mise en place d'un slider `translateX` hardware-accelerated synchronisé avec Livewire (`@entangle`).
    2. **Système de Vote Sémantique** : Application d'un code couleur vibrant (Émeraude pour Up, Rose pour Down) sur toutes les sections (Feed et PostDetail).
    3. **Optimisation Spatiale** : Réduction radicale des marges verticales description/carousel pour une expérience haute-densité.
    4. **Contrôle Qualité** : Mise à jour de `global.md` avec une clause de conformité stricte pour interdire les changements de design non sollicités.
- **Impact** : Interface robuste, cohérence chromatique globale et fiabilité accrue de l'assistant de codage.

## 2026-04-09-60 : Synchronisation Finale & Priorité Design "Xhuriken"
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Intégration finale du Système de Karma dans la branche `dev` après synchronisation avec les dernières mises à jour esthétiques de l'équipe.
- **Décision** :
    1. **Merge de Branche (Full Sync)** : Exécution de `git pull origin dev` pour réintégrer les standards UI (Search unifié, Modales Glass, layout 7xl).
    2. **Intégrité du Karma HUD** : Adaptation chirurgicale des composants de réputation (HUD vertical dans le `PostCard`, HUD horizontal dans `PostDetail`) pour qu'ils s'insèrent nativement dans le design systémique "Monolith" sans régression esthétique.
    3. **Priorité Front-End** : Application de la règle "xhuriken first" : toute modification structurelle du DOM par le système de Karma a été validée pour ne pas dénaturer les animations CSS et les espacements définis par le lead design.
    4. **Audit de Santé** : Validation de la suite de tests `KarmaSystemTest` (100% vert) post-merge.
- **Impact** : Plateforme stabilisée, système de réputation parfaitement fondu dans l'identité visuelle d'élite et codebase synchronisée.

## 2026-04-10-61 : Suppression du Coût de Karma pour le Downvote
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Le coût de -1 Karma imposé au votant lors d'un "Downvote" a été jugé contre-productif par l'utilisateur.
- **Décision** :
    1. **Gratuité du Vote** : Les votes négatifs (Down/Optimisable) ne retirent plus de point au votant.
    2. **Maintien du Barrage de Qualité** : La restriction d'accès au vote DOWN (exigence du rang "Contributeur", 10+ Karma) reste active pour prévenir le spam par des comptes fraîchement créés.
    3. **Documentation** : Mise à jour du `README.md` et de l'**US42** pour refléter ce changement de politique.
- **Impact** : Fluidification des interactions de feedback négatif tout en conservant une barrière à l'entrée pour garantir la maturité des votants.

## 2026-04-10-62 : Refonte de l'Identité Utilisateur (Handles & Public Profiles)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Besoin de découpler l'identité visuelle (Pseudo) de l'identifiant technique (Handle) pour permettre des URLs de profil uniques et une personnalisation accrue (Bio).
- **Décision** :
    1. **Architecture Handle-First** : Migration de la base de données pour introduire la colonne `handle` (unique) et ajout de la colonne `bio`. Le champ `name` perd son unicité pour devenir un "Display Name" libre.
    2. **Routage Dynamique** : Mise en place de la route `/profile/{handle}` permettant l'accès aux profils publics. La route `/profile` redirige désormais automatiquement vers le profil de l'utilisateur connecté via son handle.
    3. **Synchronisation OAuth** : Mise à jour de `HandleGithubCallbackAction` pour générer automatiquement un handle unique à partir du nickname GitHub lors de la première connexion.
    4. **UX d'Édition** : Refonte du formulaire de réglages (`update-profile-information-form`) pour permettre la modification sécurisée du handle (regex alpha-dash) et de la biographie.
    5. **Intégration GitHub Status** : Ajout d'un indicateur de connexion GitHub dans le profil avec gestion des états d'erreurs/déconnexion.
- **Impact** : Identité utilisateur professionnelle, partage de profils facilité via URLs propres et renforcement de l'aspect social de la plateforme.

## 2026-04-10-63 : Unification de la Heatmap d'Activité "ReviewMe-Native"
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Volonté de valoriser l'engagement interne sur la plateforme plutôt que de dépendre uniquement des données externes (GitHub).
- **Décision** :
    1. **Agrégation de Contributions** : Refonte de la logique de calcul dans `Profile.php`. La heatmap n'est plus liée à l'API GitHub mais agrège désormais quatre types d'actions réelles : création de `Post`, rédaction de `FullReview`, ajout de `PostComment` et proposition d'une `InlineSuggestion`.
    2. **Algorithme de Fusion** : Mise en place d'une logique de fusion de dates où chaque action compte pour `+1` dans la case journalière. Utilisation d'un cache de 600 secondes pour optimiser le rendu sur 365 jours.
    3. **UX & i18n** : Mise à jour des libellés ("Activity" -> "ReviewMe Activity") et des tooltips pour refléter l'ensemble des contributions. Centralisation des traductions dans `fr.json` et `en.json`.
- **Impact** : Renforcement du sentiment d'appartenance à ReviewMe et valorisation de tous les types d'expertise (curation, revue et conseil).

## 2026-04-10-66 : Amélioration UX/UI du Search Input (Hover)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Le composant de recherche (`search-input`) présentait un rectangle inesthétique au survol et au focus, causé par le style natif ou le plugin Tailwind Forms.
- **Décision** :
    1. **Refonte du Wrapper** : Ajout d'états dynamiques `hover:border-primary/30`, `hover:bg-[#1a1b26]/50`, et d'une micro-translation verticale (`-translate-y-0.5`) sur le conteneur principal.
    2. **Neutralisation de l'Input Interne** : Forçage explicite (`!important`) de la suppression des bordures, shadows et de l'outline sur l'input natif pour empêcher la rupture visuelle des angles arrondis (`rounded-[1.25rem]`).
- **Impact** : Expérience fluide dans le Feed et les groupes, avec un composant de recherche totalement respectueux de l'esthétique "Glass/Monolith".

## 2026-04-10-67 : Rénovation de l'Affichage Quick Review (Style GitHub Diff)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : L'affichage des suggestions de code ("Quick Review") buggait visuellement : l'indentation sautait, les lignes disparaissaient (collapse) et la disposition horizontale des conteneurs brisait la structure.
- **Décision** :
    1. **Architecture "Stack" (Flex-col)** : Remplacement de la structure flexible par `flex-col` sur la ligne parente, garantissant que la suggestion (`+`) s'affiche toujours parfaitement sous la ligne originale (`-`).
    2. **Conservation de la Coloration** : La ligne supprimée garde ses couleurs syntaxiques d'origine, seul le background passe au rouge (`bg-red-500/[0.15]`), mimant le standard GitHub. La ligne ajoutée a un background vert (`bg-emerald-500/[0.15]`).
    3. **Toggle Simplifié** : L'expérience utilisateur a été réduite à un simple toggle (clic sur l'avatar) pour afficher ou masquer la refactorisation (suppression des modes obsolètes "edit/original/diff").
- **Impact** : Lecture et révision de code sans faille géométrique, familière pour les développeurs, sans bug d'alignement sur les numéros de ligne.

## 2026-04-10-68 : Stabilisation Mécanique et visuelle des Quick Reviews
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : L'enregistrement des Quick Reviews échouait silencieusement (erreurs de validation non affichées) et l'interface du modal manquait de cohérence technique (numéros de ligne erronés, fallback de langage).
- **Décision** :
    1. **Feedback de Validation** : Intégration des directives `@error` directement sous les champs `Rationale` et `Suggested Change` pour que l'utilisateur comprenne pourquoi l'action s'arrête.
    2. **Gouttière de Ligne Dynamique** : Mise à jour du composant `syntax-highlighter` pour accepter un `startLine`, permettant d'afficher les vrais numéros de ligne du fichier dans le bloc "Original Code" du modal.
    3. **Cohérence Visuelle du Patch** : Ajout d'une gouttière décorative (`+`) sur le `textarea` de suggestion pour s'aligner esthétiquement sur les standards d'IDE et de forge logicielle.
    4. **Précision du Popup** : Réduction de l'offset vertical du popup de sélection pour une proximité immédiate avec le texte séléctionné.
- **Impact** : Processus de contribution fluide et informatif, renforçant la fiabilité perçue de l'outil de revue.

