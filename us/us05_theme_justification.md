# [Produit] US05 - Choisir le thème précis de l'application et justifier les choix produit

## Description de la story
EN TANT QUE Équipe projet  
JE VEUX Sélectionner le thème éducatif retenu et expliquer les décisions produit majeures  
AFIN DE passer d'un cadre générique à un projet concret sans perdre la logique de valeur

## Critères d'acceptation
- [x] le thème choisi appartient clairement au domaine de l'éducation
- [x] au moins 2 thèmes alternatifs écartés sont listés avec raison de rejet
- [x] au moins 3 décisions produit majeures sont justifiées avec impact attendu
- [x] les choix retenus sont cohérents avec le problème, les personas et le MVP

---

### 1. Thème Choisi : Revue de Code Collaborative Étudiante
ReviewMe est une plateforme de **curation éducative**. Elle permet aux étudiants de soumettre des fragments de code (artefacts) pour recevoir des retours critiques de leurs pairs avant une soumission officielle. Le but est de transformer la revue de code, souvent perçue comme un obstacle, en un exercice d'apprentissage gratifiant.

### 2. Thèmes Alternatifs Écartés
Avant de stabiliser le concept, deux autres directions ont été envisagées :

1.  **Plateforme de Freelance pour Audits de Sécurité** :
    - *Raison du rejet* : Trop complexe sur le plan légal et de la vérification des experts pour un projet étudiant. Le marché était déjà saturé par des outils professionnels comme Synack.
2.  **Journal de Bord Personnel pour Développeurs** :
    - *Raison du rejet* : Manque de dimension collaborative. Le projet risquait de devenir un simple "Notes de dev" sans réelle interaction sociale ou valeur ajoutée par rapport à Notion ou Obsidian.

### 3. Décisions Produit Majeures Justifiées

*   **Décision A : Authentification exclusive via GitHub**
    - *Justification* : ReviewMe s'adresse aux développeurs. L'usage de GitHub assure que l'utilisateur possède déjà une culture du code et permet l'intégration future du stockage distant.
    - *Impact* : Réduction de la friction à l'inscription et crédibilité immédiate.
*   **Décision B : Système de Groupes (ex-Labs) Privés**
    - *Justification* : Les étudiants travaillent souvent en classes ou en groupes de projets fermés pour éviter le plagiat généralisé.
    - *Impact* : Création d'un espace de confiance où le partage n'est pas synonyme d'exposition publique non désirée.
*   **Décision C : Introduction des "Lenses" (Opti, Logic, Beauty)**
    - *Justification* : Une revue de code brute est intimidante. Catégoriser les retours simplifie l'analyse.
    - *Impact* : Amélioration de la qualité des retours et gamification de la correction.

### 4. Cohérence Persona
Ce thème répond directement au besoin du persona **Lucas (Étudiant en Master)**, qui cherche à valider ses choix d'architecture dans un cadre bienveillant et structuré avant son examen final.
