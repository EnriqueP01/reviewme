# User Story 35 : [DevOps] Conteneuriser l'application avec Docker

## 📝 Description
**EN TANT QUE** : Développeur  
**JE VEUX** : Créer un conteneur exécutable de l'application  
**AFIN DE** : Faciliter l'exécution homogène du produit et préparer la livraison  

## ✅ Critères d'acceptation
- Un fichier `Dockerfile` permet de construire une image exécutable du projet.
- L'image produite démarre l'application avec une configuration documentée.
- Les choix du conteneur évitent les éléments inutiles ou dangereux évidents.
- La construction de l'image est vérifiée localement ou dans la pipeline.

## 🛠️ Détails techniques
- Image de base : PHP 8.3-FPM (Alpine pour la légèreté).
- Multi-stage build : Étape de build pour Composer et Node/Vite pour réduire la taille de l'image finale.
- Configuration optimisée pour Laravel 11.
