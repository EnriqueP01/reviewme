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

## 📄 Documentation
Les concepts détaillés et les User Journeys sont disponibles dans le dossier [us/](./us/).

