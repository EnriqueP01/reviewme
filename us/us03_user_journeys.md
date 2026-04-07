# US03 - Cartographier les user journeys prioritaires

## Description
**EN TANT QUE** UX Designer  
**JE VEUX** Modéliser les parcours utilisateurs de bout en bout  
**AFIN DE** identifier les étapes critiques, les frictions et les points de valeur avant développement

---

## Critères d'acceptation
- [x] Au moins 2 user journeys complets sont produits pour des personas prioritaires.
- [x] Chaque parcours mentionne étapes, décisions, irritants et résultat attendu.
- [x] Les points de friction majeurs sont reliés à des futures stories du backlog.
- [x] Le parcours principal est cohérent avec le MVP annoncé.

---

## User Journey 1 : Lucas (Étudiant) demande une revue de code
**Objectif** : Obtenir un regard extérieur sur la "propreté" de son architecture Flutter.

| Étape | Action / Décision | Irritants potentiels | Résultat attendu (Whaou) |
| :--- | :--- | :--- | :--- |
| **1. Connexion** | Lucas se connecte via Github. | Login laborieux ou mot de passe oublié. | Accès immédiat via OAuth. |
| **2. Dépôt de Code** | Il utilise le "Smart Paste" : copie son fichier `service.dart`. | Langage mal détecté ; copier-coller tronqué. | Coloration syntaxique automatique instantanée. |
| **3. Intention** | **Contrainte forte** : Il doit expliquer son intention ("Je veux isoler l'accès API"). | "Flemme" de rédiger si le champ est trop long. | Guide textuel (placeholder) qui l'aide à être concis. |
| **4. Publication** | Il choisit de publier dans le groupe "Cergy-Tech M1". | Groupe introuvable ou trop de bruit. | Notification visuelle : "Code en attente de regard expert". |
| **5. Réception** | Reçoit une notification de revue par un "Superviseur". | Critique trop rude ou trop vague. | Feedback bienveillant affiché en Side-by-Side interactif. |

---

## User Journey 2 : Marc (Senior Dev) apporte son expertise
**Objectif** : Transmettre son savoir efficacement et identifier des profils prometteurs.

| Étape | Action / Décision | Irritants potentiels | Résultat attendu (Whaou) |
| :--- | :--- | :--- | :--- |
| **1. Navigation** | Marc survole le feed des codes floutés (Anonymat Protecteur). | Difficulté à évaluer sans voir les noms. | Curiosity gap : le code est la seule star, pas l'ego. |
| **2. Sélection** | Il clique sur un bloc qui semble intéressant. | Temps de chargement du bloc de code. | Transition fluide "Antigravity" qui défloute le contenu. |
| **3. Analyse** | Il compare la structure originale et propose une variante. | Outil de diff complexe à manipuler. | Mode "Side-by-Side" fluide avec scroll synchronisé. |
| **4. Validation** | Il clique sur "Certifier" et ajoute un "Boost" positif. | Manque de feedback sur l'action de Boost. | **Micro-animation** : le bloc de code lévite ; badge "Certifié par Expert". |
| **5. Reconnaissance** | Il voit son score de "Sagesse" augmenter sur son dashboard. | Score caché ou peu gratifiant. | Sentiment d'impact réel sur la communauté de Cergy. |

---

## Identification des Frictions & Backlog

| Point de Friction | Story associée (Backlog) | Priorité |
| :--- | :--- | :--- |
| **Saisie de l'intention** | US12 - Templates de description assistés par IA | Moyenne |
| **Confort de lecture Side-by-Side** | US08 - Optimisation Responsive du comparateur | Haute |
| **Accès aux groupes locaux** | US05 - Système de géofencing / Groupes par établissement | Basse |
| **Animations physiques** | US15 - Moteur de physique Antigravity (Lévitation/Inertie) | Haute (Identité) |

---

## Cohérence MVP
Ce parcours valide le concept **"Web Priority"** :
- Le copier-coller et le Side-by-Side profitent de l'usage clavier/souris et de la largeur d'écran.
- La hiérarchie (Superviseur vs User) est respectée dès le dépôt de code.
- Le cycle de "Boost" positif est au cœur de l'interaction finale.
