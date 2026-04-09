# ReviewMe 🚀

> « La plateforme de revue de code collaborative professionnelle. »

ReviewMe est un écosystème de revue de code de haut niveau axé sur la collaboration sécurisée et l'excellence technique. Grâce au système de **Groupes**, les équipes peuvent organiser leurs revues dans des espaces privés, tout en bénéficiant d'une interface HUD optimisée et d'une expérience utilisateur immersive.

---

## 📚 Centre de Connaissance

Pour comprendre, installer et maintenir le projet, consultez les guides dédiés :

1.  **[Démarrage & Installation (Local/Docker)](./docs/INSTALL.md)**
2.  **[Guide d'Exploitation & Maintenance](./docs/OPERATIONS.md)**
3.  **[Architecture & Conception (Modèles/Flux)](./docs/ARCHITECTURE.md)**
4.  **[Qualité, Tests & Sécurité (Sonar/SBOM)](./docs/QUALITY_SECURITY.md)**
5.  **[Guide du Contributeur (Workflow Git)](./docs/CONTRIBUTING.md)**

---

## 🏗️ Stack Technologique (Elite HUD Stack)

- **Backend** : Laravel 11 / PHP 8.3
- **Frontend** : Livewire 3 / AlpineJS / Tailwind CSS
- **Realtime** : Laravel Reverb (WebSockets)
- **Qualité** : SonarQube / PHPUnit / CycloneDX (SBOM)

---

## ⚡ Démarrage Express (Développement)

Si vous avez déjà configuré vos pré-requis, lancez simplement :

```bash
php composer.phar dev
```
Ceci démarre le serveur Laravel (`8000`) et Vite.

---

## 🐳 Docker Mode

```bash
# Lancer l'environnement
docker-compose up -d --build

# Arrêter les services
docker-compose stop
```
L'application est accessible sur [http://localhost:8080](http://localhost:8080).

---

## 📓 Journal de bord

Découvrez l'historique des décisions techniques et des évolutions du projet :
- [Journal de Décisions (ADR)](./DECISIONS.md)
- [Historique des User Stories](./us/)
- [Changelog](./CHANGELOG.md)

---

## 🧬 Système de Groupes & Revues
1.  **Détails** : Définition des buts de revue et du contexte technique.
2.  **Fichiers** : Import multi-fichiers, détection automatique de langage et annotations.
3.  **Distribution** : Publication publique ou restreinte à un **Groupe** spécifique.
4.  **Revue** : Analyse ligne à ligne avec focus spécialisés (Clarity, Security, Performance).

---

## 🎖️ Système de Réputation & Expertise (Karma)
La plateforme intègre une méritocratie technique basée sur le **Karma** :
- **Karma Catégorisé** : Chaque interaction développe vos **Skills** dans des domaines précis (Security, Performance, Logic).
- **Paliers de Progression** : Devenez un **Reviewer Certifié** (100 Karma) pour accéder à la gestion de groupes ou un **Elite Member** pour influencer la roadmap.
- **Audit Total** : Chaque point est historisé pour une transparence absolue sur l'expertise des contributeurs.
- **Bonus de Qualité** : Le système récompense automatiquement les analyses détaillées et constructives.

---

## 📋 Commandes de l'Agent Antigravity

La plateforme est maintenue par des protocoles robotiques stricts :

| Commande | Action | Usage |
| :--- | :--- | :--- |
| `/sync` | **Synchronisation** | Pull, install, migrations et nettoyage. |
| `/lint` | **Qualité Code** | Auto-correction Laravel Pint & ESLint. |
| `/test` | **Validation** | Exécution des tests et réparation auto. |
| `/audit` | **Sécurité/SEO** | Diagnostic complet perf et sécurité. |
| `/upgrade`| **Amélioration** | Analyse de dette technique et propositions. |
| `/commit`| **Push** | Commit conventionnel après vérification qualité. |

---

## 🛠️ Maintenance & Diagnostics
```powershell
# Exécuter les tests de sécurité des Groupes
php artisan test tests/Feature/GroupSecurityTest.php

# Réinitialiser l'écosystème complet (Migrations + Global Seeders)
php artisan migrate:fresh --seed

# Initialiser uniquement les données de test avancées (Master, Groupes, Conversations)
php artisan db:seed --class=MasterTestSeeder

# Nettoyer les résidus de déploiement
php artisan optimize:clear
```

---

## 📜 Charte de Développement
*   **Workflows Atomiques** : Une branche par fonctionnalité. **Interdiction de push sur `main`**.
*   **Traçabilité** : Chaque décision technique est consignée dans [DECISIONS.md](./DECISIONS.md).
*   **Langue** : Français obligatoire pour les rapports et documentations.
*   **KISS** : Simplicité, performance et robustesse.

---

## 🚀 Pipeline CI/CD (GitHub Actions)

Le projet intègre une pipeline d'intégration continue automatisée qui s'exécute à chaque push et Pull Request pour garantir l'intégrité du code.

### Vérifications effectuées :
1.  **Tests Automatisés** : Exécution de la suite de tests Pest/PHPUnit.
2.  **Style PHP (Pint)** : Vérification de la conformité aux standards Laravel/PSR-12.
3.  **Qualité JS (ESLint/Prettier)** : Validation du code frontend et du formatage.
4.  **Analyse Statique (Larastan)** : Détection proactive de bugs via PHPStan (Niveau 5).

> [!IMPORTANT]
> Tout échec sur l'un de ces points bloque la validation de la Pull Request. Le code doit être corrigé localement via `/lint` ou manuellement avant d'être re-soumis.

---

## 📜 Licence
Ce projet est distribué sous licence MIT.
