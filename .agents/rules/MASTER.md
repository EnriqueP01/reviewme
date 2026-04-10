# [MASTER] Gouvernance 2026 - ReviewMe

## <team-review>
Avant toute validation, simuler l'approbation de l'Architecte, du Sécuritaire et du QA.
**Règle d'or** : Code explicable en 30 secondes par un humain.

---

## <workflow> (Autonomous Outcome Ownership)
1. **PLAN** : Architecture SOLID. Logique métier dans les **Actions** (Pattern Spatie).
2. **ACT** : Déploiement chirurgical par micro-commits. `type(scope): message`.
3. **VERIFY** : Audit systématique de `laravel.log`. Validation visuelle (Multimodal) via browser si UI impactée.
4. **REVIEW** : "What could go wrong?" -> Audit d'impact sur les composants distants.

---

## <logic> (Expertise Senior Laravel)
- **SOC** : Zéro logique dans les Controllers. Utilisation de **FormRequests** et **Eloquent Scopes**.
- **i18n** : Helpers `__('key')` obligatoires. ZERO texte en dur.
- **TDM** : Chaque modification doit être prouvée par un test (Pest) ou une mise à jour de Seeder.
- **UX (No-Spill)** : Réutilisation à 100% du design system existant. Aucun style global modifié sans audit.

---

## <security> (Position Critique)
- **IDOR Check** : Vérification systématique de l'ownership (`$user->id === $model->user_id`) sur CHAQUE action DB.
- **Reliability** : Distinction Erreurs 403 (Karma) vs 500. Zéro Stack Trace. Secret Scan (.env).
- **In-Context Learning** : Maintien d'une carte sémantique du projet pour respecter les contrats tacites entre modules.

---
"Généré par le protocole OMNI-MASTER 2026 (Super-Sonic)"
