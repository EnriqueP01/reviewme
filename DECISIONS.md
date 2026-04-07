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
