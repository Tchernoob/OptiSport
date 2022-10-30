# OptiSport

## Présentation

OptiSport est une société de gestion de salle de sport. Elle propose différents services et permet de gérer les droits aux services de ses clients.  

Les clients OptiSport sont les **Partenaires**  et les **Structures** du partenaire. 

Un **Partenaire** est une enseigne de salle de Sport. 
Exemple : L'orange Bleue. 

Un **Partenaire** peut avoir une ou plusieurs **Structure**. Une Une **Structure** est une salle de sport. 
Exemple : L'Orange Bleue - Lyon 8eme. 

Chaque **Partenaire** peut avoir accès à son outil de gestion en ligne ou sont disponibles certains **services**.

Un service est appelé **module** chez Optisport.  

**Optisport** gère le droit des **modules** des partenaires, et des structures des partenaires. Les droits des partenaires et des structures dépendent de **l’abonnement** choisis. Il est tout à fait possible que les structures aient des accès différents aux modules. 

Un abonnement est appelé **modules**chez Optisport. 

L’administrateur Optisport se connecte à l’aide de son adresse email et de son mot de passe. 

Il peut créer les partenaires, les structures, gérer les droits aux **modules** des sructures et le droit à un **package** des partenaires.
Il gère également les **modules** et les **packages**. Il peut les modifier, les activer, les supprimer. 

Un Utilisateur **partenaire**  à accès à sa page partenaire et aux pages des **structures** existantes liées à son **partenaire**. Il peut ainsi vérifier l'accès aux **modules** des **structures** et à l'abonnement actif sur ses entitées.

Un Utilisateur Structure à accès à sa page Structure. Il peut les droits effectifs sur sa salle de sport.

Si vous travaillez en tant qu’administrateur Optisport, et que vous n’avez pas encore de compte, utilisez le compte administrateur : 

admin@optisport.com


## Technologies utilisées 

OptiSport a été réalisé avec Symfony. 

Le front du site a été réalisé en HTML, SASS, le framework Bootstrap et Javascript. 


### Mails 

Les envois de mail dans les différents cas d'utilisation ont été testés en local avec Mailhog. 

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