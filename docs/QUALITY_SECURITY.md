# 🛡️ Qualité & Sécurité

La robustesse et la traçabilité sont au cœur du projet ReviewMe. Ce document détaille les processus de validation continue.

## ✅ Stratégie de Tests

ReviewMe utilise **PHPUnit** (PHP) et **Vitest/Cypress** (JS - Optionnel pour MVP) pour assurer la non-régression.

### Exécuter les tests
```bash
# Lancer tous les tests unitaires et fonctionnels
php artisan test

# Lancer avec rapport de couverture (nécessite pcov ou xdebug)
php artisan test --coverage
```

### Couverture critique
Les zones suivantes sont couvertes à 100% :
- **Security Policies** : Accès restreint aux Labs privés.
- **Actions** : Logique de transaction de création de poste.
- **Authentification** : Flow GitHub OAuth.

---

## 🔍 Analyse Statique (SonarQube)

Le projet est intégré à **SonarQube** via GitHub Actions.

- **Fichier de config** : `sonar-project.properties`
- **Métrique cible** : 
  - Code Coverage > 80% (recommandé).
  - 0 Vulnérabilités critiques.
  - Détection automatique de la duplication de code.

*Voir le workflow CI : [.github/workflows/sonar.yml](../.github/workflows/sonar.yml).*

---

## 📦 Gestion des Dépendances (SBOM)

Conformément aux standards de l'industrie, ReviewMe produit une **Software Bill of Materials (SBOM)** pour assurer la traçabilité de sa supply chain.

- **Standard** : CycloneDX (Format JSON).
- **Audit de sécurité** :
  ```bash
  composer audit # Vérifie les failles PHP
  npm audit      # Vérifie les failles JS
  ```
- **Régénération** : Le script `scripts/generate-sbom.sh` concatène les inventaires Composer et NPM.

*Plus d'infos dans l'US33 : [Nomenclature Logicielle](../us/us33_nomenclature_logicielle.md).*

---

## 🔍 Analyse Statique & Linting

Le projet impose des barrières de qualité strictes pour éviter la dette technique :
- **Larastan** : Niveau **5** obligatoire. Toutes les propriétés dynamiques Eloquent sont documentées via PHPDoc.
- **Laravel Pint** : Standard **Laravel** (PSR-12 amélioré) imposé sur tous les fichiers PHP.
- **ESLint/Prettier** : Formatage automatique du JS et détection de bugs dans Alpine.js.

---

## 🛡️ Audit de Sécurité (US33)

Un audit complet a été réalisé, aboutissant aux mesures suivantes :
1.  **Anti-IDOR** : Chaque action (Review, Post, Group) est protégée par une `Policy` Laravel vérifiant les droits du porteur de token.
2.  **Anti-XSS** : Échappement systématique des snippets de code et filtrage des entrées via le validateur Laravel.
3.  **Audit des Dépendances** : Patch de sécurité appliqué sur `axios` (v1.15.0) suite à la détection d'une faille SSRF.
4.  **RBAC par Karma** : Les permissions sensibles (ex: downvote, création de groupes) sont liées dynamiquement au score de réputation de l'utilisateur.

---

## 🔓 Sécurité des Données

1.  **Chiffrement** : Les mots de passe (si utilisés hors OAuth) sont hachés via Argon2ID.
2.  **Transactions SQL** : Toutes les opérations critiques (Post + Snippets) sont enveloppées dans des `DB::transaction()` pour garantir l'atomicité.
3.  **Filtrage XSS** : Les contenus utilisateur (code Snippets) sont échappés via `e()` ou Blade `{{ }}` lors du rendu.
