# [Qualité] US28 - Définir les règles de qualité de code et de commentaires utiles

## Description de la story
EN TANT QUE Développeur  
JE VEUX Établir un socle de lisibilité, de maintenabilité et de documentation pertinente du code  
AFIN DE limiter la dette technique générée par le vibe coding

## Critères d'acceptation
- [x] les règles portent au minimum sur lisibilité, duplication, complexité et séparation des responsabilités
- [x] les commentaires décoratifs ou mensongers sont interdits
- [x] les zones complexes ou non intuitives du projet sont identifiées et commentées utilement
- [x] tout nouveau code doit respecter ces règles ou documenter une exception

---

### 1. Principes Fondamentaux de Qualité

ReviewMe suit trois règles d'or pour la pérennité du code :

1.  **Architecture orientée "Actions"** : Toute logique métier dépassant 3 lignes dans un composant Livewire doit être extraite dans une classe `Action` (ex: `AddPostVersionAction`).
2.  **Séparation Inter-Couches** : Les modèles (Eloquent) gèrent les données, les Actions gèrent le métier, et les composants Livewire gèrent l'état de l'interface.
3.  **DRY (Don't Repeat Yourself)** : Extraction systématique des fragments Blade répétés dans `resources/views/components/ui`.

### 2. Standards de Code & Outillage

| Outil | Standard | Action |
| :--- | :--- | :--- |
| **Laravel Pint** | PSR-12 / Laravel | Auto-formatage du PHP. |
| **Larastan** | Level 5 | Analyse statique pour détecter les bugs potentiels de typage. |
| **ESLint / Prettier** | Standard JS | Formatage et qualité du code AlpineJS. |
| **Tailwind CSS** | Design Tokens | Utilisation exclusive des classes utilitaires (pas de CSS "magic numbers"). |
| **Audit Sécurité** | Composer/NPM | Vérification systématique des vulnérabilités (Score actuel: 0 faille). |

### 3. Stratégie de Commentaires

- **Interdiction du superflu** : Ne pas commenter ce que le code dit déjà (ex: `// Incremente i`).
- **Commentaires de "Pourquoi"** : On documente les décisions complexes (ex: pourquoi tel algorithme de tri MD5 a été choisi plutôt qu'un autre).
- **Docstrings atomiques** : Chaque méthode publique d'une Action doit avoir un court bloc de commentaire expliquant ses entrées et ses effets de bord.

### 4. Application Continue
Le non-respect de ces règles (présence de `dd()`, `console.log` oubliés, ou code trop complexe non commenté) bloque systématiquement le passage de la pipeline CI/CD de ReviewMe.
