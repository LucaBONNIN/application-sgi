# Environnement de Développement & Architecture

## 1. Vue d'ensemble de l'architecture locale

Afin de garantir l'homogénéité des développements entre les membres de l'équipe, nous avons mis en place une architecture conteneurisée stricte. Chaque poste développeur réplique l'environnement suivant :

## 2. Choix Technologiques & Outillage

### A. Le Framework & L'Interface (Filament PHP)

Nous avons choisi **Laravel 12** couplé à **Filament PHP**.

- **Justification :** Ce choix répond à la contrainte de temps (RAD - Rapid Application Development). Filament nous permet de générer l'interface d'administration (Back-office, CRUDs, Tableaux de bord) via des composants PHP, sans avoir à coder le Front-End (HTML/CSS) manuellement.
    
- **Avantage SGI :** La gestion des rôles (Intendance vs Professeurs) et les formulaires complexes (lignes de commandes dynamiques) sont gérés nativement.
    
### B. Environnement de Développement (IDE)

L'équipe utilise uniformément **JetBrains PhpStorm** accompagné du plugin **Laravel IDEA**.

- **Analyse Statique :** L'IDE détecte les erreurs de type et de logique avant même l'exécution.
    
- **Productivité :** Le plugin offre une autocomplétion avancée sur les facettes "magiques" de Laravel (Eloquent, Vues, Config) et génère le code répétitif (Migrations, Modèles), réduisant les erreurs humaines.

## 3. Infrastructure "Infrastructure as Code" (Spin Pro)

Pour éviter les divergences de configuration (le fameux _"ça marche chez moi"_), nous n'utilisons pas WAMP/XAMPP. Nous utilisons **Spin Pro**, un orchestrateur Docker pour Laravel.

Chaque membre de l'équipe dispose via une simple commande `spin up` des services isolés suivants :

|**Service**|**Technologie**|**Rôle dans le projet SGI**|
|---|---|---|
|**Serveur Web**|Nginx + PHP-FPM|Exécution du code Laravel avec la version PHP 8.3/8.4 stricte.|
|**Base de Données**|**PostgreSQL**|Choisi pour sa rigueur sur les contraintes d'intégrité (Foreign Keys) et sa gestion des types avancés, supérieure à MySQL pour des applications de gestion.|
|**Serveur Mail**|**Mailpit**|Serveur SMTP "trappe" local. Il intercepte les emails de notification (validation de commande) pour visualiser le rendu sans envoyer de vrais emails.|

## 4. Collaboration & Qualité du Code
Travaillant en équipe de trois, nous avons instauré des règles strictes de collaboration.

### A. Versioning (Git)
Le code source est versionné sous **Git**.

- Nous utilisons des branches par fonctionnalité (`feature/creation-commande`, `fix/budget-calcul`) pour ne jamais travailler directement sur la branche principale (`master`).
    
- Cela permet de fusionner le travail proprement et de revenir en arrière en cas de régression.
    
### B. Standardisation du code (Laravel Pint)
Pour éviter les débats sur le formatage (espaces, accolades), nous utilisons **Laravel Pint**. C'est un outil qui reformate automatiquement le code PHP selon les standards PSR-12 avant chaque "commit". Cela garantit que le code de l'étudiant A ressemble exactement au code de l'étudiant B.

## 5. Conclusion
Cette infrastructure professionnelle (**Docker + PhpStorm + Git**) nous permet de nous concentrer à 100% sur la logique métier du SGI. Elle simule fidèlement un environnement de production, minimisant ainsi les risques techniques lors du déploiement final.
