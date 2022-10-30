# OptiSportgit

## Présentation

OptiSport est une société de gestion de salle de sport. Elle propose différents services et permet de gérer les droits aux services de ses clients.  

Chaque **partenaire** peut avoir accès à son outil de gestion en ligne ou sont disponibles certains **services**.

Un service est appelé **module** chez Optisport.  

**Optisport** gère le droit des **modules** des partenaires, et des structures des partenaires. Les droits des partenaires et des structures dépendent de **l’abonnement** choisis. Il est tout à fait possible que les structures aient des accès différents aux modules. 

Un abonnement est appelé **package** chez Optisport. 

Tant qu'un administrateur n'a pas validé la publication, elle est visible par l'utilisateur  l'ayant proposée sur sa page profil "En attente de validation". 

Une fois validée, elles est visible sur la même page comme "Chasse validée" et accessible à tous les visiteurs du site. 

Les chasses peuvent être filtrées par date, localisation et catégorie. 

On trouve également sur Huntee des articles d'actualité rédigés par l'administrateur qui peuvent, ou non, être liés à ces chasses au trésor. 

Sur chaque page d'une chasse au trésor ou d'une actualité il est possible pour les utilisateurs connectés de laisser des commentaires. 

## Technologies utilisées 

Huntee a été réalisé avec Symfony. 

Le front du site a été réalisé en HTML, SASS et Javascript. 

Le back-office a été construit à l'aide du bundle "EasyAdmin". 

### Mails 

Les envois de mail dans le cas d'un mot de passe oublié ou d'un message envoyé depuis la page "Contact" ont été testés en local avec Mailhog. 

## Base de données 

Ci-dessous un schéma de la base de données de l'application : 

![Huntee db](/public/images/huntee-db.png)

## Quelques visuels 

### Page d'accueil 

![Huntee home](/public/images/screen_home.png)

### Page profil

![Huntee profil](/public/images/screen_profile.png)

### Filtres

![Huntee filtre](/public/images/screen_list_hunts.png)

### Panel admin 

![Huntee admin](/public/images/screen_admin.png)
