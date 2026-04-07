# ReviewMe 🚀

> « La plateforme web de revue de code où la bienveillance est inscrite dans le code. »

## 🌟 Vision
Une plateforme de revue de code axée sur la bienveillance, avec une hiérarchie de supervision pour guider les développeurs et un système de **"Boost"** uniquement positif. Projet optimisé pour le Web avec des animations physiques **Antigravity**.

---

## 🛠️ Installation & Configuration (Équipe)

Pour une installation rapide et sans erreur de PATH, utilise les scripts fournis :

### 1. Première installation
Ouvre PowerShell en tant qu'administrateur (si possible pour winget) et lance :
```powershell
./setup.ps1
```
*Ce script installe PHP, configure le PATH, installe les dépendances et prépare la base de données.*

### 2. Lancement quotidien
Pour travailler, lance simplement :
```powershell
./dev.ps1
```
*Cela lance simultanément le serveur Laravel et le compilateur Vite.*

---

---

## 🚀 Lancement du projet

Il est nécessaire de lancer deux terminaux en parallèle :

### Terminal 1 : Backend (Laravel)
```bash
php artisan serve
```
Le site sera accessible sur : [http://localhost:8000](http://localhost:8000)

### Terminal 2 : Frontend (Vite / Tailwind)
```bash
npm run dev
```

---

## 🏗️ Architecture
Le projet suit une architecture **Clean** avec une couche de **Services** pour la logique métier :
*   **App\Models** : `Post`, `Review`, `Group`, `Boost`.
*   **App\Services** : `PostService`, `ReviewService`.
*   **Frontend** : Laravel Breeze (Blade) + Tailwind CSS.

---


---

## 📋 Commandes Rapides (Copier-Coller)

### 🚀 Lancement Direct (après installation)
```powershell
# Terminal 1 : Backend
php artisan serve

# Terminal 2 : Frontend
npm run dev
```

### 🛠️ Maintenance & Reset Base de données
```powershell
# Tout réinitialiser (Database + Seeders)
php artisan migrate:fresh --seed
```

### 💾 Workflow Git (Développement)
```powershell
# Ajouter, Commiter et Pusher
git add . ; git commit -m "feat: description" ; git push origin main
```

---

## 💡 Astuces Développement
*   **Logs Laravel** : `tail -f storage/logs/laravel.log`
*   **Effacer le cache** : `php artisan optimize:clear`

---

---

## 📜 Charte de Développement (Antigravity)

Ce projet utilise des règles d'automatisation et de contrôle qualité via l'agent **Antigravity**. Voici les directives à respecter :

### 🛡️ Principes Fondamentaux
*   **Langue :** Français obligatoire (explications, rapports, commits).
*   **Méthode KISS :** Simplicité et lisibilité avant tout.
*   **Standards :** Respect strict des fichiers de configuration locaux (`.editorconfig`, `.styleci.yml`, `phpstan.neon`, etc.).
*   **Traçabilité :** Documentation systématique des choix techniques dans [DECISIONS.md](./DECISIONS.md).
*   **Robustesse & Ops :** 
    *   Mise à jour systématique de `.env.example` si le `.env` change.
    *   Toute modification de schéma s'effectue via une **migration Laravel**.
    *   Gestion d'erreurs proactive et utilisation des Logs Laravel.
*   **Tests :** Rangement exclusif dans `tests/`.

### 📥 Workflow de Synchronisation (`/pull`)
1.  **Diff Review :** Analyse de l'impact des changements sur l'architecture globale.
2.  **Synchro Env :** Vérification automatique et mise à jour des dépendances (`npm install`, `composer install`).
3.  **Conflits & Validation :** Résolution proactive des conflits et vérification que les tests passent au vert après merge.

### 📤 Workflow de Publication (`/push`)
1.  **Pre-Push Scan :** Chasse aux bugs, suppression des `console.log` / `var_dump` et vérification de la logique.
2.  **Doc Architecture :** Vérification et mise à jour du fichier `DECISIONS.md`.
3.  **Commit & Push :** Signature des commits via la convention `Conventional Commits` et génération des commandes terminal.

### 🚀 Analyse & Optimisation (`/opti`)
Analyse **100% technique et factuelle** (sans analogies) axée sur la performance algorithmique, la sécurité et la maintenabilité des nouvelles fonctionnalités.

---

## 📄 Documentation
Les concepts détaillés et les User Journeys sont disponibles dans le dossier [us/](./us/).

