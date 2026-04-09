# [UX] US09 - Concevoir les écrans clés et les règles d'interface

## Description de la story
EN TANT QUE UX Designer  
JE VEUX Produire les maquettes du parcours principal et un socle de cohérence visuelle  
AFIN DE réduire les ambiguïtés avant développement et éviter une interface patchwork

## Critères d'acceptation
- [x] les écrans structurants du parcours principal sont représentés avec zones, actions et informations attendues
- [x] les états normal, vide et erreur sont prévus quand ils sont pertinents
- [x] un mini guide UI précise composants récurrents, navigation, formulaires et feedback utilisateur
- [x] les maquettes servent de référence aux développeurs et aux agents IA

---

### 1. Parcours Principal : Les 4 Écrans Clés

1.  **Le Feed (Dashboard)** :
    - *Rôle* : Centralisation des curations publiques et privées.
    - *Zones* : Barre de recherche dynamique, filtres par Lenses, Liste de cartes (Posts) avec aperçu du langage et de la version.
    - *Actions* : Inspection (Quick View), navigation vers le détail.
2.  **Workflow de Publication (Publish Step)** :
    - *Rôle* : Tunnel de création d'un artefact.
    - *Zones* : Stepper horizontal (Files -> Context -> Deploy), Zone de drop massive, sélecteur de visibilité.
    - *Feedback* : Barre de progression animée et télémétrie en temps réel (LOC, KB).
3.  **Explorateur de Code (Post Detail)** :
    - *Rôle* : Analyse approfondie du code.
    - *Zones* : Navigation par fichiers (onglets), Gouttière de numérotation, Sélecteur de versions.
    - *Esthétique* : Thème sombre haute-fidélité, coloration syntaxique dynamique.
4.  **Gestionnaire de Groupes (Group Manager)** :
    - *Rôle* : Administration de la collaboration privée.
    - *Zones* : Mes groupes, Création rapide, Liste des membres.

### 2. États UI Contextuels

- **État Normal** : Utilisation du design "Monolith & Glass" avec flous d'arrière-plan (`backdrop-blur`).
- **État Vide** : Illustrations stylisées pour les feeds vides (ex: "Aucun artefact détecté dans ce secteur") avec bouton d'action principal bien visible.
- **État Erreur** : Notifications Toast (HUD style) avec lueurs rouges pour les erreurs critiques et ambre pour les validations.
- **État de Chargement** : Progression progressive au sommet (style GitHub) et overlays flous sur les composants en mutation (Livewire loading).

### 3. Mini Guide UI (HUD Design System)

*   **Typographie** : Fonts sans-serif modernes (Inter/Outfit) pour l'UI, Monospaced (JetBrains Mono/Fira Code) pour les identifiants techniques et le code.
*   **Couleurs des Lenses** :
    - **Logic** : Jaune/Ambre (`#F59E0B`)
    - **Beauty** : Bleu/Cyan (`#06B6D4`)
    - **Opti/Performance** : Vert/Émeraude (`#10B981`)
    - **Security** : Rouge/Rose (`#EF4444`)
*   **Composants Récurrents** :
    - *Boutons* : Effet "Glow" au survol, coins arrondis précis.
    - *Cartes* : Translucides, bordures subtiles (1px), coins 1.5rem.
    - *Navigation* : Centrée, flottante si besoin.

### 4. Référence Technique
L'interface doit rester fidèle au concept de **HUD (Heads-Up Display)**, privilégiant la densité d'information utile sans encombrement visuel inutile.
