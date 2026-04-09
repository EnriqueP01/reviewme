# Règle : Auto-PRD & Raffinement de Prompt

Cette règle définit le comportement de l'agent lors de la réception d'une demande fonctionnelle complexe. L'agent doit transformer le "vibe" du prompt utilisateur en une spécification technique structurée avant toute modification de code.

## 🛠️ Processus de Raffinement
Pour chaque nouvelle demande de fonctionnalité, l'agent génère un court bloc de spécification avant d'agir :

1.  **Context** : Résumé technique du besoin.
2.  **Architecture** : Liste des composants impactés (Models, Actions, Livewire).
3.  **Security/i18n** : Vérification des Policies et des clés de traduction nécessaires.
4.  **US Link** : Identification de la User Story à mettre à jour (règle `update_us`).

## 📜 Principes Appliqués
- **Zéro Analogie** : La spécification est purement technique.
- **Atomicité** : Si la demande est trop large, l'agent la découpe en étapes dans ce bloc.
- **Validation** : L'agent vérifie la conformité avec `global.md` et `architecture.md` dès cette étape.

## ⚡ Exception (Fast-Track)
L'agent ne doit **pas** appliquer ce format pour :
- Les corrections de bugs triviaux.
- Les commandes de maintenance (clearing cache, migration).
- Les questions purement informatives.
