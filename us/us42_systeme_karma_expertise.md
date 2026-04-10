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
- [x] **Interaction UX & Feedback** : Affichage de notifications Toast (vibe-notif) en cas d'action bloquée par le Karma pour informer l'utilisateur du privilège manquant.

## 🛠 Détails Techniques
- **Audit Logging** : Toute modification de points DOIT passer par la table `karma_transactions`. Interdiction de modifier `reputation_score` directement sans traceur.
- **Expertise par Lens** : Le système `UserSkill` stocke le cumul par slug (ex: `security`, `logic`).
- **Validation Middleware** : `EnsureUserHasKarma` supporte le format `karma:min_score` ou `karma:permission_name`.
- **Consolidation UI (Anti-Erreur)** : Migration de la logique `vote` vers Alpine (`handleVote`) pour éviter les erreurs Livewire 3 sur les attributs vides et assurer un feedback immédiat (Audio/Haptique).
- **Calcul de Qualité** : Utilise `strlen()` sur la source pour appliquer le multiplicateur x2.
- **Sync Integrity** : La commande `karma:rebuild` utilise `chunkById` pour traiter les gros volumes de transactions sans crash mémoire.

## 🔗 Liens
- DECISION_2026_04_09_57 (Barème de points et Équilibrage)
- AD-005 (Sécurisation UI par Toasts)
- AD-004 (Consolidation de la logique de vote)
- `config/karma.php` (Configuration des paliers)
