# Journal de Décisions - ReviewMe

## 2026-04-08 : Implémentation Core ReviewMe

### Choix Technique : GitHub Login Obligatoire
**Décision** : Utiliser uniquement GitHub OAuth pour l'authentification.
**Raison** : Plateforme dédiée aux développeurs, simplifie la gestion des avatars et pseudos, et renforce l'aspect communautaire tech.

### Choix Technique : Stockage SQLite (Local)
**Décision** : Utilisation temporaire de SQLite pour le développement local.
**Raison** : L'extension `pdo_pgsql` n'est pas activée sur l'environnement actuel de l'utilisateur. PostgreSQL reste la cible pour la production.

### Choix Architecture : Versioning via Snippets
**Décision** : Séparer les `snippets` du modèle `Post`.
**Raison** : Permet de conserver l'historique des modifications de code (V1, V2...) sans dupliquer les métadonnées du post original.

### Choix UI : Mode Sombre Monolith
**Décision** : Forcer le mode sombre par défaut avec une palette Monolith (Surface #12131b).
**Raison** : Préférence majeure de la cible (développeurs) et esthétique premium immédiate.

## 2026-04-08 (Antigravity Ops) : Sécurisation GitHub & Clean Auth
### Choix Technique : Auto-Vérification via GitHub
**Décision** : Marquer automatiquement les emails comme vérifiés lors de l'authentification GitHub.
**Raison** : GitHub a déjà vérifié l'email, cela évite une étape redondante et fluide l'onboarding vers le Dashboard.

### Choix UI : Rationalisation de la page Login
**Décision** : Masquer le formulaire standard par défaut sous un bouton "Accès Démo" et désactiver les routes `/register`.
**Raison** : En accord avec l'exclusivité GitHub décidée, cela guide l'utilisateur vers le tunnel principal tout en gardant une porte de secours pour le debugging et les leads.

## 2026-04-08 (Antigravity Ops) : Internationalisation & Structure Layout
### Choix Technique : Système de localisation JSON (i18n)
- **Décision** : Implémenter un système de traduction multilingue (FR/EN) via des fichiers JSON (`lang/en.json`, `lang/fr.json`).
- **Raison** : Flexibilité maximale pour l'ajout de langues, centralisation des chaînes de texte et conformité avec les standards Laravel 11. Utilisation d'un middleware `SetLocale` persistant via la session.

### Choix Technique : Synchronisation Layout Livewire 3
- **Décision** : Dupliquer et structure le layout principal vers `resources/views/components/layouts/app.blade.php`.
- **Raison** : Livewire 3 recherche par défaut le layout dans ce dossier précis. Cela corrige l'erreur `MissingLayoutException` rencontrée lors du premier chargement des composants Livewire full-page.

### Optimisation Performance Windows (Dev)
- **Décision** : Bascule des drivers de session et cache vers `file` au lieu de `database`.
- **Raison** : Sur Windows avec SQLite, le driver `database` provoque des verrous fréquents (database is locked) lors des accès concurrents (Vite + PHP dev server). Le passage en mode `file` fluidifie considérablement l'expérience de développement locale.

## 2026-04-08 (Ops) : Conteneurisation & Orchestration (US35/US36)
### Choix Technique : Docker Multi-Stage Build
- **Décision** : Utiliser un `Dockerfile` multi-étapes pour séparer les étapes de build (Composer, Node/Vite) du runtime final.
- **Raison** : Permet de générer des images très légères basées sur PHP-FPM Alpine, tout en garantissant des environnements de build reproductibles et une image finale exempte de dépendances de build inutiles.

### Choix Technique : Orchestration Web/App/DB
- **Décision** : Découpler le serveur Web (Nginx), l'application (PHP-FPM) et la base de données (MySQL) via Docker Compose.
- **Raison** : C'est le standard industriel qui facilite le passage en production et permet une maintenance isolée de chaque service. L'utilisation de volumes assure la persistance des données et des logs.

### Choix Technique : Automatisation Ops Antigravity
- **Décision** : Mise en place d'une règle de gestion Docker (`docker.md`) et intégration du cycle de vie Docker dans le workflow de synchronisation (`pull.md`).
- **Raison** : Garantit que l'environnement de développement reste "sain" et à jour après chaque fusion de code, tout en automatisant les tâches répétitives comme le nettoyage d'images orphelines.
