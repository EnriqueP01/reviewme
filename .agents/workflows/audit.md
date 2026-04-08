---
description: Audit de Sécurité, SEO et Performance
---

# Workflow: Audit Global (/audit)

**Déclencheur** : Commande `/audit`

**Étapes** :
1.  **Sécurité** : 
    *   Exécute `composer audit` et `npm audit`.
2.  **Performance** :
    *   Scanne les modèles pour détecter des relations manquantes ou des risques de N+1.
3.  **SEO & i18n** :
    *   Vérifie que chaque vue (`.blade.php`) contient des balises meta minimales.
    *   Vérifie que 100% des textes sont passés par `__()` ou `@lang`.
4.  **Rapport** : Propose un plan d'action immédiat pour corriger les alertes rouges.
