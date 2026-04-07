# US03 - Cartographier les user journeys prioritaires (ReviewMe)

## Description
**EN TANT QUE** UX Designer  
**JE VEUX** Modéliser les parcours utilisateurs alignés sur les Personas (Lucas, Marc, Sarah)  
**AFIN DE** garantir que chaque fonctionnalité (TALL Stack) répond à un irritant spécifique identifié.

---

## Critères d'acceptation
- [x] Journey 1 (Lucas) : Cycle de progression versionnée (V1 -> V2).
- [x] Journey 2 (Marc) : Expertise chirurgicale et gain de Karma.
- [x] Journey 3 (Sarah) : Gestion pédagogique et revue de groupe.
- [x] Mapping des frictions vers le backlog technique (Livewire/Reverb).

---

## 👤 Journey 1 : Lucas (L'Apprenti) — Élever son niveau technique
**Objectif** : Sortir du "code de débutant" grâce au regard d'un expert.
**Profil** : Étudiant M1 en alternance à Cergy-le-Haut.

| Étape | Action / Décision | Irritants (US02) | Résultat attendu (Whaou) |
| :--- | :--- | :--- | :--- |
| **1. Dépôt V1** | Colle son code Laravel et définit son intention. | Syndrome de l'imposteur. | Rendu **Shiki** pro qui valorise son code. |
| **2. Feedback** | Reçoit un conseil architectural sur la ligne 15. | Critique floue ou dévalorisante. | Commentaire bienveillant ancré sur le code. |
| **3. Pivot V2** | Publie une V2 liée pour montrer qu'il a compris. | Peur de s'égarer dans les retours. | Historique clair montrant sa progression. |

---

## 👤 Journey 2 : Marc (L'Expert) — Mentoring à haute efficacité
**Objectif** : Transmettre son savoir sans que cela ne devienne un second job.
**Profil** : Lead Dev Senior en télétravail.

| Étape | Action / Décision | Irritants (US02) | Résultat attendu (Whaou) |
| :--- | :--- | :--- | :--- |
| **1. Veille Rapide** | Filtre le feed par langage (PHP/JS). | Trop de bruit/bugs à régler. | Cible uniquement les problèmes de logique. |
| **2. Revue Inline** | Clique sur une ligne et suggère un refactoring. | Processus de revue lourd (Gerrit/Gitlab). | Saisie **Livewire** ultra-flash sans rechargement. |
| **3. Reaction** | Applique un tag **✨ Clean Code**. | Manque de feedback valorisant. | Sentiment d'avoir aidé concrètement un junior. |

---

## 👤 Journey 3 : Sarah (L'Enseignante) — Gestion de promo à l'échelle
**Objectif** : Faire monter en compétence 40 étudiants sans s'épuiser.
**Profil** : Prof d'informatique à CY Tech.

| Étape | Action / Décision | Irritants (US02) | Résultat attendu (Whaou) |
| :--- | :--- | :--- | :--- |
| **1. Setup Groupe** | Crée l'espace "CY Tech - L2 Dev Web" et partage le lien. | Complexité de l'admin des outils. | Invitation simple par URL comme Google Docs. |
| **2. Supervision** | Utilise les Websockets (Reverb) pour voir qui est actif. | Impression de corriger dans le vide. | Tableau de bord vivant : "12 étudiants révisent". |
| **3. Certification** | "Certifie" une excellente suggestion d'un élève. | Temps de correction interminable. | La revue certifiée remonte tout en haut du post. |
| **4. Bilan** | Exporte ou visionne les scores de Karma de sa promo. | Manque de preuves de progression. | Classement basé sur l'esprit critique, pas la note. |

---

## Identification des Frictions techniques & Backlog

| Point de Friction | Story associée (Backlog) | Priorité |
| :--- | :--- | :--- |
| **Partage d'invitation** | US05 - Système de liens d'invitation à usage unique | Haute |
| **Visibilité des mentions** | US11 - Système de notifications Livewire (Toast) | Moyenne |
| **Rendu temps réel** | US12 - Optimisation des broadcasts Laravel Reverb | Haute |
| **Filtrage des scores** | US20 - Export CSV/PDF du Karma pour les profs | Basse |

---

## Cohérence avec les Personas (US02)
- **Pour Lucas** : ReviewMe n'est pas un juge, mais un coach (focus sur le versioning).
- **Pour Marc** : ReviewMe respecte son temps (focus sur l'inline commenting).
- **Pour Sarah** : ReviewMe est son assistant pédagogique (focus sur les groupes et la certification).
