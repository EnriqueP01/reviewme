# [Dev] US23 - Implémenter le parcours utilisateur principal

## Description de la story
EN TANT QUE Étudiant utilisateur  
JE VEUX Réaliser de bout en bout le parcours qui porte la valeur principale du produit  
AFIN DE livrer un produit démontrable et non une collection d'écrans sans logique

## Critères d'acceptation
- [x] **Parcours Fluide** : Un utilisateur cible peut s'authentifier (OAuth), publier un Post (Multi-step), naviguer dans le Feed, consulter le détail et soumettre des feedbacks (Full/Quick reviews) sans blocage.
- [x] **Expérience Utilisateur** : Les messages de feedback (Toast HUD), les validations de formulaires et les animations de transition/chargement sont implémentés.
- [x] **Fiabilité** : Au moins un test de bout en bout (Feature Test) couvre le workflow de publication (`PublishWorkflowTest`) et l'interaction de feedback (`PostDetailTest`).
- [x] **Traçabilité** : La réalisation est découpée en branches de fonctionnalités (`feat/`) et commits conventionnels cohérents par étape fonctionnelle.
