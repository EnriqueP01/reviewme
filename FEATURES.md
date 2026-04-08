# Spécifications Fonctionnelles - ReviewMe 🧬

ReviewMe est une plateforme de curation de code "haute-fidélité" conçue pour transformer la revue de code en une expérience immersive et collaborative. Voici le détail des fonctionnalités implémentées.

---

## 1. 🛡️ Authentification & Identité
*   **Neural Link (GitHub OAuth)** : L'accès à la plateforme est exclusivement géré via GitHub. L'inscription standard est désactivée pour garantir une identité de développeur vérifiée.
*   **Identité de Curation** : Chaque utilisateur possède un identifiant unique (préfixé par `@`) et un score de réputation (Karma) visible sur son profil.
*   **Profils Premium** : Support des avatars GitHub avec fallback sur UI-Avatars, bio personnalisée et heatmap d'activité.

## 2. 🚀 Curation d'Artefacts (Publication)
*   **Workflow en 3 Étapes** :
    1.  **Introspection** : Définition du titre, résumé court, buts de revue et objectifs d'amélioration.
    2.  **Artefacts (Code)** : Upload multi-fichiers par Drag-and-drop. Détection automatique du langage (PHP, JS, CSS, Python, etc.) basée sur l'extension.
    3.  **Distribution** : Choix de la visibilité (Publique ou restreinte à un Lab) et sélection des "Lenses" (focus spécialisés : Clarity, Security, Performance, Logic).

## 3. 🧪 Neural Labs (Collaboration Groupée)
*   **Unités Tactiques** : Création de groupes privés (Labs) pour isoler les revues sensibles ou les projets d'équipe.
*   **Gestion des Rôles** : Système de permissions incluant le Propriétaire (`Director`), les Modérateurs et les Membres.
*   **Isolation des Flux** : Les artefacts publiés dans un Lab sont strictement invisibles pour les utilisateurs externes, garantissant une étanchéité totale des données.

## 4. 🔍 Flux de Curation (Feed & Recherche)
*   **Interactive Feed** : Affichage dynamique des artefacts avec prévisualisation du snippet principal.
*   **Lenses Filtering** : Système de filtrage par "focus" technique pour permettre aux experts de trouver les segments de code correspondant à leur spécialité.
*   **Recherche de Densité** : Barre de recherche performante pour filtrer par titre ou description.

## 5. 💬 Système de Revue & Karma
*   **Revue Inline** : Capacité d'ajouter des commentaires (Reviews) sur des lignes de code spécifiques de n'importe quel fichier de l'artefact.
*   **Interaction Karma** : Les utilisateurs peuvent réagir aux artefacts via des types spécifiques (`mindblown`, `clean`, `security`, `optimisable`).
*   **Réputation Dynamique** : Gain de points automatisé basé sur la qualité perçue des contributions. Un retrait de réaction annule les points associés.

## 6. 🎧 Immersion & HUD (UX/UI)
*   **Design System "The Monolith"** : Esthétique sombre, Glassmorphism et bordures cybernétiques.
*   **HUD Toasts** : Notifications système stylisées (Heads-Up Display) pour les interactions critiques.
*   **Neural Feedback** : Retour audio procédural (oscillateurs Web Audio) et simulation haptique lors des votes et actions système.
*   **Heatmap de Densité** : Visualisation temporelle de l'activité de curation d'un utilisateur directement sur son profil.

---
*Dernière mise à jour : 2026-04-09 par Antigravity Agent.*
