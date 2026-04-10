# Analyse de Sécurité du Code et des Accès

**Acteur** : Architecte Logiciel  
**Objectif** : Identifier les vulnérabilités, durcir les accès et assurer la traçabilité des incidents conformément aux standards de qualité du projet.

## 🛡️ 1. Inventaire des Risques Applicatifs

| Risque | Description | Mesure Appliquée | Statut |
| :--- | :--- | :--- | :--- |
| **IDOR** | Accès non autorisé à un Post privé via manipulation d'ID. | **Policies Laravel** : Vérification systématique de l'appartenance au groupe ou à l'auteur. | 🟢 Traité |
| **XSS** | Injection de script via les Snippets de code. | **Échappement automatique** via Blade et fonction `e()`. Utilisation de `@js` pour les données transmises. | 🟢 Traité |
| **Abus / Spam** | Création massive de contenus ou votes malveillants. | **Karma-RBAC** : Les actions sensibles (Downvote, Modération) nécessitent un score de réputation minimum. | 🟢 Traité |
| **Secrets** | Fuite de clés API (GitHub, Reverb) dans le code. | **Ségrégation .env** : Fichier exclu du Git, intégré via variables d'environnement Docker. | 🟢 Traité |
| **Usurpation** | Modification du Handle utilisateur pour tromper l'identité. | **Unique Handles** : Validation stricte et unicité forcée en base de données avec filtrage alpha-dash. | 🟢 Traité |

---

## 🏗️ 2. Zones Sensibles Auditées

1.  **Isolation des Groupes (Labs)** : Les données de curation privée ne sont jamais exposées hors du cercle des membres autorisés.
2.  **Workflow de Publication** : Validation multiniveau (Frontend/Backend) pour garantir l'intégrité des fichiers importés.
3.  **Transactions de Karma** : Utilisation de `DB::transaction()` et `increment/decrement` atomiques pour prévenir les erreurs de scoring concurrentes.
4.  **Points d'entrée OAuth** : Sécurisation du callback GitHub avec masquage des erreurs système ($e->getMessage()) pour éviter les fuites d'info techniques.

---

## 🛠️ 3. Mesures de Réduction de Risque

### A. Contrôle d'Accès Défensif (Policies & Audit)
Chaque tentative d'accès à une ressource est vérifiée par une `Policy`. En cas de refus, un log de niveau `warning` est généré (`Log::warning("[UNAUTHORIZED_ACCESS] User {id} tried to view Post {id}")`) pour permettre une analyse forensics ultérieure.

### B. Barrière de Qualité Statique (SonarQube)
L'intégration d'une **Quality Gate** SonarQube dans la pipeline CI/CD bloque le merge de toute branche introduisant des vulnérabilités connues (OWASP Top 10) ou des "Security Hotspots" non revus.

### C. Méritocratie Technique (Karma-RBAC)
Le système de Karma n'est pas qu'un outil de gamification, c'est une barrière de sécurité : il empêche les nouveaux comptes non vérifiés de polluer la plateforme par des downvotes massifs ou des revues de faible qualité.

---

## ⚠️ 4. Limites Connues (MVP)

- **Authentification Multi-Facteurs (2FA)** : Non implémenté dans le MVP. La sécurité repose sur la fiabilité du fournisseur OAuth (GitHub).
- **Audit Logs Complets** : Seules les tentatives d'accès refusées sont logguées. Un audit trail de modification (qui a changé quoi) n'est pas encore présent.
- **Rate Limiting Granulaire** : Le throttle est actif sur le login, mais des attaques subtiles de type "slow-spam" sur les commentaires restent théoriquement possibles.
- **SQL Injection (Limites)** : Entièrement protégé par Eloquent, mais vigilance requise pour toute future utilisation de `DB::raw()`.
