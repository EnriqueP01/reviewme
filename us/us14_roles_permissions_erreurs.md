# [Architecture] US14 - Définir les rôles, permissions et la stratégie de gestion des erreurs

## 1. Rôles et Permissions (RBAC)

Dans le cadre du projet ReviewMe, nous adoptons une approche basée sur des rôles stricts pour garantir la sécurité et la clarté des actions au sein de la plateforme. 

### 1.1 Rôles utiles au produit

*   **Guest (Visiteur non authentifié)**
    *   **Actions Autorisées** : Voir les **Posts** publics, s'authentifier via GitHub, lire les pages d'information (Landing Page).
    *   **Actions Interdites** : Créer des posts, voter, commenter, rejoindre des **Groupes**, accéder aux posts privés, accéder à l'API de modification.

*   **User (Membre authentifié)**
    *   **Actions Autorisées** : Créer des posts publics/privés, voter et donner des retours (reviews), rejoindre des **Groupes** collaboratifs, voir les posts privés des groupes dont il est membre, éditer/supprimer ses propres posts.
    *   **Actions Interdites** : Supprimer le post d'un autre utilisateur, forcer l'entrée dans un groupe privé, supprimer un groupe qu'il n'a pas créé.

*   **Group Owner (Propriétaire de groupe)**
    *   **Actions Autorisées** : Hérite des droits du *User*. Peut modifier les paramètres du groupe, expulser des membres, supprimer le groupe.
    *   **Actions Interdites** : Interférer avec la modération globale de la plateforme, éditer les posts privés publiés dans son groupe par d'autres utilisateurs.

*   **Admin (Administrateur système)**
    *   **Actions Autorisées** : Modérer n'importe quel post, supprimer n'importe quel commentaire/review, dissoudre des groupes qui ne respectent pas les règles, gérer les rôles des utilisateurs.
    *   **Actions Interdites** : Modifier la configuration de la base de données en production sans passer par les pipelines de déploiement (séparation dev/ops).

### 1.2 Cas explicite de refus d'accès (Access Denied)
*   *Scénario* : Un **User A** tente d'accéder (via l'URL ou l'API) à un **Post** privé appartenant à un **Groupe X**, alors qu'il n'est pas membre de ce groupe.
*   *Comportement* : 
    *   **Interface (Frontend)** : Redirection vers la page 403 (Unauthorized) avec un message élégant et poli : "Vous n'avez pas l'accréditation nécessaire pour observer cette architecture."
    *   **API / Backend** : Laravel Gate/Policy intercepte la requête et retourne un `abort(403, 'Unauthorized Access')`. Une entrée est ajoutée dans les logs de sécurité.

---

## 2. Stratégie de Gestion des Erreurs

Afin de garantir que les comportements restent toujours cohérents côté client ou via des clients API externes, nous classifions les erreurs en trois niveaux distincts.

### 2.1 Distinction des types d'erreurs

#### A. Erreurs de Validation (Formulaires & API)
*   **Contexte** : Données utilisateur manquantes, erronées, ou mal typées (ex: Fichier trop grand pour le Publish Workflow, URL invalide, champs vides).
*   **Couverture** : Traitement via `Illuminate\Foundation\Http\FormRequest`.
*   **Comportement Interface** : Avertissement UI direct (messages rouges sous les champs ou notifications Toast), le statut ne change pas, les données déjà saisies sont conservées.
*   **Comportement API** : Retourne un code `422 Unprocessable Entity` avec le tableau JSON standard `{ "message": "...", "errors": { ... } }`.

#### B. Erreurs Métier (Business Logic)
*   **Contexte** : L'action est valide syntaxiquement mais viole une règle métier du produit (ex: Tenter de liker son propre post de manière forcée si désactivé au niveau core, rejoindre un groupe déjà complet).
*   **Couverture** : Exceptions spécifiques lancées depuis les classes *Actions* (ex: `ActionDeniedException`).
*   **Comportement Interface** : Toast d'erreur informatif expliquant *pourquoi* la règle empêche l'action : "Ce groupe a atteint sa capacité maximale d'analystes." Redirection douce.
*   **Comportement API** : Retourne un code `409 Conflict` ou `403 Forbidden` avec un contexte fonctionnel dans le payload JSON.

#### C. Erreurs Techniques (Système & Base de données)
*   **Contexte** : Base de données déconnectée, clé API tierce invalide (ex: GitHub down), timeout système.
*   **Couverture** : Bloquées via le gestionnaire global d'exceptions Laravel (`app/Exceptions/Handler.php`).
*   **Comportement Interface** : Rendu d'une page 500 personnalisée et rassurante aux couleurs du système ("Notre réseau neuronal est temporairement surchargé. Réessayez d'ici peu."). Ne **jamais** afficher de Stack Trace.
*   **Comportement API** : Code `500 Internal Server Error`, message générique `{ "error": "Internal Server Error" }`. L'erreur détaillée est reportée côté serveur (Sentry / Logs Laravel).

### 2.2 Cas explicite d'erreur critique (Fatal Error)
*   *Scénario* : Perte de connexion soudaine à la base de données PostgreSQL pendant une mise à jour de réputation d'un utilisateur.
*   *Comportement* :
    *   **Backend** : La transaction (`DB::transaction`) échoue, effectue un `rollback` automatique empêchant les incohérences de données (ex: reputation mise à jour mais pas le vote). L'erreur `PDOException` est capturée et "swallow" par une erreur système générique, puis loggée avec le grade `CRITICAL`.
    *   **Interface (Frontend)** : Le système passe en "fail-safe". L'utilisateur reçoit une notification standard "Nous rencontrons un délai de synchronisation. Veuillez rafraîchir."
    *   **API** : Interruption de processus, réponse brute 500 structurée, aucun détail exposé (ex: pas d'identifiant DB_USER exposé dans la console réseau).

---

## 3. Implémentation Pratique (Checklist des composants)

Ces directives de sécurité sont implémentées sur ces couches techniques :
- **Routing** : Groupes protégés par middlewares `auth` et `verified`.
- **Controllers/Actions** : Validation avec appels à `$this->authorize('view', $post);`.
- **Policies** : Fichiers `PostPolicy.php` et `GroupPolicy.php` concentrant la logique des actions.
- **Vues Blade (UI)** : Utilisation dynamique de `@can` / `@cannot` pour afficher ou masquer les boutons (Éditer, Supprimer) et prévenir la frustration des utilisateurs.
