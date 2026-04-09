# [Architecture] Analyse de Sécurité du Code et des Accès (US38)

**Acteur** : Architecte Logiciel
**Objectif** : Identifier les vulnérabilités, durcir les accès et assurer la traçabilité des incidents.

## 🛡️ Inventaire des Risques Applicatifs

| Risque | Description | Statut MVP | Mesure appliquée |
| :--- | :--- | :--- | :--- |
| **IDOR** | Accès non autorisé à un Post privé via l'ID. | 🔴 Faible | Utilisation de **Laravel Policies** pour chaque accès. |
| **XSS** | Injection de script via le contenu des Snippets. | 🟠 Mitigé | Échappement automatique (Blade/Livewire) + `e()` en DB. |
| **Secrets** | Exposition des clés GitHub / Reverb. | 🟢 Sécurisé | Variables confinées dans `.env` (exclu du Git). |
| **Debug** | Fuite d'informations via `APP_DEBUG=true`. | 🟠 Risque | Warning ajouté dans `OPERATIONS.md`. |
| **Injections** | SQL Injection via les paramètres de recherche. | 🟢 Sécurisé | Utilisation systématique de l'ORM Eloquent (Query Binding). |

---

## 🏗️ Zones Sensibles Auditées

1.  **Gestion des Labs (Groups)** : Vérification de l'isolation stricte entre membres et non-membres.
2.  **Workflow de Publication** : Validation des entrées utilisateur pour éviter le déni de service (DoS) par de trop gros fichiers.
3.  **Actions Métier** : Audit des transactions pour garantir l'intégrité (tout ou rien).

---

## 🛠️ Mesures de Réduction de Risque (US38)

### 1. Durcissement des ACL (PostPolicy)
- **Problème** : Les membres d'un groupe ne pouvaient pas voir les posts du groupe s'ils n'en étaient pas les auteurs.
- **Action** : Correction de la logique pour inclure `$user->isMemberOf($post->group)`.

### 2. Validation Défensive (Actions)
- **Problème** : Confiance excessive dans la validation frontend.
- **Action** : Ajout d'un second niveau de validation (`Validator`) directement dans les Actions PHP.

### 3. Traçabilité & Forensics
- **Problème** : Tentatives d'intrusion silencieuses.
- **Action** : Logging des tentatives d'accès refusées (`Log::warning`) avec contexte (User ID, IP, Ressource).

---

## ⚠️ Limites Connues & Hors-Périmètre
- **Brute Force** : Laravel standard throttle utilisé sur le login, mais pas de 2FA implémenté dans le MVP.
- **CSRF** : Actif par défaut, mais nécessite une vigilance lors de l'exposition d'endpoints API purs (hors Livewire).
- **Rate Limiting** : Limité au login. Pas de limitation sur la création de commentaires (Vitesse humaine attendue).
