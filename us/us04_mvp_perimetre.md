# [Produit] US04 - Définir le MVP, le hors périmètre et les critères de coupe

## Description de la story
EN TANT QUE Product Owner  
JE VEUX Identifier le noyau minimal livrable et ce qui sera volontairement écarté ou reporté  
AFIN DE forcer la priorisation réelle dans un cadre de temps étudiant limité

## Critères d'acceptation
- [x] la liste MVP contient uniquement les éléments indispensables au parcours principal
- [x] les éléments hors périmètre sont listés avec justification
- [x] des critères de coupe sont définis pour décider quoi retirer en cas de retard
- [x] le MVP reste démontrable comme un produit cohérent et non comme une maquette vide

---

### 1. Noyau Minimal Livrable (MVP)
Le MVP se concentre sur le cycle de vie d'un artefact de code (Post) au sein d'une communauté d'étudiants.

- **Authentification Sécurisée** : Connexion via GitHub (OAuth) pour lier l'identité de développeur.
- **Moteur de Curation (Publish Workflow)** :
    - Import massif de fichiers par drag-and-drop.
    - Détection automatique du langage de programmation.
    - Association de métadonnées (Lenses : Logic, Opti, Beauty).
- **Consommation & Collaboration** :
    - Dashboard/Feed avec recherche plein texte et filtres par langage/Lens.
    - Système de Groupes (ex-Labs) pour isoler les revues par projet ou classe.
    - Versioning : Possibilité de soumettre une itération de code sur un post existant.
- **Feedback** : Système de réactions et de reviews par fragments.

### 2. Hors Périmètre (Post-MVP)
Éléments volontairement écartés pour garantir la tenue des délais :

- **Analyse Statique par IA** : Automatiser la revue via un LLM (trop complexe pour la phase initiale, risque de faux positifs).
- **Messagerie Instantanée** : Chat en temps réel dans les groupes (utilité secondaire face au système de reviews asynchrones).
- **Application Mobile Native** : Le responsive web suffit pour l'usage étudiant sur tablette/laptop.
- **Statistiques d'Équipe Avancées** : Dashboards complexes pour enseignants (reporté pour focus sur l'expérience étudiant).

### 3. Critères de Coupe (Downscoping)
Si le retard menace la livraison, les éléments suivants seront retirés dans cet ordre :

1.  **Système de Versioning (US41)** : On se limite à une seule version par post.
2.  **Heatmap d'Activité** : Supprimer la visualisation graphique de la réputation.
3.  **Détection de Clones (MD5)** : Retirer la validation technique contre le plagiat immédiat.
4.  **Multi-Lenses** : Limiter la curation à un seul type de review par post.

### 4. Cohérence du Produit
Même sans ces options, ReviewMe reste un outil complet permettant de charger du code, de le partager en privé/public et de recevoir des revues structurées, remplissant sa promesse de "plateforme de curation collaborative".
