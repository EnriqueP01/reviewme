# [Qualité] US32 - Intégrer SonarQube dans les vérifications du projet

## Description de la story
**EN TANT QUE** Développeur  
**JE VEUX** Mesurer la qualité statique du code avec SonarQube  
**AFIN DE** Rendre visibles les défauts de qualité et ancrer une logique standard entreprise

## 🎯 Critères d'Acceptation
- [x] L'analyse SonarQube s'exécute sur le projet via GitHub Actions (`sonar.yml`).
- [x] Les résultats sont exploitables via le fichier `sonar-project.properties`.
- [x] Un seuil de qualité (Quality Gate) est défini (Couverture > 80%, 0 Critical Vulns).
- [x] La pipeline CI/CD bloque le merge si la Quality Gate échoue (Strict enforcement).
- [x] L'analyse remonte la couverture de code PHP (Clover) et les résultats de tests (JUnit).

---

## 🛠️ Détails Techniques

### Architecture d'Analyse
L'intégration repose sur le trio :
1.  **Scanner** : `sonarsource/sonarqube-scan-action` dans GitHub Actions.
2.  **Configuration** : `sonar-project.properties` pour définir les sources (`app`, `routes`) et les exclusions (`vendor`, `storage`).
3.  **Collecte de Données** : Rapports `clover.xml` et `junit.xml` générés pendant l'étape de tests PHPUnit.

### Quality Gate (Seuils Entreprise)
Les seuils suivants sont appliqués pour valider une "Release Candidate" :
- **Coverage** : Minimum 80% sur le nouveau code.
- **Security Hotspots** : 100% traités.
- **Reliability** : Rang A (0 Bugs).
- **Maintainability** : Rang A (Dette technique < 5%).

### Intégration CI/CD
Le workflow `.github/workflows/sonar.yml` est synchronisé sur les branches `dev` et `main`. Il s'exécute après la réussite des tests unitaires pour garantir que les métriques sont basées sur un code fonctionnel.
