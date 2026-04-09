# [Architecture] US14 - Définir les rôles, permissions et la stratégie de gestion des erreurs

## 1. Rôles et Permissions (RBAC)

ReviewMe utilise un système d'autorisations granulaires (Laravel Policies) pour sécuriser l'accès aux posts et aux actions de collaboration.

### 1.1 Rôles Applicatifs

*   **Guest (Visiteur)**
    *   **Droits** : Lecture des **Posts** publics exclusivement.
    *   **Limites** : Aucune interaction possible (ni vote, ni commentaire).

*   **User (Membre)**
    *   **Droits de Création** : Publier des **Posts** (Public/Groupe), créer des Groupes (Owner).
    *   **Droits de Collaboration** : Émettre des **Full Reviews**, des **Inline Suggestions** et des **Commentaires**.
    *   **Droits de Gestion** : Éditer/Supprimer ses propres contenus (Posts, Commentaires).
    *   **Participation Sociale** : Réagir (React) aux contenus tiers.

*   **Group Moderator / Owner**
    *   **Droits** : Gestion des membres (invitation/exclusion), modification des métadonnées du groupe.
    *   **Visibilité** : Accès à tous les posts publiés dans le groupe (visibilité `group`).

*   **Admin (Modérateur Global)**
    *   **Droits d'Élite** : Suppression de contenu non conforme, modération globale.
    *   **Expertise (Pinning)** : Capacité d'épingler (`is_pinned`) les commentaires ou reviews les plus pertinentes pour les faire remonter en haut du flux de discussion.

### 1.2 Matrice de Sécurité (Source of Truth)

| Action | Owner | Group Member | Other User | Guest |
| :--- | :---: | :---: | :---: | :---: |
| Voir Post Public | ✅ | ✅ | ✅ | ✅ |
| Voir Post Groupe | ✅ | ✅ | ❌ | ❌ |
| Éditer Post / Snippet | ✅ | ❌ | ❌ | ❌ |
| Soumettre Full Review | ❌ | ✅ | ✅ | ❌ |
| Suggérer Inline | ❌ | ✅ | ✅ | ❌ |
| Épingler Commentaire | ❌ | ❌ | ❌ (Admin Only) | ❌ |

---

## 2. Stratégie de Gestion des Erreurs

### 2.1 Hiérarchie des Erreurs

1.  **Validation (422)** : Gérée par les `FormRequests`. Feedback visuel immédiat via Toast HUD (Ambre).
2.  **Autorisation (403)** : Interceptée par les `Policies`. Redirection vers page 403 stylisée ("Accréditation insuffisante").
3.  **Logique (409/400)** : Exceptions métier (ex: Doublon de contenu MD5). Feedback par Toast (Rouge).
4.  **Système (500)** : Fail-safe global. Rollback de transaction (`DB::transaction`) et log critique. Masquage total de la stack trace en production.

### 2.2 Workflow d'Intégrité
Toute action critique (création de post, publication de revue) est encapsulée dans une **Action** (`app/Actions`). En cas d'erreur lors d'une étape (ex: échec d'insertion d'un snippet), la base de données est restaurée à son état initial pour éviter les "posts fantômes".

---

## 3. Implémentation Técnica
- **Policies** : `PostPolicy`, `GroupPolicy`, `CommentPolicy`, `ReviewPolicy`.
- **Feedback UI** : Composant `global-loader` et `toast-hud` pour notifier l'utilisateur du succès ou de l'échec d'une opération asynchrone sans rechargement de page.
