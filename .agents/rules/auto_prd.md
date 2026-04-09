# Règle : Auto-PRD Adaptatif & Raffinement de Prompt

Cette règle définit le comportement de l'agent pour adapter dynamiquement la structure de planification en fonction de la complexité de la demande utilisateur.

## 📊 Matrice d'Adaptation
L'agent doit choisir le format le plus pertinent avant d'agir :

### 1. Format FULL PRD (Complexité Élevée)
*   **Usage** : Nouvelles fonctionnalités majeures, refontes architecturales, systèmes multi-composants.
*   **Structure** :
    *   **Context** : Vision produit et besoin utilisateur.
    *   **Architecture** : Schéma des interactions (Models, Actions, Lenses, Policies).
    *   **Security & i18n** : Analyse des risques et clés de traduction.
    *   **Test Plan** : Liste des cas de tests critiques.
    *   **US Link** : Story dédiée dans `us/`.

### 2. Format MINI-PRD (Complexité Moyenne)
*   **Usage** : Ajout d'une option, modification d'un workflow existant, création d'un nouveau composant UI.
*   **Structure** :
    *   **Context** : Résumé technique court.
    *   **Tech Spec** : Composants impactés et logique modifiée.
    *   **US Link** : Mise à jour de la story correspondante.

### 3. Format NOTE TECHNIQUE (Optimisation / Refactor)
*   **Usage** : Amélioration de performance, nettoyage de code, modification de signature de fonction.
*   **Structure** : 
    *   **Target** : Fichiers concernés.
    *   **Logic** : Changement apporté.
    *   **Impact** : Bénéfice attendu (perf/clarté).

### 4. FAST-TRACK (Maintenance / Trivial)
*   **Usage** : Correction de fautes, commandes terminal, questions informatives, bugs mineurs évidents.
*   **Action** : Exécution immédiate sans bloc de spécification.

## 📜 Principes Directeurs
- **Zéro Analogie** : La spécification reste 100% technique et factuelle.
- **Auto-Sélection** : L'agent annonce le format choisi en début de réponse.
- **Validation** : Respect strict de `global.md` et `architecture.md`.
