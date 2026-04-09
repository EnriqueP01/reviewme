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
Le MVP se concentre sur le cycle de vie d'un post de code au sein d'une communauté d'étudiants.

- **Authentification & Identité** : Connexion via GitHub (OAuth). Profil avec score de réputation et historique.
- **Moteur de Curation (Publish Workflow)** :
    - Import multi-fichiers asynchrone par drag-and-drop.
    - Détection automatique du langage et télémétrie (LOC, KB, Doublons MD5).
    - Métadonnées granulaires (Buts de revue, Objectifs d'amélioration, Lenses).
- **Consommation & Collaboration d'Élite** :
    - **Feed** : Dashboard avec recherche "Live" et filtrage chromatique par Lenses.
    - **Groupes** : Espaces privés pour isoler les projets ou classes.
    - **Versioning** : Support de l'itération continue sur un même post.
- **Feedback Interactif (Précision IDE)** :
    - **Full Review** : Publication de versions alternatives complètes du code (PR-Style).
    - **Quick Review** : Suggestions de modifications contextuelles (Inline) sur des lignes spécifiques.
    - **Discussion Threadée** : Commentaires globaux, réponses imbriquées et réactions polymorphiques (Likes/Votes).

### 2. Hors Périmètre (Post-MVP)
Éléments volontairement écartés pour garantir la tenue des délais :

- **Analyse Statique par IA** : Automatiser la revue via un LLM (risque de faux positifs élevé).
- **Messagerie Instantanée** : Chat en temps réel hors système de revue.
- **Application Mobile Native** : Le responsive web suffit pour l'usage sur laptop (IDE-style).
- **Audit Logs d'Administration** : Traçabilité ultra-détaillée des actions admin.

### 3. Critères de Coupe (Downscoping)
Si le retard menace la livraison, les éléments suivants seront retirés dans cet ordre :

1.  **Système de Suggestions Inline** : On se limite aux commentaires globaux et aux Full Reviews.
2.  **Heatmap d'Activité** : Supprimer la visualisation graphique de la réputation sur le profil.
3.  **Détection de Clones (MD5)** : Retirer la validation automatique contre les doublons de contenu.
4.  **Multi-Threading de Lenses** : Limiter la curation à un seul type de focus par post.

### 4. Cohérence du Produit
ReviewMe reste un outil de collaboration complet permettant de charger, partager et auditer du code de manière structurée et sensorielle, remplissant sa promesse de "hub de curation collaborative".
