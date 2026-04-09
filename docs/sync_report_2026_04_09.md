# Rapport de Synchronisation - Doc vs Code (2026-04-09)

Ce document liste les modifications apportées aux User Stories pour garantir que la documentation est la source de vérité exacte du code actuel.

## Résumé des Modifications

### [US04 - MVP, Périmètre & Coupes](file:///Users/Nolhan/Documents/reviewme/us/us04_mvp_perimetre.md)
- **Modifié** : Liste du MVP étendue pour inclure les fonctionnalités de collaboration réelle implémentées (**Full Reviews**, **Inline Suggestions**, **Versioning**).
- **Modifié** : Harmonisation nomenclature (**Vibe -> Post**, **Lab -> Group**).
- **Ajouté** : Détails de télémétrie technique (MD5, LOC, KB) comme part intégrante du moteur de curation.
- **Obsolète** : Suppression de la mention "Analyse IA" dans le MVP (clairement hors périmètre dans le code).

### [US09 - Conception UX](file:///Users/Nolhan/Documents/reviewme/us/us09_conception_ux.md)
- **Modifié** : Synchronisation du **Publish Workflow** (passage de 4 à 3 étapes réelles : Détails -> Fichiers -> Focus).
- **Ajouté** : Description du "Double Mode" d'interaction dans le détail du post (Quick Review vs Full Review).
- **Modifié** : Palette chromatique mise à jour selon les derniers tokens CSS (`#1a1b26`).

### [US13 - Modèle de Domaine](file:///Users/Nolhan/Documents/reviewme/us/us13_modele_domaine.md)
- **Ajouté (Entités)** : `FullReview`, `InlineSuggestion`, `PostComment`, `FullReviewSnippet`.
- **Modifié (Schéma)** : Nouveau diagramme Mermaid incluant toutes les relations de feedback et de versioning.
- **Modifié (Champs)** : Ajout des champs `filename`, `sort_order`, `is_pinned`, `review_goals`, `improvement_goals`, `lens`.
- **Supprimé** : Référence à la "Logic Density" (supprimée du code car trop complexe à maintenir).

### [US14 - Rôles & Permissions](file:///Users/Nolhan/Documents/reviewme/us/us14_roles_permissions_erreurs.md)
- **Ajouté** : Rôle d'Admin capable d'épingler des commentaires (`is_pinned`).
- **Modifié** : Matrice de permissions mise à jour pour inclure les nouveaux types de collaboration (Full vs Quick).

### [US15 - Contrat API](file:///Users/Nolhan/Documents/reviewme/us/us15_contrat_api.md)
- **Modifié** : Schéma JSON de création de post synchronisé avec `PublishWorkflow:validate()`.
- **Ajouté** : Support des champs `short_description`, `review_goals` et `improvement_goals`.
- **Modifié** : Type du champ `lens` (string séparée par des virgules en DB).

---
*Audit réalisé par Antigravity (PO/Architecte) - 09/04/2026*
