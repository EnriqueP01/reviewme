# [API] US15 - Documenter le contrat des routes API

## Description de la story
EN TANT QUE Développeur  
JE VEUX Définir et documenter les routes API nécessaires au MVP  
AFIN DE éviter les interfaces implicites et faciliter le travail entre front, back et IA

---

## 1. Introduction
ReviewMe utilise principalement Livewire pour son interface réactive, mais le contrat suivant définit les points d'entrée (théoriques ou réels via les `Actions`) pour l'interopérabilité.

## 2. Contrat des Routes (Endpoints)

| Méthode | Route | Description | Entrée (Payload) | Sortie (200 OK) |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | `/api/posts` | Liste des posts paginée | `search`, `lens`, `language` | `data: [ {post_obj} ]` |
| **POST** | `/api/posts` | Création d'un post | `title`, `short_description`, `lens`, `files[]` | `{ id: 1, status: 'deployed' }` |
| **GET** | `/api/posts/{id}` | Détail d'un post et snippets | - | `{ title: '...', snippets: [...] }` |
| **POST** | `/api/posts/{id}/review` | Publication d'une Full Review | `description`, `modified_files[]` | `{ review_id: 101 }` |

---

## 3. Schéma de Validation de Création (POST /api/posts)

Ce schéma reflète strictement les règles appliquées dans `PublishWorkflow.php`.

```json
{
  "title": "required|string|min:5|max:255",
  "short_description": "nullable|string|min:10|max:255",
  "review_goals": "nullable|string|min:10",
  "improvement_goals": "nullable|string|min:10",
  "lens": "required|string", 
  "visibility": "required|in:public,group,private",
  "group_id": "required_if:visibility,group|exists:groups,id",
  "files": "required|array|min:1",
  "files.*.name": "required|string",
  "files.*.content": "required|string|max:524288",
  "files.*.language": "required|string"
}
```

---

## 4. Gestion des Réponses & Statuts

- **201 (Created)** : Post ou collaboration publié avec succès.
- **403 (Forbidden)** : Tentative de modification illégitime ou accès à un post d'un groupe privé hors-membre.
- **422 (Unprocessable Entity)** : Échec de validation technique ou détection de doublon (MD5 Conflict).
- **500 (Internal Error)** : Erreur système (DB, GitHub OAuth).

---

## 5. Intégrité des Données
Le contrat impose l'utilisation de **Lenses** sémantiques. Le champ `lens` peut contenir plusieurs valeurs séparées par des virgules (ex: `"logic,opti"`), correspondant à la charte graphique HUD de l'application.
