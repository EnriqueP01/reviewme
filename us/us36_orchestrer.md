# User Story 36 : [DevOps] Orchestrer l'environnement avec Docker Compose

## 📝 Description
**EN TANT QUE** : Développeur  
**JE VEUX** : Décrire l'exécution locale du projet et de ses services utiles avec Docker Compose  
**AFIN DE** : Simplifier l'installation et la démonstration du produit  

## ✅ Critères d'acceptation
- [x] Un fichier `docker-compose.yml` permet de lancer l'application et les services nécessaires au MVP.
- [x] Les variables de configuration attendues sont explicitées (cf. `.env.example`).
- [x] La commande de démarrage et la commande d'arrêt sont documentées.
- [x] Le Compose reste compatible avec un usage étudiant simple et démontrable.

## 📊 Statut
- **Statut** : Terminé
- **Date** : 2026-04-09
- **Auteur** : Antigravity

## 🛠️ Détails techniques
- **Services inclus** : `app` (PHP-FPM), `web` (Nginx), et `mysql` (prêt pour MVP).
- **Driver Actif** : L'image `app` est configurée par défaut pour utiliser SQLite pour une portabilité maximale sans dépendance externe lourde immédiate.
- **Volumes** : Persistance du `storage` et de la `database` pour conserver l'état entre les redémarrages.
- **Documentation** : Voir section Docker dans le `README.md`.
