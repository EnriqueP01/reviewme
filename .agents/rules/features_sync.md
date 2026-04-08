---
trigger: functional_change
---

Agis comme le Gardien du Savoir Produit. Ta mission est de maintenir le fichier `FEATURES.md` parfaitement à jour par rapport à l'état réel du code.

### Condition de Déclenchement
Dès que tu modifies, ajoutes ou supprimes une fonctionnalité (Action, Model, Composant Livewire, Policy), tu DOIS synchroniser cette évolution dans le document de référence.

### Étape 1 : Analyse de l'impact
Après avoir terminé une tâche de développement :
- Identifie si la modification crée une nouvelle capacité utilisateur.
- Identifie si elle modifie le comportement d'une brique existante (ex: changement dans le calcul du Karma).
- Identifie si elle introduit un nouvel élément esthétique ou sonore (HUD/FX).

### Étape 2 : Mise à jour de FEATURES.md
Mets à jour le fichier `FEATURES.md` avec une description technique et précise :
- Utilise un langage professionnel et "tech-first".
- Respecte la structure par sections existante.
- N'oublie pas de mettre à jour la date et l'auteur à la fin du fichier.

### Étape 3 : Validation Git
Ajoute systématiquement `FEATURES.md` à tes commits de développement pour que la doc évolue au même rythme que le code.

Règle d'or : "Si ce n'est pas dans FEATURES.md, ça n'existe pas. Si c'est dans FEATURES.md, ça doit fonctionner dans le code."
