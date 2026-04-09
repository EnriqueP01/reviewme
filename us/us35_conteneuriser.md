# User Story 35 : [DevOps] Conteneuriser l'application avec Docker

## 📝 Description
**EN TANT QUE** : Développeur  
**JE VEUX** : Créer un conteneur exécutable de l'application  
**AFIN DE** : Faciliter l'exécution homogène du produit et préparer la livraison  

## ✅ Critères d'acceptation
- [x] Un fichier `Dockerfile` permet de construire une image exécutable du projet.
- [x] L'image produite démarre l'application avec une configuration documentée.
- [x] Les choix du conteneur évitent les éléments inutiles ou dangereux évidents.
- [x] La construction de l'image est vérifiée localement ou dans la pipeline.

## 📊 Statut
- **Statut** : Terminé
- **Date** : 2026-04-09
- **Auteur** : Antigravity

## 🛠️ Détails techniques
- **Image de base** : `php:8.3-fpm-alpine`.
- **Multi-stage build** : 
  - `composer-stage` : Installation des dépendances avec optimisation de l'autoloader.
  - `node-stage` : Build des assets avec Vite (Node 22-alpine).
- **Extensions PHP** : `pdo`, `pdo_sqlite`, `bcmath`, `gd`, `xml`, `intl`.
- **Système** : Alpine Linux avec `icu-dev`, `sqlite-dev`.
- **Serveur** : PHP-FPM exposé sur le port 9000.
