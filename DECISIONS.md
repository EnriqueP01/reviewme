# Journal de Décisions - ReviewMe

## 2026-04-08 : Implémentation Core ReviewMe

### Choix Technique : GitHub Login Obligatoire
**Décision** : Utiliser uniquement GitHub OAuth pour l'authentification.
**Raison** : Plateforme dédiée aux développeurs, simplifie la gestion des avatars et pseudos, et renforce l'aspect communautaire tech.

### Choix Technique : Stockage SQLite (Local)
**Décision** : Utilisation temporaire de SQLite pour le développement local.
**Raison** : L'extension `pdo_pgsql` n'est pas activée sur l'environnement actuel de l'utilisateur. PostgreSQL reste la cible pour la production.

### Choix Architecture : Versioning via Snippets
**Décision** : Séparer les `snippets` du modèle `Post`.
**Raison** : Permet de conserver l'historique des modifications de code (V1, V2...) sans dupliquer les métadonnées du post original.

### Choix UI : Mode Sombre Monolith
**Décision** : Forcer le mode sombre par défaut avec une palette Monolith (Surface #12131b).
**Raison** : Préférence majeure de la cible (développeurs) et esthétique premium immédiate.

## 2026-04-08 (Antigravity Ops) : Sécurisation GitHub & Clean Auth
### Choix Technique : Auto-Vérification via GitHub
**Décision** : Marquer automatiquement les emails comme vérifiés lors de l'authentification GitHub.
**Raison** : GitHub a déjà vérifié l'email, cela évite une étape redondante et fluide l'onboarding vers le Dashboard.

### Choix UI : Rationalisation de la page Login
**Décision** : Masquer le formulaire standard par défaut sous un bouton "Accès Démo" et désactiver les routes `/register`.
**Raison** : En accord avec l'exclusivité GitHub décidée, cela guide l'utilisateur vers le tunnel principal tout en gardant une porte de secours pour le debugging et les leads.
