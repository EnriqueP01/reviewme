# [Architecture] US11 - Justifier les choix techniques et de stack

## Description de la story
EN TANT QUE Architecte logiciel  
JE VEUX Définir des critères de sélection technique et justifier le choix retenu  
AFIN DE montrer que la technique sert le besoin et non l'inverse

## Critères d'acceptation
- [x] au moins 3 critères de sélection sont définis comme maintenabilité, testabilité, rapidité d'apprentissage ou déploiement
- [x] le choix retenu est justifié face à au moins 2 alternatives crédibles
- [x] la justification reste valable sans imposer une stack spécifique
- [x] les impacts sur qualité, sécurité et CI/CD sont pris en compte

---

### 1. Critères de Sélection (Scorecard)

Pour ce projet, les décisions ont été guidées par quatre piliers :

1.  **Vitesse de Prototypage (Velocity)** : Capacité à passer d'une idée à un composant réactif en quelques minutes.
2.  **Cohérence du Contexte (Single Context)** : Réduire la charge mentale en utilisant un minimum de langages différents (Maîtrise du PHP de bout en bout).
3.  **Maintenabilité (DX)** : Facilité de refactorisation et clarté de la séparation des responsabilités.
4.  **Performance perçue** : Fournir une interface "Elite HUD" sans les temps de chargement des Single Page Applications (SPA) classiques.

### 2. Choix de la Stack : TALL (Tailwind, Alpine, Laravel, Livewire)

Le choix s'est porté sur l'écosystème Laravel pour sa puissance "out-of-the-box".

*   **Pourquoi TALL ?** : Elle permet de créer des interfaces riches et dynamiques (Livewire) tout en conservant la simplicité d'un rendu côté serveur (SSR). Pas de gestion de state management complexe (Redux) ni de routage double (React Router + Backend).
*   **Justification vs Alternatives** :
    *   **vs MERN (Mongo, Express, React, Node)** : Rejeté car nécessite deux codebases séparées, un typage souvent incohérent entre front et back, et une complexité de déploiement supérieure pour un bénéfice minime sur ce type de projet.
    *   **vs Symfony + React** : Rejeté car plus rigide et verbeux. Laravel offre une "Developer Experience" supérieure pour le développement rapide (Eloquent, Actions atomiques).

### 3. Impacts Architecturaux

*   **Qualité & Testabilité** : L'usage de Laravel facilite les tests d'intégration (Pest/PHPUnit) car le frontend (Livewire) peut être testé directement en PHP, garantissant un taux de couverture élevé sans outils d'E2E lourds.
*   **Sécurité** : Protection native contre les failles CSRF, XSS et SQL Injection. L'usage de Policies centralisées (US14) assure une sécurité granulaire.
*   **CI/CD** : Intégration fluide avec GitHub Actions grâce à la légèreté des dépendances PHP/Node, permettant des validations de linting (Pint/ESLint) et de tests en moins de 3 minutes.

### 4. Conclusion
La stack choisie est un équilibre parfait entre robustesse "entreprise" (Laravel) et agilité "startup" (Livewire/Alpine), parfaitement adaptée au cadre d'un projet étudiant à forte itération.
