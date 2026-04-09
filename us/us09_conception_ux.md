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

1.  **Dashboard (Feed)** :
    - *Rôle* : Hub central de consommation et de recherche.
    - *Zones* : Barre de recherche "Live", filtres "Lens", grille de cartes à haute densité d'information.
    - *Animations* : Reveal du titre au survol, transition fluide des filtres.
2.  **Workflow de Publication (Publish Workflow)** :
    - *Rôle* : Tunnel de dépôt structuré.
    - *Étapes Réelles* : 
        1. **Détails** (Titre, Buts de revue, Objectifs d'amélioration).
        2. **Fichiers** (Dropzone massive, télémétrie LOC/KB/Complexity, détection de doublons).
        3. **Focus & Distribution** (Sélection multi-lenses, Visibilité Publique/Groupe).
    - *UX* : Stepper géométrique scellé au design HUD.
3.  **Explorateur de Code (Post Detail)** :
    - *Rôle* : Interface de collaboration technique.
    - *Interaction Double Mode* :
        - **Quick Review** : Sélection de code -> Overlay contextuel -> Suggestion de modification directe. Visualisation des contributeurs dans la Gouttière HUD.
        - **Full Review** : Basculement en mode "PR" -> Modification globale des fichiers -> Prévisualisation des Diffs en temps réel (Live Diff Engine).
    - *Navigation* : Système d'onglets synchronisés, sélecteur de version interactif.
4.  **Group Manager** :
    - *Rôle* : Espace de collaboration privée.
    - *Zones* : Création instantanée, Dashboard de groupe, Gestion des membres.

### 2. États UI Contextuels

- **État Normal** : "Monolith & Glass" avec flous progressifs (`backdrop-blur`) et textures HUD.
- **État Vide** : "Zero Post Detected" / "No Result Found". Illustrations monoline évitant la frustration.
- **État Erreur** : Lueurs "Rubis" (Rouge) pour les erreurs système, "Ambre" (Orange) pour la validation.
- **État Loading** : Overlays de flou (`backdrop-bloom`) et boutons réactifs avec spinners intégrés (`animate-spin`).

### 3. Mini Guide UI (HUD Design System)

*   **Identité Visuelle** : Palette sombre (`#1a1b26`, `#0d0e12`).
*   **Signatures Chromatiques Lenses** :
    - **Logic** : Jaune/Ambre (`#F59E0B`) - Focus sur la structure et le flux.
    - **Beauty** : Bleu/Sky (`#06B6D4`) - Focus sur le style et l'architecture.
    - **Opti/Performance** : Vert/Emerald (`#10B981`) - Focus sur la rapidité et les ressources.
*   **Composants Critiques** :
    - *Buttons* : `wire:loading` géré nativement (désactivation + lueur).
    - *Code Viewers* : Tab-based, max-height contrôlé (600px), scrollbar HUD.

### 4. Référence Technique
L'interface privilégie la **haute densité d'information** (compact IDE feel) plutôt que les espaces blancs génériques.
