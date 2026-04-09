# [Dev] US26 - Gérer les états vides, erreurs et cas limites

## Description de la story
EN TANT QUE Développeur  
JE VEUX Prévoir les comportements de l'application hors chemin idéal  
AFIN DE éviter qu'un produit apparemment fonctionnel casse dès qu'un sort du scénario parfait

## Critères d'acceptation
- [x] les écrans critiques disposent d'un état vide compréhensible et exploitable
- [x] les erreurs de validation sont distinguées des erreurs techniques et affichées clairement
- [x] au moins 5 cas limites métier ou techniques sont identifiés et traités ou explicitement exclus du MVP
- [x] au moins un test ou scénario vérifie ces comportements

---

### 1. États Vides (Empty States)

| Écran | Déclencheur | Affichage / Action |
| :--- | :--- | :--- |
| **Feed Principal** | Aucun post publié ou base vide. | Message HUD : "Aucun artefact de code détecté. Soyez le premier à publier." + Bouton géant "Publish". |
| **Mes Groupes** | L'utilisateur n'appartient à aucun groupe. | Illustration monoline et lien "Créer ou rejoindre un groupe". |
| **Recherche** | Aucun résultat pour le filtre/requête. | Message : "Aucune correspondance trouvée pour '...'." + Bouton "Clear Filters". |

### 2. Gestion des Erreurs

*   **Erreurs de Validation** : Affichées via `@error` dans Blade. Messages précis (ex: "Le titre doit faire au moins 5 caractères") pour guider l'utilisateur.
*   **Erreurs Techniques** : Capturées par le Kernel. L'utilisateur voit une page de maintenance ou un Toast HUD générique : "Erreur de synchronisation système." Les détails techniques sont masqués.

### 3. Cas Limites (Edge Cases) Traités

1.  **Plagiat Immédiat** : Tentative d'importer un fichier déjà présent dans le même post (détection par nom de fichier). -> *Alerte visuelle et blocage de l'ajout.*
2.  **Upload Volumineux** : Tentative d'import d'un fichier > 2MB (limite PHP/Nginx). -> *Message d'erreur explicite sur la taille.*
3.  **Fichier Binaire** : Import d'une image ou d'un .exe au lieu de code source. -> *Validation d'extension et rejet.*
4.  **Accès Concurrent** : Deux utilisateurs éditent le même groupe simultanément. -> *L'action la plus récente l'emporte, rafraîchissement Livewire forcé.*
5.  **Perte de Session** : Déconnexion GitHub pendant un workflow de publication long. -> *Sauvegarde locale temporaire (Local Storage) ou message de reconnexion.*

### 4. Scénario de Test
- **Test de robustesse** : Simuler un upload de 50 fichiers simultanés dans le Publish Workflow.
- **Vérification** : Le système doit traiter la file de manière asynchrone sans geler l'interface et bloquer les doublons de noms automatiquement.
