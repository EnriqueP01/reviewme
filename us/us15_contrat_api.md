# [API] US15 - Documenter le contrat des routes API

## Description de la story
EN TANT QUE Développeur  
JE VEUX Définir et documenter les routes API nécessaires au MVP  
AFIN DE éviter les interfaces implicites et faciliter le travail entre front, back et IA

## Critères d'acceptation
- [x] chaque route documentée précise méthode, entrée, sortie, codes de réponse et cas d'erreur
- [x] les règles de validation des paramètres et du corps de requête sont explicites
- [x] les routes sont cohérentes avec le modèle de données et le parcours principal
- [x] la documentation API peut être utilisée par un tiers sans lire le code

---

### 1. Introduction
Bien que ReviewMe utilise principalement Livewire pour son interface réactive, le contrat suivant définit les points d'entrée API REST indispensables pour l'interopérabilité future ou les intégrations tierces.

### 2. Contrat des Routes (Endpoints)

| Méthode | Route | Description | Entrée (JSON) | Sortie (200 OK) | Codes Erreur |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **GET** | `/api/posts` | Liste des posts paginée | `search`, `lens`, `language` | `data: [ {post_obj} ]` | 500 |
| **POST** | `/api/posts` | Création d'un post | `title`, `description`, `lenses[]`, `files[]` | `{ id: 1, status: 'deployed' }` | 401, 422 |
| **GET** | `/api/posts/{id}` | Détail d'un post et snippets | - | `{ title: '...', snippets: [...] }` | 404, 403 |
| **PATCH** | `/api/posts/{id}` | Itération de code (version) | `files[]`, `change_log` | `{ version: 2 }` | 403, 422 |
| **GET** | `/api/groups` | Liste des groupes de l'user | - | `[ {group_obj} ]` | 401 |

### 3. Schéma de Validation (POST /api/posts)

```json
{
  "title": "required|string|max:255",
  "description": "nullable|string",
  "lenses": "required|array|min:1",
  "files": "required|array|min:1",
  "files.*.filename": "required|string",
  "files.*.content": "required|string",
  "files.*.language": "required|string",
  "group_id": "nullable|exists:groups,id"
}
```

### 4. Gestion des Réponses

- **200 / 201** : Succès. L'objet créé ou demandé est retourné dans une clé `data`.
- **401 (Unauthorized)** : Jeton API manquant ou invalide.
- **403 (Forbidden)** : Tentative d'accès à un post privé ou modification d'un post dont l'utilisateur n'est pas l'auteur.
- **422 (Unprocessable Entity)** : Échec de validation (ex: fichier trop volumineux, titre manquant). Retourne un objet `errors`.
- **500 (Server Error)** : Erreur technique interne (ex: DB Down).

### 5. Utilisation par des Agents IA
Cette documentation sert de référence pour tout Agent IA souhaitant interagir avec le backend ReviewMe. Les noms de champs (`title`, `lenses`, `group_id`) doivent être respectés strictement pour éviter les rejets de validation.
