# ReviewMe 🚀

> « La plateforme de curation collaborative où le code devient un artefact vivant. »

## 🌟 Vision
ReviewMe est un écosystème de revue de code d'élite axé sur la collaboration sécurisée et la bienveillance. Grâce au système de **Labs**, les équipes peuvent isoler leurs audits dans des unités privées, tout en bénéficiant d'une interface HUD futuriste et d'un feedback haptique/audio immersif.

---

## 📋 Identifiants de Test (Lab Mode)

Explorez l'application avec des profils pré-configurés pour tester toutes les fonctionnalités :

### 1. Master Curator (Profil Principal)
*   **Rôle** : Administrateur de Labs, Multi-publieur.
*   **Email** : `master@reviewme.com`
*   **Mot de passe** : `password`
*   **ID** : `515`

### 2. Senior Reviewer (Contrôleur)
*   **Rôle** : Expert en audit, donneur de feedback complexe.
*   **Email** : `senior@reviewme.com`
*   **Mot de passe** : `password`

### 3. Junior Coder (Aide)
*   **Rôle** : Auteur de code optimisable et questions sur le forum.
*   **Email** : `junior@reviewme.com`
*   **Mot de passe** : `password`

---

## 🛠️ Installation & Configuration

### 1. Pré-requis
*   **PHP 8.3** (Extensions : SQLite, BCMath, GD).
*   **Node.js & NPM / Bun**.
*   **Composer**.

### 2. Démarrage Rapide
Ouvrez votre terminal et lancez l'orchestrateur de développement :
```powershell
php composer.phar dev
```
*Note : Cette commande lance simultanément le serveur Laravel artisan et le compilateur Vite.*

### 🐳 Infrastructure Docker
Le projet est prêt pour une isolation complète via Docker Compose :
```bash
docker-compose up -d --build
```
Accès local : [http://localhost:8080](http://localhost:8080)

---

## 🏗️ Architecture (Action-Domain)
ReviewMe utilise une architecture moderne **Orientée Actions** pour une testabilité maximale et un découplage total du frontend :
*   **App\Actions** : Logique métier atomique (`CreatePostAction`, `SearchPostsAction`, `UpdateUserReputationAction`).
*   **App\Livewire** : Composants SPA interactifs (`PublishWorkflow`, `GroupManager`).
*   **App\Models** : `Post` (Artefact), `Group` (Lab), `Snippet`, `Review`, `Reaction` (Karma).
*   **App\Policies** : Sécurité granulaire pour l'isolation des Labs.

---

## 🧬 Système de Labs & Curation
1.  **Introspection** : Définition des buts de revue et du contexte technique.
2.  **Artefacts** : Upload multi-fichiers, détection automatique de langage et annotations.
3.  **Distribution** : Publication publique ou restreinte à un **Lab** spécifique (Groupe privé).
4.  **Audit** : Revue ligne à ligne avec focus spécialisés (Clarity, Security, Performance).

---

## 📋 Commandes de l'Agent Antigravity

La plateforme est maintenue par des protocoles robotiques stricts :

| Commande | Action | Usage |
| :--- | :--- | :--- |
| `/sync` | **Synchronisation** | Pull, install, migrations et nettoyage. |
| `/lint` | **Qualité Code** | Auto-correction Laravel Pint & ESLint. |
| `/test` | **Validation** | Exécution des tests et réparation auto. |
| `/audit` | **Sécurité/SEO** | Diagnostic complet perf et sécurité. |
| `/upgrade`| **Amélioration** | Analyse de dette technique et propositions. |
| `/commit`| **Push** | Commit conventionnel après vérification qualité. |

---

## 🛠️ Maintenance & Diagnostics
```powershell
# Exécuter les tests de sécurité des Labs
php artisan test tests/Feature/LabSecurityTest.php

# Réinitialiser l'écosystème complet (Migrations + Global Seeders)
php artisan migrate:fresh --seed

# Initialiser uniquement les données de test avancées (Master, Labs, Conversations)
php artisan db:seed --class=MasterTestSeeder

# Nettoyer les résidus de déploiement
php artisan optimize:clear
```

---

## 📜 Charte de Développement
*   **Workflows Atomiques** : Une branche par fonctionnalité. **Interdiction de push sur `main`**.
*   **Traçabilité** : Chaque décision technique est consignée dans [DECISIONS.md](./DECISIONS.md).
*   **Langue** : Français obligatoire pour les rapports et documentations.
*   **KISS** : Simplicité, performance et robustesse.

---

## 📄 Documentation Supplémentaire
*   [User Journeys](./us/us03_user_journeys.md)
*   [Persona Profiling](./us/02_persona.md)
*   [Architecture ADR](./DECISIONS.md)
