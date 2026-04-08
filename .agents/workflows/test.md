---
description: fais les test
---

# Workflow: Suite de Tests Globale & Auto-Repair

**Déclencheur** : Commande `/test`

**Étapes** :
1.  **Analyse** : Scanne tout le code source du projet.
2.  **Création/Mise à jour** : Pour chaque fichier source, assure qu'un fichier de test existe (créé ou mis à jour) à l'emplacement défini par les règles d'architecture.
3.  **Boucle de Correction** :
    * Lance les tests.
    * **Tant qu'il y a des erreurs** :
        * Analyse l'erreur.
        * Corrige le code ou le test.
        * Relance les tests.
    * S'arrête quand tout est vert (succès).
4.  **Rapport Final** : Génère un résumé indiquant :
    * Les tests créés/modifiés.
    * Les bugs corrigés durant la boucle.
    * L'état final de la suite de tests.