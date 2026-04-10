# Contrôle des dépendances et production d'une SBOM

**Acteur** : Développeur / Lead Tech  
**Objectif** : Inventorier, sécuriser et rationaliser les composants tiers du projet pour garantir la traçabilité standard entreprise.

## 📦 1. Inventaire des Dépendances (SBOM)

### 🐘 PHP (Backend - Composer)
| Package | Version | Utilité | État |
| :--- | :--- | :--- | :--- |
| `laravel/framework` | ^11.0 | Cœur de l'application (Routing, ORM, etc.) | **Essentiel** |
| `laravel/socialite` | ^5.14 | Authentification via GitHub OAuth | **Critique** |
| `livewire/livewire` | ^3.5 | Dynamisme frontend sans JS complexe | **Léger / Essentiel** |
| `laravel/reverb` | ^1.0 | Serveur de WebSockets temps réel natif | **Performance** |
| `laravel/tinker` | ^2.9 | REPL Console pour le débugging | **Dev / Ops** |

### 🌍 JavaScript (Frontend - NPM)
| Package | Version | Utilité | État |
| :--- | :--- | :--- | :--- |
| `tailwindcss` | ^3.1 | Framework CSS de conception (Design System) | **Essentiel** |
| `alpinejs` | ^3.4 | Micro-framework pour l'interactivité UI | **Essentiel (Livewire)** |
| `vite` | ^8.0 | Bundleur d'assets ultra-rapide | **Build** |
| `axios` | ^1.15 | Appels HTTP API (**Patch de sécurité SSRF Appliqué**) | **Crucial** |
| `laravel-echo` | ^2.3 | Pont de communication WebSockets client | **Temps-Réel** |
| `pusher-js` | ^8.5 | Driver de transport pour Reverb/Echo | **Temps-Réel** |
| `concurrently` | ^9.0 | Exécution parallèle des serveurs de dev | **Dev tool** |

---

## 🧹 2. Nettoyage des Composants (Pruning)

- **Audit & Suppression** : Les packages redondants comme `@tailwindcss/vite` (tentative v4) ont été purgés pour maintenir une codebase légère et prévisible.
- **Justification** : Chaque ajout de package NPM/Composer fait désormais l'objet d'une revue pour limiter l'empreinte de la `node_modules` et de la `vendor`.

---

## 🛡️ 3. Audit de Sécurité & Vigilance

Le projet est audité systématiquement lors des phases de build :
- **PHP Audit** : `php composer.phar audit` -> **Passé (0 vulnérabilité)**.
- **JS Audit** : `npm audit` -> **Passé (0 vulnérabilité)**.
  - *Note : Correction de la faille GHSA-3p68-rc4w-qgx5 (Axios SSRF) effectuée par upgrade vers v1.15.0.*

---

## 📜 4. Procédure de Génération SBOM

Le fichier `sbom.json` au format standard **CycloneDX** est généré automatiquement via la pipeline ou manuellement :
```bash
# Générer la nomenclature consolidée
bash scripts/generate-sbom.sh
```
Ce script extrait les versions réelles installées depuis les fichiers `lock` pour garantir une traçabilité 100% fidèle à la production.
