---
description: met a jour le readme
---

# Workflow: Gestionnaire de README

**Déclencheur** : Commande `/readme`

**Étapes** :
1.  Vérifie si `README.md` existe.
    * Si non : Le crée.
    * Si oui : Le lit pour mise à jour (en conservant les sections existantes).
2.  Analyse le projet (fichiers de config, scripts) pour identifier comment lancer, builder et tester le projet.
3.  Rédige ou met à jour les sections :
    * **Titre & Description** : Basés sur le code.
    * **Installation & Démarrage** : Commandes précises.
    * **Commandes de Gestion** : Liste des scripts disponibles (serveur, tests, etc.).
4.  Sauvegarde et affiche un résumé des ajouts.