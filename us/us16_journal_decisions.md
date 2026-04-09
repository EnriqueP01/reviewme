# [Architecture] US16 - Tenir un journal des décisions produit et techniques

## Description de la story
EN TANT QUE Product Owner  
JE VEUX Tracer les décisions structurants et leurs justifications  
AFIN DE rendre les arbitrages défendables et éviter les choix opaques pris au fil des prompts

## Critères d'acceptation
- [x] chaque décision structurante comporte date, contexte, options envisagées et choix retenu
- [x] les décisions couvrent au minimum produit, architecture, sécurité ou déploiement
- [x] chaque décision mentionne un compromis accepté ou une conséquence
- [x] le journal est exploitable en soutenance pour expliquer les arbitrages

---

### 1. Structure du Journal (ADR)
ReviewMe adopte le format **ADR (Architecture Decision Records)** consigné dans le fichier racine [DECISIONS.md](file:///Users/Nolhan/Documents/reviewme/DECISIONS.md).

Chaque entrée suit un format strict :
- **ID & Titre** : Pour une identification rapide.
- **Contexte** : Description du problème ou du besoin.
- **Décision** : Le choix technique ou produit final.
- **Impact & Conséquences** : Ce que cela simplifie ou complique (compromis).

### 2. Typologie des Décisions Tracées

| Catégorie | Exemple de Décision | Fichier source |
| :--- | :--- | :--- |
| **Produit** | Changement de lexique (Labs -> Groups) | ADR 2026-04-09-40 |
| **Architecture** | Choix de la stack TALL (Livewire/Alpine) | ADR 2026-04-08-14 |
| **Sécurité** | Protocole d'audit US33 et Durcissement | ADR 2026-04-09-38 |
| **Déploiement** | Pipeline CI/CD automatisé | ADR 2026-04-09-35 |

### 3. Compromis et Arbitrages (Exemple)
Lors de l'implémentation du **Drag-and-Drop** pour les artefacts complexe (V3), nous avons arbitré pour une gestion dynamique des indices côté AlpineJS plutôt qu'un state management lourd côté serveur. 
- *Conséquence positive* : Fluidité et réactivité instantanée.
- *Compromis* : Logique de tri légèrement plus complexe à maintenir dans les templates Blade.

### 4. Utilisation pour la Soutenance
Ce journal permet de démontrer au jury que chaque ligne de code et chaque choix d'UI n'est pas le fruit du hasard, mais résulte d'une réflexion documentée sur les contraintes du projet et les besoins des utilisateurs.
