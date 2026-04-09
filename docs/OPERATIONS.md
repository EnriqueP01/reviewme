# 🛠️ Guide d'Exploitation

Ce guide est destiné aux administrateurs et développeurs en charge du maintien et du monitoring de ReviewMe.

## 📡 Gestion des Services

| Service | Commande | Rôle |
| :--- | :--- | :--- |
| **Reverb** | `php artisan reverb:start` | Gère les WebSockets (temps réel) |
| **Queue** | `php artisan queue:listen` | Traite les tâches de fond (notifications, calculs) |
| **Vite** | `npm run dev` | Serveur de développement pour les assets |
| **Optimizer** | `php artisan optimize:clear` | Vide tous les caches (Config, Routes, Vues) |

---

## 🪵 Journaux et Monitoring

### Localisation des logs
- **Laravel Logs** : `storage/logs/laravel.log` (Contient toutes les exceptions et `Log::info()`).
- **Nginx Logs (Docker)** : Accessibles via `docker logs reviewme-web`.
- **PHP Logs (Docker)** : Accessibles via `docker logs reviewme-app`.

### Niveaux de Log
Dans le fichier `.env`, vous pouvez ajuster la verbosité :
```bash
LOG_LEVEL=debug # Utilisez 'info' ou 'error' en production
```

---

## ⚙️ Variables d'Environnement Critiques

| Variable | Description |
| :--- | :--- |
| `APP_DEBUG` | Doit être `false` en production pour éviter les fuites d'infos. |
| `APP_URL` | Utilisé pour générer les liens absolus et les callbacks OAuth. |
| `REVERB_HOST` | Host pour la connexion WebSocket (ex: `localhost` ou `reviewme.com`). |
| `DB_CONNECTION` | Switcher entre `sqlite` et `mysql` selon les besoins. |

---

## 🏗️ Maintenance de Routine

### Mise à jour des dépendances
```bash
# Vérifier les vulnérabilités
php artisan composer:audit
npm audit

# Mettre à jour et régénérer la SBOM
composer update
npm update
bash scripts/generate-sbom.sh
```

### Nettoyage du stockage
```bash
# Supprimer les anciens backups ou fichiers temporaires
php artisan view:clear
php artisan cache:clear
```

---

## 🚨 Dépannage (Troubleshooting)

### "Vite manifest not found"
**Cause** : Les assets n'ont pas été compilés.
**Solution** : Lancez `npm run build` ou `npm run dev`.

### Erreurs 500 après une mise à jour
**Cause** : Migrations manquantes ou cache obsolète.
**Solution** : `php artisan migrate` suivi de `php artisan optimize:clear`.

### Connexion GitHub échoue
**Cause** : `GITHUB_REDIRECT_URI` incorrect dans le `.env` ou domaine non autorisé sur GitHub.
**Solution** : Vérifiez que l'URL dans GitHub correspond EXACTEMENT à celle de votre application.
