# 🏛️ Architecture & Conception

Ce document décrit les fondations techniques de ReviewMe et les choix de conception qui garantissent sa scalabilité et sa maintenabilité.

## 🚀 Stack Technologique

| Couche | Technologie | Justification |
| :--- | :--- | :--- |
| **Backend** | Laravel 11 (PHP 8.3) | Framework robuste, écosystème mature, productivité. |
| **Frontend** | Livewire 3 + AlpineJS | Approche "Fullstack" réactive sans la complexité d'un SPA lourd (Vue/React). |
| **Styling** | Tailwind CSS v3 | Design system utilitaire pour une UI rapide et cohérente. |
| **Realtime** | Laravel Reverb | WebSockets natifs pour le feedback instantané. |
| **Database** | SQLite (Dev) / MySQL (Prod) | Simplicité en développement, robustesse en production. |

---

## 🏗️ Structure du Projet (Action-Domain)

ReviewMe suit un pattern **Orienté Actions** (Action-Domain-Responder) :

- **`App\Actions`** : Contient l'intelligence métier. Chaque fichier est une action unique (ex: `CreatePostAction`).
- **`App\Models`** : Représente les entités et leurs relations.
- **`App\Livewire`** : Gère l'état de l'UI et délègue la logique aux Actions.
- **`App\Policies`** : Gère les autorisations d'accès aux Groupes (groupes privés).

---

## 📊 Modèle de Domaine (US13)

L'architecture des données repose sur la notion de **Post** (publication) qui possède des **Snippets** (fragments de code).

```mermaid
classDiagram
    class User {
        +string name
        +int reputation_score
    }
    class Post {
        +string title
        +enum visibility
    }
    class Snippet {
        +text code_content
        +string language
    }
    class Review {
        +int line_number
        +text content
    }
    class Group {
        +string name
        +boolean is_private
    }

    User "1" -- "0..*" Post : publishes
    Post "1" -- "1..*" Snippet : contains
    Snippet "1" -- "0..*" Review : receives
    Group "1" -- "0..*" Post : isolates
    User "0..*" -- "0..*" Group : belongs to
```
*Pour plus de détails, voir l'US13 : [Modèle de Domaine](../us/us13_modele_domaine.md).*

---

## 🔄 Parcours Principal (US12)

Le diagramme ci-dessous illustre le cycle de vie d'une publication (Post) et les interactions avec les composants.

```mermaid
sequenceDiagram
    participant User
    participant PublishWorkflow (Livewire)
    participant CreatePostAction
    participant Database

    User->>PublishWorkflow: Saisie Titre & Code
    PublishWorkflow->>PublishWorkflow: Validation (Step-by-step)
    User->>PublishWorkflow: Clique sur Publier
    PublishWorkflow->>CreatePostAction: execute($user, $data)
    CreatePostAction->>Database: Transaction (Post + Snippets)
    Database-->>CreatePostAction: ID Post
    CreatePostAction-->>PublishWorkflow: Objet Post
    PublishWorkflow->>User: Redirection Dashboard
```
*Pour plus de détails, voir l'US12 : [Diagramme de Séquence](../us/us12_diagramme_sequence.md).*

---

## 🛠️ API & Routes

L'application utilise principalement des routes Web dynamiques via Livewire :
- `/dashboard` : Hub principal (Feed).
- `/publish` : Workflow de création de post.
- `/posts/{id}` : Vue détaillée et annotations.
- `/groups` : Gestion des Groupes.
