# User Story : Gestion de l'Identité et Profils Publics (US45)

## 🎯 Objectif
Permettre aux utilisateurs de définir une identité unique (Handle) et personnelle (Biographie) tout en facilitant le partage et la consultation de profils experts via des URLs dédiées.

## 🛠 Spécifications Techniques

### 1. Structure de Données
- **Champ `handle`** : Identifiant alphanumérique unique (ex: `@dev_expert`). Utilisé comme clé de routage.
- **Champ `name`** : Pseudo d'affichage non-unique (Display Name).
- **Champ `bio`** : Texte libre (longueur limitée à 255-500 caractères) pour la présentation de l'utilisateur.

### 2. Système de Routage
- **Route Publique** : `/profile/{handle}` (Accessible en lecture seule pour les tiers).
- **Redirection Intelligente** : La route `/profile` redirige automatiquement l'utilisateur authentifié vers son propre profil `/profile/{{auth_handle}}`.
- **Navigation Livewire** : Intégration de `wire:navigate` pour une navigation instantanée (SPA style) entre les profils.

### 3. Automatisation & Inscription
- **GitHub Sync** : Lors de la première connexion via OAuth, le `handle` est automatiquement généré à partir du nickname GitHub pour garantir une identité immédiate.
- **Migration de Masse** : Peuplement automatique des handles pour les utilisateurs existants lors du déploiement.

### 4. Interface Utilisateur (UI)
- **Profile Header** : Affichage distinct du pseudo (grand) et du handle (style monospace avec `@`).
- **Gestion d'État** : Indicateur visuel de l'état de la connexion GitHub (Connecté / Non-connecté).
- **Édition Directe** : Formulaire de mise à jour intégré dans les réglages permettant la modification atomique de l'identité.

## ✅ Critères d'Acceptation
- [x] Un utilisateur peut accéder à son profil via son handle unique.
- [x] Un utilisateur peut modifier son pseudo, son handle et sa bio.
- [x] Le handle doit être unique et comporter uniquement des caractères alphanumériques et des tirets.
- [x] Tous les liens d'auteurs dans le Feed pointent correctement vers le profil public.
- [x] L'état de connexion GitHub est visible sur le profil.
