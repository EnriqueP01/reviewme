# User Story 42 : Système de Karma & Expertise Thématique

## 📄 Description
**En tant que** membre de la plateforme ReviewMe,
**Je veux** que mes contributions (posts, reviews, commentaires) soient valorisées par un système de réputation et de compétences,
**Afin de** débloquer de nouveaux privilèges, de démontrer mon expertise dans des domaines spécifiques (Lenses) et de garantir la qualité des échanges communautaires.

## 🎯 Critères d'Acceptation
- [x] **Persistance des points** : Chaque gain ou perte de Karma doit être enregistré dans une table de transactions à des fins d'audit.
- [x] **Expertise par Lens** : Le Karma doit être catégorisé selon la thématique du post (Security, Performance, Logic, etc.) pour créer un profil de compétences (Skills).
- [x] **Niveaux de Progression** : Des rangs automatiques doivent être attribués (Apprenti, Contributeur, Reviewer, Expert, Elite).
- [x] **Privilèges RBAC** : Certaines actions (Downvote, Création de groupes) doivent être restreintes par le score de Karma.
- [x] **Anti-Abus** : Un plafond de gain quotidien (Daily Cap de 200 pts) doit être en place.
- [x] **Dashboard de Réputation** : Le profil utilisateur doit afficher le badge de rang, la grille d'expertise et l'historique des gains.
- [x] **Feedback Qualité** : Les contenus longs et détaillés (> 500 chars) doivent bénéficier d'un bonus multiplicateur de points.

## 🛠 Détails Techniques
- **Modèles** : `KarmaTransaction` (polymorphique), `UserSkill`.
- **Moteur** : `GrantKarmaAction` (Pattern Action centralisé).
- **Middleware** : `EnsureUserHasKarma` pour la protection des routes.
- **Maintenance** : Commande Artisan `karma:rebuild`.
- **UI** : Composant Livewire `Profile` enrichi avec des états réactifs `karma-updated`.

## 🔗 Liens US
- US14 (Rôles & Permissions)
- US33 (Analyse de Sécurité)
- DECISION_2026_04_09_57
