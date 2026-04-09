# [Architecture] US13 - Modèle de domaine et Modélisation des données

## Description de la story
EN TANT QUE Architecte logiciel  
JE VEUX Décrire les entités métier, leurs relations et la structure des données  
AFIN DE poser un langage commun entre produit, données et développement

---

## 1. Modèle Conceptuel de Données (UML Mermaid)

```mermaid
classDiagram
    class User {
        +int id
        +string github_id
        +string name
        +string avatar
        +int reputation_score
        +text bio
    }

    class Group {
        +int id
        +string name
        +string slug
        +int owner_id
    }

    class Post {
        +int id
        +int user_id
        +int group_id
        +string title
        +string short_description
        +text description
        +text review_goals
        +text improvement_goals
        +string lens
        +enum visibility
    }

    class Snippet {
        +int id
        +int post_id
        +int version_number
        +string name
        +string filename
        +text code_content
        +string language
        +int sort_order
    }

    class PostComment {
        +int id
        +int user_id
        +int post_id
        +int parent_id
        +int full_review_id
        +text content
        +bool is_pinned
    }

    class FullReview {
        +int id
        +int user_id
        +int post_id
        +text description
    }

    class FullReviewSnippet {
        +int id
        +int full_review_id
        +int snippet_id
        +text modified_content
        +text description
    }

    class InlineSuggestion {
        +int id
        +int user_id
        +int snippet_id
        +int line_number
        +int end_line_number
        +text original_content
        +text suggested_content
        +text description
    }

    class Reaction {
        <<Polymorphic>>
        +int id
        +int user_id
        +int reactable_id
        +string reactable_type
        +enum type
    }

    User "1" --o "*" Group : owns
    User "1" --o "*" Post : creates
    Group "1" --o "*" Post : contains
    Post "1" --* "1..*" Snippet : has
    Post "1" --o "*" PostComment : receives
    Post "1" --o "*" FullReview : receives
    Snippet "1" --o "*" InlineSuggestion : receives
    FullReview "1" --* "*" FullReviewSnippet : contains
    Snippet "1" --o "*" FullReviewSnippet : references
    PostComment "1" --o "*" PostComment : replies
    User "1" --o "*" Reaction : feels
    Reaction "*" --o "1" Post : targets
    Reaction "*" --o "1" PostComment : targets
    Reaction "*" --o "1" FullReview : targets
```

---

## 2. Dictionnaire des Données (Champs Réels)

### 2.1. Entités Centrales

| Entité | Champs Clés Appliqués | Description |
| :--- | :--- | :--- |
| **User** | `github_id`, `reputation_score` | Authentification Unique (GitHub). |
| **Group** | `name`, `slug`, `owner_id` | Règle de visibilité privée/semi-privée. |
| **Post** | `review_goals`, `improvement_goals`, `lens`, `visibility` | Unité de curation. Les Lenses (`lens`) stockent les focus choisis (ex: `logic,opti`). |
| **Snippet** | `version_number`, `filename`, `sort_order` | Fragment de code. L'unicité est gérée par le couple (post_id, filename, version_number). |

### 2.2. Entités de Feedback & Collaboration

| Entité | Champs Clés Appliqués | Description |
| :--- | :--- | :--- |
| **FullReview** | `post_id`, `description` | Revue globale (PR-Style) avec propositions de modifications majeures. |
| **InlineSuggestion** | `line_number`, `end_line_number`, `suggested_content` | Micro-modification ciblée sur une ou plusieurs lignes de code. |
| **PostComment** | `parent_id`, `full_review_id`, `is_pinned` | Discussion threadée. Peut être rattachée à une `FullReview`. |
| **Reaction** | `reactable_type`, `type` | Support des types `up`, `down`, `like`. |

---

## 3. Règles Métier & Contraintes de Données

1. **Intégrité de Versioning** : Chaque `Snippet` appartient à une `version_number`. La suppression d'un `Post` entraîne la suppression en cascade de toutes ses versions.
2. **Unicité Technologique** : Interdiction d'avoir deux fichiers avec le même `filename` dans la même version d'un `Post` (sécurisé par le MD5 Guard du workflow).
3. **Hiérarchie RBAC** :
    - `is_pinned` : Seuls les administrateurs ou experts certifiés peuvent épingler des commentaires pour hiérarchiser les retours.
    - `FullReview` : Seuls l'auteur original peut itérer (nouvelle version) mais tout le monde peut proposer une `FullReview`.

---

## 4. Nomenclature Professionnelle (Source of Truth)

- **Post** : (ex-Vibe) L'entité maîtresse du flux.
- **Group** : (ex-Lab) Unité de collaboration restreinte.
- **Snippet** : Fragment de code technique.
- **Lens** : Axe d'analyse chromatique (Logic, Beauty, Opti).
