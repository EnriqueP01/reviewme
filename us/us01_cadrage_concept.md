# US01 - Définir le problème, la cible et la proposition de valeur

## Description
**EN TANT QUE** Product Owner  
**JE VEUX** Formaliser le problème éducatif traité, les utilisateurs visés et la valeur attendue  
**AFIN DE** ancrer le projet dans un besoin crédible et éviter de construire une application gadget

---

## Critères d'acceptation
- [x] Le problème est décrit avec causes, impacts et contexte d'usage éducatif.
- [x] La cible principale et les segments utilisateurs sont explicités.
- [x] La proposition de valeur explique qui gagne quoi et par rapport à quelle situation actuelle.
- [x] Le document permet à un tiers de comprendre pourquoi le projet mérite d'exister.

---

## Réponse et Cadrage du Projet

C'est un choix technique très stratégique. En ciblant le **Web en priorité** avec Flutter, tu réponds parfaitement à l'usage "Développeur" : on a souvent l'IDE ouvert sur un écran et le navigateur sur l'autre pour copier-coller du code.

Voici la structure finale de ton projet, optimisée pour le Web avec cette culture de la bienveillance et de la hiérarchie :

### 1. La Phrase "Clarté Totale"
> « La plateforme web de revue de code où la bienveillance est inscrite dans le code : une hiérarchie de supervision pour guider et un système de "Boost" uniquement positif pour progresser sans crainte. »

### 2. 10 Améliorations "Twist, Contrainte & Preuve" (Spécial Web)

1.  **Le Rôle "Architecte" & Groupes**
    * **Twist :** Le créateur d'un groupe (ex: une classe ou un projet) nomme des **Superviseurs**.
    * **Contrainte :** Seuls les Superviseurs peuvent "Certifier" une réponse.
    * **Preuve :** Une section "Solutions certifiées" en haut de chaque post.

2.  **Mise en avant Automatique**
    * **Twist :** Tout message posté par un superviseur est **automatiquement mis en avant** (taille de police légèrement supérieure ou contour distinctif).
    * **Contrainte :** Limité à 2 messages mis en avant par fil pour garder la clarté.
    * **Preuve :** Badge "Expert" dynamique.

3.  **Le "Boost" Positif (Système de Like)**
    * **Twist :** Aucun bouton négatif. On "Boost" une réflexion qu'on trouve élégante ou maligne.
    * **Contrainte :** Cliquer sur Boost déclenche une micro-animation Antigravity qui fait léviter légèrement le bloc de code.
    * **Preuve :** Score de "Sagesse" global sur le profil (somme des Boosts).

4.  **Le "Smart Paste" Web**
    * **Twist :** À la création d'un post, l'app détecte automatiquement le langage (Dart, JS, Python) via le presse-papier.
    * **Contrainte :** Obligation d'ajouter un commentaire d'intention ("Je voulais faire X...").
    * **Preuve :** Moins de friction à l'entrée.

5.  **Curateur de Pépites**
    * **Twist :** Un superviseur peut "promouvoir" le message d'un simple utilisateur.
    * **Contrainte :** Cela transforme le message en "Réponse de Référence".
    * **Preuve :** Notification spéciale "Votre logique a été saluée par un expert".

6.  **L'Anonymat Protecteur**
    * **Twist :** Les noms et avatars sont floutés sur les reviews tant qu'on n'a pas survolé le bloc de code (focus sur le fond, pas la forme).
    * **Contrainte :** Désactivé pour les Superviseurs (leur autorité est transparente).
    * **Preuve :** Réduction du biais de confirmation.

7.  **Le Side-by-Side Interactif**
    * **Twist :** Sur le Web, on profite de la largeur d'écran pour comparer l'original et la suggestion de revue côte à côte.
    * **Contrainte :** Utilisation de la souris pour scroller les deux blocs en miroir.
    * **Preuve :** Clarté visuelle immédiate des changements.

8.  **Antigravity "Feedback"**
    * **Twist :** Utiliser la physique pour simuler l'importance : plus un post a de "Boosts", plus il a d'inertie et reste fixe dans le scroll.
    * **Contrainte :** Ne pas gêner la lisibilité.
    * **Preuve :** Navigation organique et ludique.

9.  **Le Mode "Lecteur Zen"**
    * **Twist :** Un raccourci clavier (`Echap`) qui cache tout sauf le code et la meilleure review.
    * **Contrainte :** Exclusivement sur la version Web.
    * **Preuve :** Concentration maximale pour comprendre la logique.

10. **L'Historique de Progression**
    * **Twist :** Un graphique montre l'évolution du ratio "Code posté" vs "Reviews boostées".
    * **Contrainte :** Pas de classement public, juste un dashboard privé.
    * **Preuve :** Motivation par l'auto-amélioration.

### 3. Les Trois Versions du Concept

*   **Version Simple (MVP Web)** : Un feed où l'on poste du code. Bouton "Boost" unique. Le créateur peut épingler un message. Gestion simple des rôles (Admin/User).
*   **Version Solide (Celle que tu publies)** : Gestion complète des **Superviseurs**. Mise en avant automatique de leurs messages. Groupes privés/publics. Système de "Boost" avec animations Antigravity. Comparateur de code côte à côte.
*   **Version Ambitieuse** : Extension Chrome qui permet de "Boost" un code directement sur GitHub ou StackOverflow et de l'importer dans ta plateforme pour en discuter. IA qui suggère des superviseurs en fonction du langage détecté.

### 4. Plan d'Exécution (3.5 Jours - Focus Web)

1.  **J1 : Structure & Rôles (Firebase/Supabase)**
    * Modèle de données : `Posts`, `Reviews`, `Groups`, `Boosts`.
    * Logique de permissions : Qui peut nommer un superviseur ? (Le créateur).
2.  **J2 : UI Code & Physique (Flutter + Antigravity)**
    * Intégration du rendu Markdown et coloration syntaxique.
    * Mise en place du système de "Boost" avec feedback visuel (vibration/lévitation).
3.  **J3 : Hiérarchie & Supervision**
    * Algorithme de tri : Messages superviseurs en haut (auto-highlight).
    * Développement du mode "Side-by-Side" pour les suggestions de code.
4.  **J4 (Matin) : Optimisation Web & Polish**
    * Gestion du Responsive (même si priorité Web, ça doit rester propre).
    * Raccourcis clavier et gestion du copier-cliquer.
5.  **J4 (Midi) : Déploiement**
    * Hébergement (Firebase Hosting ou Vercel) et test live.

> **Conseil Antigravity :** Sur le Web, utilise-le pour les transitions entre les pages et pour faire "respirer" les blocs de code mis en avant. Cela donnera un aspect très moderne et haut de gamme à l'application.

