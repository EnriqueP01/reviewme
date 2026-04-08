# US01 - Définir le problème, la cible et la proposition de valeur

## Description
**EN TANT QUE** Product Owner  
**JE VEUX** Formaliser le problème éducatif traité, les utilisateurs visés et la valeur attendue (ReviewMe)  
**AFIN DE** ancrer le projet dans un besoin crédible et éviter de construire une application gadget.

---

## Critères d'acceptation
- [x] Le problème est décrit avec causes, impacts et contexte d'usage.
- [x] La cible principale (Lucas) et les segments utilisateurs sont explicités.
- [x] La proposition de valeur explique qui gagne quoi par rapport à la situation actuelle.
- [x] La stack technique et l'architecture simplifiée (KISS) sont documentées.

---

## 🚀 Projet : ReviewMe - Plateforme de Peer-Review

### 1. Vision du Produit
Une plateforme axée sur l'amélioration de la qualité du code par l'échange et la bienveillance. Contrairement à StackOverflow, on n'y vient pas pour réparer un bug, mais pour demander : **"Qu'est-ce que vous pensez de mon approche ?"**. L'objectif est l'élévation du niveau architectural par la confrontation constructive.

### 2. Stack Technique (The TALL Stack +)
*   **Framework** : Laravel 11 (Architecture épurée).
*   **Frontend** : Livewire 3 (Interactivité serveur réactive sans complexité JS).
*   **Styling** : Tailwind CSS (Design système fluide).
*   **Interactions** : Alpine.js pour les micro-animations.
*   **Temps Réel** : Laravel Reverb (Websockets natifs) pour la présence et le "Typing indicator".
*   **Coloration Syntaxique** : Shiki.php (Rendu serveur "VS Code style").
*   **Base de données** : PostgreSQL.
*   **Authentification** : Laravel Socialite (Login GitHub obligatoire).

### 3. Backlog des Fonctionnalités (MVP+)

#### A. Authentification & Profils
*   **GitHub OAuth** : Connexion exclusive via GitHub (récupération pseudo/avatar).
*   **Developer Card** : Profil affichant la stack et un score de **"Karma"** (utilité des reviews).

#### B. Création & Gestion de "Reviews" (Posts)
*   **Éditeur de Snippet** : Support Markdown pour la description et Shiki pour le code.
*   **Système de Versioning** : 
    *   Publication d'une V1.
    *   Après feedback, publication d'une **V2 liée** pour montrer l'évolution de la réflexion.
*   **Visibilité** : Public, Lien privé (URL unique), ou Groupe (espace dédié).

#### C. Système de Review & Social
*   **Inline Commenting (Crucial)** : Cliquer sur un numéro de ligne pour ouvrir un champ de commentaire lié.
*   **Reactions "Tech-Focused"** : 
    *   ✨ *Clean Code*
    *   🚀 *Optimisable*
    *   🤯 *Mindblown* (Appris quelque chose)
    *   🛡️ *Sécurité* (Warning)
*   **Threads** : Discussions techniques imbriquées.

#### D. Groupes & Espaces Privés
*   **Espaces Collaboratifs** : Création de groupes (ex: "Équipe Backend X").
*   **Invitations** : Partage via lien unique ou email.

#### E. Real-Time Status (L'aspect vivant)
*   **Indicateur de présence** : Voir qui consulte le code en temps réel.
*   **Typing indicator** : "Un expert est en train d'écrire une review..." (via Reverb).

### 4. Architecture de Données (Schéma KISS)
*   **users** : id, github_id, name, avatar, reputation_score, bio.
*   **groups** : id, name, slug, owner_id.
*   **posts** : id, user_id, group_id (nullable), title, description, visibility.
*   **snippets** : id, post_id, version_number, code_content, language (Plusieurs snippets par post pour V1, V2...).
*   **reviews** : id, snippet_id, user_id, line_number, content.
*   **reactions** : id, review_id OR post_id, user_id, type (enum).

### 5. Guide de Style (ReviewMe UI)
*   **Mode Sombre forcé** : Esthétique premium "Dev First".
*   **Typography** : **Inter** pour l'interface, **JetBrains Mono** pour le code.
*   **Design** : Très peu de bordures, whitespace généreux, arrondis `rounded-xl`, et transitions fluides (Alpine.js).

### 6. Rationale du Lead Dev (Simplicité & Efficacité)
*   **MVP Focalisé** : Le cœur est la lecture de code. Pas de superflu.
*   **Maintenance Réduite** : Pas de gestion de state complexe côté front, tout est piloté par le serveur via Livewire.
*   **Scalabilité** : Le système de versions encourage l'utilisateur à rester pour suivre sa propre progression.
