# [Soutenance] US39 - Justifier les arbitrages, compromis et priorités du backlog

## Description de la story
EN TANT QUE Product Owner  
JE VEUX Préparer une explication structurée des choix, renoncements et ordres de priorité  
AFIN DE prouver que le backlog n'est pas une liste arbitraire mais un outil de pilotage

## Critères d'acceptation
- [x] au moins 5 arbitrages majeurs sont expliqués avec bénéfice attendu et coût associé
- [x] les compromis entre délai, qualité, sécurité, périmètre et ergonomie sont explicités
- [x] les stories prioritaires sont reliées au problème, aux personas et au MVP
- [x] les stories repoussées ou non réalisées sont expliquées par des critères clairs comme valeur, risque ou coût

---

## 1. Arbitrages Majeurs du Projet

| Arbitrage | Choix Retenu | Bénéfice Attendu | Coût / Risque |
| :--- | :--- | :--- | :--- |
| **Stack Technique** | **TALL Stack** (Livewire) | Vitesse de développement (Vibe Coding) et Single Source of Truth. | Moins de réactivité client "pure" qu'une SPA React/Vue. |
| **Architecture** | **Modular Monolith** | Simplicité de déploiement et maintenance. Absence de latence réseau entre micro-services. | Scalabilité horizontale plus complexe des composants isolés. |
| **Authentification** | **GitHub OAuth Unique** | Sécurisation immédiate (Provider tiers) et garantie du persona cible (Développeur). | Exclusion des utilisateurs n'ayant pas de compte GitHub. |
| **Pattern Métier** | **Actions Pattern** | Testabilité à 100% et réutilisation de la logique (CreatePostAction utilisée partout). | Légère surcharge de code (Boilerplate) par rapport aux contrôleurs classiques. |
| **Design System** | **High-Density UI** | Esthétique "Pro-grade" / IDE renforçant la crédibilité de l'outil pour les experts. | Courbe d'apprentissage plus raide pour les novices. |

---

## 2. Matrice des Compromis (Compromise Matrix)

Pour tenir les dates de livraison tout en maximisant la valeur, la stratégie de compromis suivante a été appliquée :

1.  **Délai vs Périmètre (Optimisation)** : Le retrait de l'IA (périmètre complexe) a été le pivot permettant de sécuriser les fonctionnalités à "haute densité" initialement menacées (Suggestions Inline, Heatmap). Cette efficacité a même permis la réintégration du **Chat Instantané**.
2.  **Qualité vs Ergonomie** : Priorité absolue au moteur de revue (Qualité technique) sur les fioritures visuelles inutiles, assurant une expérience "Pro-grade".
3.  **Sécurité vs Rapidité** : Utilisation exclusive des standards natifs Laravel (verified via US33) plutôt que le développement d'une couche crypto sur-mesure coûteuse en temps.

---

## 3. Priorisation & Alignement Stratégique

Les stories suivantes ont été placées en **Haute Priorité** car elles répondent directement au "Pain Point" de notre persona **Lucas** (Étudiant dév) :

- **Publish Workflow (US04/US09)** : Lucas doit pouvoir partager du code fragmenté instantanément pour obtenir de l'aide.
- **Inline Suggestions (US13)** : La précision du feedback est l'élément qui différencie ReviewMe d'un simple chat Discord/Slack.

---

## 4. Backlog Repoussé & Réintégrations

Certaines fonctionnalités ont été arbitrées selon des critères de valeur, risque et coût :

- **Analyse IA (Retirée)** : Trop complexe à fiabiliser dans le temps imparti. Risque de faux positifs élevé dégradant la confiance dans les revues.
- **Application Mobile (Retirée)** : Lucas code sur laptop. Une version mobile n'apporte que très peu de valeur pour un hub de revue de code technique.
- **Chat Instantané (Réintégré)** : Initialement hors périmètre, cette fonctionnalité a été réintégrée par opportunité architecturale (usage de Laravel Reverb), offrant une valeur collaborative immédiate sans surcoût de délai majeur.
- **Multi-Provider Auth (Annulée)** : L'implémentation de Google/Apple auth n'apporte pas de nouveaux utilisateurs dans la cible "Développeur" et a été écartée au profit de la qualité du moteur de suggestions.
