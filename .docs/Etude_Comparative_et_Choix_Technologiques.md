**Projet :** Système de Gestion de l'Intendance (SGI)
**Contexte :** Projet d'application web
## 1. Introduction et Analyse des besoins
Dans le cadre du développement de l'application SGI, destinée à centraliser les demandes de l'établissement (fournitures, sorties, réservations), notre équipe a dû sélectionner la pile technologique (stack) la plus adaptée.

Notre équipe étant composée de trois étudiants débutants en frameworks, nous avons défini trois critères décisifs pour ce choix :

1. **Vitesse de développement (RAD) :** La capacité à livrer un prototype fonctionnel rapidement compte tenu des délais scolaires.
2. **Facilité d'apprentissage :** La courbe d'apprentissage doit être compatible avec notre niveau actuel.
3. **Adéquation fonctionnelle :** La technologie doit faciliter la création de tableaux de bord, de formulaires complexes (lignes de produits, uploads) et la gestion des rôles (Admin/Utilisateur).

## 2. Tableau comparatif des solutions envisagées
Nous avons comparé quatre approches populaires dans le développement web moderne :

| **Langage** | **Framework Backend** | **Solution Frontend / UI** | **Courbe d'apprentissage** | **Vitesse de Dev** | **Verdict pour le projet SGI**                                                                                    |
| ----------- | --------------------- | -------------------------- | -------------------------- | ------------------ | ----------------------------------------------------------------------------------------------------------------- |
| **PHP**     | **Laravel**           | **Filament PHP**           | **Faible / Moyenne**       | **Très Élevée**    | **RETENU**<br><br>Solution "tout-en-un" idéale pour les back-offices.                                             |
| **PHP**     | Symfony               | EasyAdmin                  | Élevée                     | Élevée             | **Rejeté**<br><br>Configuration plus complexe et stricte que Laravel pour des débutants.                          |
| **JS / TS** | Node.js (NestJS)      | React.js ou Vue.js         | Très Élevée                | Faible             | **Rejeté**<br><br>Nécessite de gérer deux projets distincts (API + Front) et une authentification complexe (JWT). |
| **Python**  | Django                | Django Admin               | Moyenne                    | Moyenne            | **Rejeté**<br><br>Interface d'administration par défaut peu ergonomique et difficile à personnaliser.             |
| **Java**    | Spring Boot           | Angular                    | Extrême                    | Très Faible        | **Rejeté**<br><br>Trop verbeux ("Boilerplate") pour une équipe réduite et un projet de cette taille.              |
## 3. Analyse détaillée des alternatives rejetées
### Pourquoi pas une stack JavaScript (Node + React) ?
Bien que très populaire, séparer le Backend (API) et le Frontend ajoute une complexité structurelle majeure. Il aurait fallu gérer :

- La communication asynchrone (Fetch/Axios) pour chaque formulaire.
- La sécurité des tokens d'authentification (JWT) manuellement.
- Le "State Management" (Redux/Context) pour gérer les données.

Pour une application de type SGI qui est essentiellement composée de formulaires et de tableaux (CRUD), cette approche est surdimensionnée et risquée pour des délais courts.
### Pourquoi pas Symfony ?
Symfony est un excellent framework, très présent en entreprise. Cependant, sa rigueur (injection de dépendances stricte, configuration YAML) demande un temps d'adaptation plus long que la syntaxe expressive et intuitive de Laravel. EasyAdmin, l'équivalent de Filament chez Symfony, est performant mais offre moins de flexibilité "out-of-the-box" pour des débutants.
## 4. Justification du choix : Laravel + Filament
Nous avons retenu le couple **Laravel** (Backend) et **Filament** (Frontend/Admin Panel) pour les raisons suivantes, directement liées au cahier des charges :
### A. Développement Monolithique (PHP Unifié)
Avec Filament, nous n'avons pas besoin d'écrire de HTML, CSS ou JavaScript complexe. Tout le code de l'interface est généré via des classes PHP. Cela nous permet de nous concentrer sur la **logique métier** (règles de gestion de l'intendance) sans être bloqués par des problèmes de design ou de mise en page.
### B. Gestion native des composants complexes du SGI
Le cahier des charges impose des fonctionnalités qui sont natives dans Filament :

- **Tableaux dynamiques (Repeater) :** Pour la commande de fournitures, l'utilisateur doit pouvoir ajouter plusieurs lignes de produits (Réf, Désignation, Prix, Qté). Le composant `Repeater` de Filament gère cela nativement.
- **Upload de fichiers :** La gestion du devis PDF (upload, stockage, lien) se fait en une ligne de code avec le composant `FileUpload`.
- **Filtrage et Recherche :** Le service intendance doit avoir une vision globale. Filament génère automatiquement des tableaux avec filtres (par statut "En cours", "Commandée", etc.).
### C. Gestion des Rôles et Sécurité
Le projet distingue clairement deux acteurs : le service intendance (Admin) et le personnel (Utilisateur). Laravel possède un système d'authentification robuste. Filament s'appuie dessus pour gérer les permissions (Policies). Nous pourrons facilement restreindre l'accès à certaines pages (ex: seul l'intendant peut voir le bouton "Valider la commande").
### D. Rendu professionnel immédiat
Filament utilise **Tailwind CSS**. L'interface sera donc responsive (mobile/tablette) et esthétique dès le début du projet, ce qui nous permettra de présenter une application propre lors de la soutenance, sans avoir passé du temps sur le CSS.
## 5. Conclusion
Le choix de **Laravel + Filament** est une décision stratégique pragmatique. Il minimise les risques techniques liés à notre niveau de débutant tout en maximisant la productivité. Cette stack nous garantit de pouvoir livrer toutes les fonctionnalités demandées (Gestion des fournitures, Sorties, Réservations) dans les temps impartis, avec un niveau de qualité et de sécurité professionnel.
