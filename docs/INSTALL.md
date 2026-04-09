# 🚀 Guide d'Installation

Ce document détaille les étapes nécessaires pour mettre en place l'environnement de développement ReviewMe sur votre machine locale ou via Docker.

## 📋 Pré-requis Système

| Outil | Version Minimale | Rôle |
| :--- | :--- | :--- |
| **PHP** | 8.3 | Langage serveur |
| **Node.js** | 20.x | Compilation des assets (Vite) |
| **Composer** | 2.x | Gestion des dépendances PHP |
| **NPM** | 10.x | Gestion des dépendances JS |
| **SQLite / MySQL** | - | Base de données (SQLite par défaut) |
| **Docker** | Optional | Isolation de l'environnement |

---

## 🛠️ Installation Locale

### 1. Clonage du projet
```bash
git clone https://github.com/EnriqueP01/reviewme.git
cd reviewme
```

### 2. Dépendances & Environnement
```bash
# Installation PHP
composer install

# Installation JavaScript
npm install

# Configuration des variables
cp .env.example .env
php artisan key:generate
```

### 3. Base de données
```bash
# Création du fichier SQLite (si DB_CONNECTION=sqlite)
touch database/database.sqlite

# Exécution des migrations et chargement des données de test
php artisan migrate:fresh --seed
```

### 4. Lancement
Lancez l'orchestrateur de développement (recommandé) :
```bash
php composer.phar dev
```
Ceci lancera simultanément le serveur Laravel (`localhost:8000`) et le serveur Vite pour le Hot Module Replacement.

---

## 🐳 Installation via Docker

ReviewMe est entièrement conteneurisé pour simplifier le déploiement.

### 1. Lancement des conteneurs
```bash
docker-compose up -d --build
```
Les services suivants seront démarrés :
- `reviewme-app` (PHP-FPM)
- `reviewme-web` (Nginx sur le port **8080**)
- `reviewme-db` (MySQL)

### 2. Initialisation dans le conteneur
```bash
docker exec -it reviewme-app php artisan migrate --seed
docker exec -it reviewme-app php artisan key:generate
```

Accédez à l'application sur : [http://localhost:8080](http://localhost:8080)

---

## 🔑 Configuration Socialite (GitHub)
Pour que la connexion fonctionne, vous devez créer une OAuth App sur GitHub et remplir les variables suivantes dans votre `.env` :
- `GITHUB_CLIENT_ID`
- `GITHUB_CLIENT_SECRET`
- `GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback`
