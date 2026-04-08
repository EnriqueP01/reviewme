# US02 - Définir les personas et leurs scénarios d'usage

## Description
Identification et formalisation des profils utilisateurs types (Étudiants, Enseignants, Professionnels) et de leurs parcours sur la plateforme ReviewMe. Cette étape est cruciale pour aligner le développement technique (Laravel/Tailwind) sur les besoins réels du terrain.

---

**EN TANT QU'** Équipe projet  
**JE VEUX** formaliser les personas prioritaires et les scénarios concrets qui justifient le produit  
**AFIN DE** relier les futures fonctionnalités à des besoins réalistes et défendables.

---

## Critères d'acceptation

- [x] **Diversité des Profils** : 3 personas décrits (Apprenti, Enseignante, Expert).
- [x] **Profondeur d'Analyse** : Inclusion des dimensions sociodémographiques, psychographiques et comportementales.
- [x] **Scénarisation** : Chaque persona est relié à un scénario d'usage "Moment de Vérité".
- [x] **Questions-Solutions** : Identification des problématiques clés sous forme d'interrogations internes.

---

## 👤 Persona 1 : Lucas (L'Apprenti Architecte)

### 1. Critères sociodémographiques
| Critère | Détails |
| :--- | :--- |
| **Prénom / Profil** | Lucas, Étudiant en Master 1 Informatique (ou Apprenti Développeur). |
| **Âge** | 22 - 25 ans. |
| **Localisation** | Cergy-le-Haut / Port Cergy (Zone Cergy-Pontoise, Île-de-France). |
| **Situation pro** | Étudiant en alternance dans une ESN ou une startup à Cergy ou La Défense. |
| **Niveau d'études** | Bac +4 / Bac +5 en ingénierie logicielle. |
| **Revenus** | 1 100 € - 1 600 € (Salaire d'apprenti ou gratification de stage). |
| **Technologies** | JavaScript (React/Node.js), Dart (Flutter), Python. |

### 2. Description du Persona
Lucas est un jeune développeur passionné qui a dépassé le stade de l'apprentissage de la syntaxe. Il sait produire un code fonctionnel, mais il souffre du **"syndrome de l'imposteur technique"**. Bien que ses projets tournent, il s'inquiète de la qualité de sa structure, de sa dette technique et de la lisibilité de son code. Évoluant dans l'écosystème étudiant de Cergy, il cherche à se professionnaliser rapidement pour maximiser son employabilité. Il ne cherche pas une solution à un bug, mais une confrontation d'idées pour élever son niveau architectural.

### 3. Psychographie
| Attribut | Analyse |
| :--- | :--- |
| **Motivations** | Atteindre l'excellence technique ; comprendre les Design Patterns ; être validé par des pairs expérimentés. |
| **Aspirations** | Devenir Lead Developer ou CTO d'ici 5 à 7 ans ; intégrer une entreprise "Top Tier" à Paris. |
| **Valeurs** | Partage (Open Source spirit) ; pragmatisme ; amélioration continue (Kaizen). |
| **Craintes** | Développer de mauvaises habitudes ; être jugé sur la médiocrité de sa logique interne. |
| **Intérêts** | Veille (Dev.to) ; Meetups de développeurs à Cergy/Paris ; Gaming compétitif. |

### 4. Comportements & Canaux
| Vecteur | Description |
| :--- | :--- |
| **Canaux** | GitHub ; Discord ; Reddit ; LinkedIn. |
| **Décision** | Rapidité du feedback ; légitimité des relecteurs (experts) ; simplicité de l'UI. |
| **Habitudes** | Adepte du "Freemium" ; prêt à payer si la valeur sur sa carrière est prouvée. |
| **Sensibilité prix** | Haute (budget étudiant), mais perçu comme un investissement de formation. |
| **Influenceurs** | Ses professeurs à CY Tech ; Micode, Grafikart sur YouTube. |

### 5. Problématiques clés (Questions solutions)
*   **Stagnation qualitative** : "Comment puis-je savoir si ma logique de code est optimale alors que mon programme fonctionne déjà sans erreur ?"
*   **Isolement intellectuel** : "Où puis-je trouver un regard critique et bienveillant sur ma structure de code en dehors du cadre restreint de mes projets de groupe ?"
*   **Transition pro** : "Quels sont les standards de réflexion des experts en entreprise que je ne maîtrise pas encore ?"
*   **Déficit de mentoring** : "Comment obtenir des conseils d'architecte sans avoir à intégrer immédiatement une grande entreprise possédant des processus de revue seniors ?"

### 6. Arguments clés pour convaincre
*   **L'accélérateur de carrière** : Gagnez deux ans d'expérience en comprenant comment pensent les seniors.
*   **La culture de la "Clean Architecture"** : Ne vous contentez pas d'un code qui marche ; produisez un code qui se lit.
*   **Un environnement "Safe"** : Une communauté où la question porte sur la logique, pas sur l'échec technique.
*   **Networking local** : Connectez-vous avec les talents de l'axe Cergy-Paris pour échanger sur vos pratiques.

### 7. Scénario d'usage : "Le Pivot Architectural"
Lucas vient de finir une fonctionnalité complexe en Laravel pour son alternance. Il est fier que cela marche, mais il a l'impression d'avoir écrit "du code de débutant". Il poste son controller sur ReviewMe en demandant : *"Est-ce que j'ai respecté le principe de responsabilité unique ici ?"*. En recevant un "Boost" et un conseil de Marc sur l'usage d'un Service, Lucas comprend immédiatement comment refactoriser.

---

## 👤 Persona 2 : Sarah (L'Enseignante Innovante)

### 1. Critères sociodémographiques
| Critère | Détails |
| :--- | :--- |
| **Prénom / Profil** | Sarah, Professeure agrégée d'informatique. |
| **Âge** | 40 - 45 ans. |
| **Localisation** | Cergy (CY Tech ou Université de Cergy-Pontoise). |
| **Situation pro** | Responsable de module de programmation / Projets de fin d'études. |
| **Technologies** | Java, C++, Algorithmique, PHP/Laravel. |

### 2. Description du Persona
Sarah cherche à moderniser ses méthodes d'évaluation. Elle est frustrée par le temps passé à corriger des erreurs de structure identiques chez 40 étudiants. Elle souhaite que ses élèves développent un esprit critique et apprennent à lire le code des autres, pas seulement à écrire le leur. Elle voit en ReviewMe un levier pour le peer-learning.

### 3. Problématiques clés (Questions solutions)
*   **Feedback à l'échelle** : "Comment offrir un retour qualitatif à toute une promotion sans y passer mes nuits ?"
*   **Esprit critique** : "Comment forcer mes étudiants à se poser des questions sur la maintenance avant de rendre leur projet ?"
*   **Traces de progression** : "Comment prouver qu'un étudiant a acquis une maturité de réflexion au-delà de la note du TP ?"

### 4. Arguments clés pour convaincre
*   **Massification du Mentoring** : Transformez la classe en un réseau de micro-revues.
*   **Standardisation Pro** : Préparez vos étudiants aux rituels techniques (Code Review) du monde de l'entreprise.
*   **Tableau de Bord Pédagogique** : Identifiez en un coup d'œil les blocages conceptuels majeurs de la promo.

### 5. Scénario d'usage : "Le TD Inversé"
Sarah crée un "Cercle ReviewMe" pour son module Laravel. Elle demande aux étudiants de poster leur Architecture de Base de Données. Au lieu de corriger elle-même, elle demande aux étudiants d'utiliser le système de "Boost" sur les idées les plus pragmatiques. Elle n'intervient que pour certifier les meilleures réponses, gagnant 4h de correction par semaine.

---

## 👤 Persona 3 : Marc (L'Expert Bienveillant)

### 1. Critères sociodémographiques
| Critère | Détails |
| :--- | :--- |
| **Prénom / Profil** | Marc, Lead Developer Freelance ou Senior Architecte. |
| **Âge** | 35 - 40 ans. |
| **Localisation** | Télétravail / Paris (Cergy-La Défense). |
| **Technologies** | Go, Rust, Clean Architecture, PHP 8.x. |

### 2. Description du Persona
Marc est un expert qui a atteint un niveau où la transmission est devenue une priorité personnelle. Il est lassé par l'arrogance de certaines communautés (StackOverflow) et cherche un endroit pour mentorer de manière positive. Il est aussi toujours en veille sur les usages des frameworks par la nouvelle génération.

### 3. Problématiques clés (Questions solutions)
*   **Transmission efficiente** : "Comment aider les juniors sans que cela ne devienne un second job à temps plein ?"
*   **Personal Branding** : "Comment valoriser ma capacité à faire grandir une équipe auprès de mes futurs clients ?"
*   **Sourcing de talents** : "Où trouver des juniors qui ont déjà une mentalité d'architecte avant de les recruter ?"

### 4. Arguments clés pour convaincre
*   **Mentoring Asynchrone** : Donnez un conseil d'expert entre deux réunions, en 5 minutes.
*   **Certification d'Expertise** : Accumulez des "Boosts" de la communauté qui prouvent votre pédagogie technique.
*   **Accès à la Relève** : Identifiez les futurs talents de Cergy-Paris avant qu'ils ne soient sur le marché.

### 5. Scénario d'usage : "Le Chasseur de Tête Technique"
Marc passe 10 minutes sur ReviewMe pendant sa pause café. Il voit le post de Lucas sur Laravel. Il est impressionné par la clarté de sa question. Il lui répond avec un snippet élégant. Voyant la réaction enthousiaste de Lucas, Marc l'ajoute sur LinkedIn. Trois mois plus tard, il propose à Lucas une mission en freelance pour l'aider sur un projet complexe.
