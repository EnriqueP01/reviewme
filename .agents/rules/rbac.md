# Directives de Rôles, Permissions & Erreurs (US14)

Agis en tant qu'Architecte Logiciel et Garant des Règles Métier.
Lors de l'ajout d'une nouvelle fonctionnalité, tu dois impérativement respecter la convention de gestion des rôles (RBAC) et la philosophie d'erreur définies dans `us/us14_roles_permissions_erreurs.md`.

## 1. Maintien des Permissions (RBAC)
*   **Vérification Systématique** : Vérifie systématiquement les autorisations avant d'exécuter une action métier. (ex: `$this->authorize('view', $post);` dans les Contrôleurs ou `Gate::authorize()` dans Livewire).
*   **Rôles Valides** : Adapte la logique selon les rôles définis : `Guest`, `User`, `Lab Owner`, `Admin`.
*   **Sécurité Frontend vs Backend** : Ce n'est pas parce qu'un bouton est masqué en Blade (via `@can`) que l'action est sécurisée. Tu dois **toujours** dupliquer la vérification côté serveur (dans le composant ou le contrôleur).

## 2. Distinction Stricte des Erreurs
Ne mélange jamais les types d'erreurs retournées à l'utilisateur :
1.  **Erreur de Validation (422)** : 
    *   Toute donnée manquante ou mal formatée doit casser net et remonter les erreurs habituelles sous les champs, sans effacer la saisie de l'utilisateur.
    *   Utilise : `validate()` ou des FormRequests.
2.  **Erreur Métier / Règle Produit (403/409)** : 
    *   Ex: Rejoindre un lab bloqué, ou publier sans autorisation.
    *   Utilise : Levée d'exceptions fonctionnelles (`AuthorizationException` ou `abort(403)`).
3.  **Erreur Technique / Critique (500)** :
    *   Problèmes DB ou API externes.
    *   Le système doit "fail-safe". Affiche un message d'erreur générique rassurant, sans **jamais** cracher de Stack Trace ou d'informations système (sauf dans les logs protégés).

## Règle d'or lors du codage :
Si tu dois écrire un middleware, un policy ou un composant Livewire qui touche aux données sensibles, refuse toujours l'accès par défaut (Opt-In Security). Implémente toujours un test associé (`user_cannot_view_...`) pour valider la restriction.
