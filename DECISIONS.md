
## 2026-04-09-23 : Harmonisation Sémantique & Standardisation Professionnelle
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Utilisation de terminologies génériques peu professionnelles et défaut d'ergonomie sur les hitboxes HUD.
- **Décision** :
    1. **Refonte Lexicale Globale** : Migration vers un lexique "Enterprise-grade" :
        - `NODE_ORIGIN` -> `SOURCE_ORIGIN`
        - `Inspect Pattern` -> `Inspect Post`
        - `Exploration_Details` -> `Artifact_Analysis`
        - `Logic Body` -> `Logic_Implementation`
    2. **Optimisation UX (Hitbox)** : Correction du bouton d'inspection dans le Feed. Neutralisation des `pointer-events` sur les icônes internes et passage en mode `static` pour garantir une zone de clic 100% fiable sans interférence du magnétisme Alpine.js.
- **Impact** : Renforcement du positionnement premium de la plateforme et fluidité d'interaction accrue sur le flux principal.

## 2026-04-09-24 : Résolution des Conflits de Drop & Sync State
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Échec du Drag & Drop de réorganisation et de la détection lors du premier import.
- **Décision** :
    1. **Sync State Backend** : Centralisation de l'heuristique dans `detectLanguage($index)` appelée par `importFile` via le pont Alp-Wire.
    2. **Dissociation Drop** : Utilisation de `.stop` pour isoler les imports de fichiers externes du drag-and-drop de tri interne.
- **Impact** : Fiabilité de l'import de 100% dès la première interaction.

## 2026-04-09-25 : Allégement Visuel & Raffinement UX (Feed & Code)
- **Auteur** : Antigravity
- **Statut** : ✅ Implémenté
- **Contexte** : Présence de bordures blanches intrusives, mauvais alignement des numéros de ligne et besoin d'une animation plus dynamique sur les titres du feed.
- **Décision** :
    1. **Animation de Titre "Reveal"** : Passage d'un header statique à une structure dynamique. Le titre est centré par défaut et coulisse vers le bas au survol pour révéler la description, accompagné d'une icône d'artéfact.
    2. **Refonte de la Pagination** : Alignement sur le design "Monolith & Glass". Remplacement des labels système par des termes métier ("Résultat", "Affichage").
    3. **Optimisation Code Explorer** :
        - Alignement vertical strict des numéros de ligne (suppression du padding asymétrique).
        - Nettoyage des bordures blanches sur les onglets et le conteneur principal.
        - Correction du bug de scrollbar fictive sur la navigation multi-fichiers.
    4. **Centralisation Chromatique** : Injection de couleurs spécifiques par "Lens" (Security: Red, Logic: Cyan, Performance: Green) sur toute la chaîne UI, incluant les hashtags de métadonnées.
- **Impact** : Interface plus "aérée", suppression du bruit visuel (borders blanches) et hiérarchie de lecture optimisée.
