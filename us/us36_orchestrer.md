# User Story 36 : [DevOps] Orchestrer l'environnement avec Docker Compose

## 📝 Description
**EN TANT QUE** : Développeur  
**JE VEUX** : Décrire l'exécution locale du projet et de ses services utiles avec Docker Compose  
**AFIN DE** : Simplifier l'installation et la démonstration du produit  

## ✅ Critères d'acceptation
- Un fichier `docker-compose.yml` permet de lancer l'application et les services nécessaires au MVP.
- Les variables de configuration attendues sont explicitées.
- La commande de démarrage et la commande d'arrêt sont documentées.
- Le Compose reste compatible avec un usage étudiant simple et démontrable.

## 🛠️ Détails techniques
- Services inclus : `app` (PHP-FPM), `web` (Nginx), et `mysql` (pour le futur passage en production).
- Volume persistant pour la base de données MySQL.
- Documentation ajoutée au `README.md`.
