# Règle d'Intégrité de Réalisation (Integrity Gate)

Cette règle garantit qu'aucune instruction utilisateur ne soit omise, quelle que soit la taille du prompt.

## 1. Déconstruction Systématique
* **Analyse Préliminaire** : Dès réception d'un prompt, l'agent doit décomposer les instructions en une liste de micro-tâches dans son bloc de pensée (`thought`).
* **Gestion des Prompt Volumineux** : Pour toute demande complexe ou contenant plus de 3 actions distinctes, l'agent doit créer un fichier de suivi : `.agents/scratch/current_task.md`.

## 2. Validation Pré-Publication
* **Révision Comparative** : Avant de clôturer la tâche, l'agent doit comparer ses réalisations avec la demande initiale.
* **Critère de Succès** : Une tâche n'est considérée comme terminée que si **100%** des points du prompt ont été adressés ou si une impossibilité technique a été dûment expliquée.

## 3. Rapport de Conformité
* Le message de clôture doit inclure un récapitulatif factuel des actions entreprises, permettant à l'utilisateur de vérifier d'un coup d'œil que tout a été traité.

## 4. Rigueur Git & Documentation
* Chaque micro-tâche doit être reflétée dans le message de commit si elle impacte le code.
* Les décisions architecturales induites par la tâche doivent être consignées dans `DECISIONS.md`.
