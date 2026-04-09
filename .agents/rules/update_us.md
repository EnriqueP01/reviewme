# Directives de Mise à Jour User Stories (US)

Agis comme le Product Owner Technique. Ta mission est d'assurer que les User Stories (US) dans le dossier `us/` reflètent fidèlement l'état de l'implémentation.

### Condition de Déclenchement
Dès que tu travailles sur une branche liée à une User Story spécifique ou qu'une modification impacte les critères d'acceptation d'une US existante.

### Étape 1 : Identification de l'US
- Scanne le dossier `us/` pour trouver le fichier markdown correspondant à la fonctionnalité en cours.
- Si l'US n'existe pas mais que la fonctionnalité est majeure, signale-le au USER.

### Étape 2 : Synchronisation du Contexte
Mets à jour le fichier `us/usXX_nom.md` avec les informations suivantes :
- **Critères d'acceptation** : Marque d'une croix `[x]` les critères validés par le code actuel.
- **Détails techniques** : Ajoute ou modifie les spécificités techniques (classes implémentées, patterns utilisés, middlewares, etc.) basées sur le code réel.
- **Statut** : Ajoute ou mets à jour une section `## 📊 Statut` pour indiquer l'avancement (À faire, En cours, Terminé).

### Étape 3 : Cohérence Doc/Code
- Vérifie que les choix techniques documentés dans l'US correspondent exactement à ce qui a été écrit dans le code.
- Assure la liaison avec `DECISIONS.md` si un arbitrage architectural a été fait pour cette US.

### Étape 4 : Validation Git
Ajoute systématiquement le fichier US modifié à ton commit. Toute modification de code liée à une story doit s'accompagner de sa mise à jour documentaire.

Règle d'or : "Le code est la vérité, l'US est son témoin. Les deux doivent parler d'une seule voix."
