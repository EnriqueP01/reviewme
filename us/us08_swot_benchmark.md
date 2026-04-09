# [Cadrage] US08 - Réaliser une SWOT et un benchmark minimal

## Description de la story
EN TANT QUE Product Owner  
JE VEUX Comparer le projet à l'existant et analyser ses forces, faiblesses, opportunités et menaces  
AFIN DE préparer une défense crédible des choix et éviter les idées hors sol

## Critères d'acceptation
- [x] une SWOT complète est produite avec au moins 2 éléments par quadrant
- [x] au moins 3 références comparables ou approches alternatives sont étudiées
- [x] au moins 2 décisions de backlog ou de MVP découlent de cette analyse
- [x] l'analyse reste spécifique au projet et ne se contente pas de banalités

---

### 1. Analyse SWOT

| **Forces** | **Faiblesses** |
| :--- | :--- |
| **Interface HUD Premium** : Expérience utilisateur immersive et valorisante pour les développeurs. | **Base Utilisateur Nulle** : Plateforme vide au démarrage nécessitant une phase critique d'adoption. |
| **Spécificité Éducative** : Contrairement à GitHub, l'outil est pensé pour l'apprentissage, pas seulement pour la production. | **Maintenance Frontend** : L'utilisation massive de Livewire/Alpine nécessite une rigueur technique élevée pour éviter la dette. |
| **Opportunités** | **Menaces** |
| **Partenariats Écoles** : Possibilité d'intégrer l'outil dans le cursus officiel des écoles d'ingénieurs. | **GitHub PRs** : Le concurrent par défaut qui est déjà l'outil standard du marché. |
| **Curation Thématique** : Devenir une bibliothèque de "meilleures pratiques" pour des fragments de code spécifiques. | **IA Générative** : Les outils comme ChatGPT peuvent donner des retours instantanés, diminuant la motivation pour la revue humaine. |

### 2. Benchmark Minimal

1.  **GitHub Pull Requests** :
    - *Points forts* : Standard de l'industrie, lié au versioning.
    - *Points faibles* : Trop complexe pour des petits fragments isolés, interface purement utilitaire et peu orientée "pédagogie".
2.  **Exercism** :
    - *Points forts* : Feedback humain par des mentors.
    - *Points faibles* : Limité à des exercices pré-définis (Katas), pas de support pour les projets libres des étudiants.
3.  **Stack Overflow** :
    - *Points forts* : Base de connaissance immense.
    - *Points faibles* : Toxicité fréquente envers les débutants, pas d'espaces privés pour les classes.

### 3. Décisions découlant de l'analyse

*   **Décision 1 (Anti-Toxicité)** : L'implémentation des **Groupes Privés** permet de contrer la menace de la toxicité des plateformes publiques classiques (benchmark Stack Overflow).
*   **Décision 2 (Focus Pédagogique)** : L'ajout des **Lenses (Logic/Opti/Beauty)** remplace le feedback générique par un cadre d'apprentissage structuré que ne propose pas GitHub.

### 4. Cohérence Backlog
Cette analyse a directement conduit à prioriser le "Workflow de Publication" multi-fichiers pour rivaliser avec la clarté des diffs GitHub tout en restant accessible pour des extraits de code.
