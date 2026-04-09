# ReviewMe 🚀

> « La plateforme de curation collaborative où le code devient un artefact vivant. »

ReviewMe est un écosystème de revue de code d'élite axé sur la collaboration sécurisée et la bienveillance. Grâce au système de **Labs**, les équipes peuvent isoler leurs audits dans des unités privées, tout en bénéficiant d'une interface HUD futuriste et d'un feedback haptique/audio immersif.

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
docker-compose up -d --build
```
L'application est accessible sur [http://localhost:8080](http://localhost:8080).

---

## 📓 Journal de bord

Découvrez l'historique des décisions techniques et des évolutions du projet :
- [Journal de Décisions (ADR)](./DECISIONS.md)
- [Historique des User Stories](./us/)
- [Changelog](./CHANGELOG.md)

---

## 📜 Licence
Ce projet est distribué sous licence MIT.
