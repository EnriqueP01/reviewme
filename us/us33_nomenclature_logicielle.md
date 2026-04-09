# [Architecture] US33 - Contrôle des dépendances et production d'une SBOM

**Acteur** : Développeur / Lead Tech
**Objectif** : Inventorier, sécuriser et rationaliser les composants tiers du projet.

## 📦 Inventaire des Dépendances (SBOM)

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
| `axios` | ^1.11 | Appels HTTP API (fallback hors Livewire) | **Standard** |
| `concurrently` | ^9.0 | Exécution parallèle des serveurs de dev | **Dev tool** |

---

## 🧹 Nettoyage des Composants (Pruning)
Les packages suivants ont été identifiés comme redondants ou inutilisés et supprimés :
- `[SUPPRIMÉ]` **@tailwindcss/vite** : Tentative prématurée de passage à Tailwind v4 alors que le projet est stable sur la v3. Suppression de 17 sous-dépendances associées.

---

## 🛡️ Audit de Sécurité
- **PHP Audit** : `php composer.phar audit` -> **Passé (0 vulnérabilité)**.
- **JS Audit** : `npm audit` -> **Passé (0 vulnérabilité)**.

## 📜 Procédure de Génération SBOM
Pour générer le fichier `sbom.json` au format standard **CycloneDX**, exécutez le script suivant :
```bash
bash scripts/generate-sbom.sh
```
Ce script consolide les métadonnées de `composer.lock` et `package-lock.json`.
