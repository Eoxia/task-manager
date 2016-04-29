=== Task Manager ===
Contributors: Eoxia
Donate link:
Tags: project, task, time, manage, client, easy, french, wordpress task, wordpress time
Requires at least: 3.4.0
Tested up to: 4.5.1
Stable tag: 1.3.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Quick and easy to use, manage all your tasks and your time with the Task Manager plugin.

== Description ==

= No more communication problems, team organization and posts-it! =
With Task Manager organize your projects with your team and keep control over your time.
Task Manager is a fast and intuitive task management extension.

= Tasks =
Task Manager allows you to create tasks, assign users and then manage their time.

= Categorize =
With Task Manager categorize your tasks and find them. You can customize your categories as you like. Once they are finished, they are archived.

= Comments =
Each user can add comments inside his duties, he can then enter the date and time spent.

= Speed =
Task manager is fast ! In one click, find your tasks by user or category.

= Chronology =
The chronology of Task Manager keeps user actions history.

= Export =
Export your tasks in text format, to transmit or integrate in a report.

= Customers followed =
With shortcode [task], you can display any task, anywhere in article or page.

= To contribute =
We encourage the community to contribute: no matter whether for a compliment, a bug report or idea...

== Installation ==

L'installation de l'extension peut se faire de 2 façons :

Install Task Manager in the wordpress plugin directory, or upload the unzipped files manually to your server.

Some helps for install ? [We are here !](http://forums.eoxia.com/forum/)

== Frequently Asked Questions ==

No questions asked yet.

== Screenshots ==

1. Dashboard / Tableau de bord
2. Add user in a task / Ajouter un utilisateur dans une tâche
3. Add tag in a task / Ajouter un tag dans une tâche
4. Manage time / Gestion du temps
5. See the history / Chronologie

== Changelog ==

= 1.3.1.0 =

= 1.3.0.1 =
= Amélioration / Improvment =
 * 3435 - Backend : Ajout de la date de fin prévue
 * 3436 - Backend : Ajout d'un champ permettant d'associer une tâche vers un élément
 * 3454 - Ajout de traduction supplémentaire

 = Correction / Fix =
 * 3433 - Backend : initiales des avatars sont maintenant en majuscules
 * 3452 - Le plugin est maintenant prêt pour la traduction : https://translate.wordpress.org/projects/wp-plugins/task-manager

= 1.3.0.0 =
= Amélioration / Improvment =
 * 3094 - Page "Users.php" de WordPress : Le temps de présence est maintenant en heure.
 * 3097 - Ajout du support pour translate.wordpress.com.
 * 3065 - La demande de tâche faites par un client ajoutes un point dans une tâche préalablement crée par l'administrateur ou par le script si elle n'existe pas.
 * 3066 - Frontend : Le nombre de point terminé d'une tâche est maintenant affiché.
 * 3073 - Frontend : Le bouton demande de tâche est maintenant renommé demande de ticket.
 * 2954 - Chronologie : Historique de date quand on complète ou dé-complète un point .
 * 2971 - Chronologie : Historique des derniers commentaires ajoutés.
 * 2972 - Chronologie : Affichages des points créer par un utilisateur entre une période.
 * 2973 - Chronologie : Affichages des tâches créer entre une période.
 * 2974 - Chronologie : Affichage du temps travaillé entre une période.
 * 2975 - Chronologie : Affichage des points complétés entre une période.
 * 2976 - Chronologie : Affichage du temps de présence entre une période.
 * 2977 - Chronologie : Affichage du temps passé par projet entre une période.
 * 2978 - Chronologie : Récapitulatif de chaque jour.
 * 3035 - Chronologie : Voir les autres utilisateurs dans la timeline.
 * 3036 - Chronologie : Traduction de la page.
 * 3037 - Chronologie : Design du menu.
 * 3101 - Chronologie : Ordre d'affichage par date décroissant.
 * 3084 - Chronologie : Mise à jour des requêtes SQL pour récupérer les informations par jour dans la page.
 * 3319 - WPShop : La liste des clients pour la recherche est plus simple intuitif.
 * 3399 - Backend : Ajouter une option pour exporter les commentaires des points.
 * 3074 - Backend : Ajout de jQuery DatePicker dans les commentaires.
 * 2946 - Backend : Ajout de la fonction "get_task_by_comment_user_id_and_date" permettant de récupérer le temps passé sur une tâche pour un utilisateur entre deux dates.
 * 2947 - Backend : Ajout de la fonction "get_list_point_by_comment_user_id" permettant de récupérer les points ou un utilisateur à commenté.
 * 2949 - Backend : Ajout de la fonction "get_list_point_by_comment_user_id_and_date" permettant de récupérer les points ou un utilisateur à commenté entre deux dates.
 * 2952 - Backend : Ajout de la fonction "get_created_point_by_user_id_and_date" permettant de récupérer les points créer par un utilisateur par rapport à une période.

= Correction / Fix =
 * 3072 - Frontend : Quand on cliquez sur "Point completés", les points completées de chaque tâche s'ouvrées.
 * 3095 - Chronologie : Le temps travaillé était erroné.
 * 3099 - WPShop : Si aucun message par un client est trouvé, le bloc en dessous de la barre des filtres n'est pas affiché.
 * 3193 - WPShop : Les demandes faites par les clients sont maintenant correctement affiché.
 * 3401 - WPShop : Le slug de la tâche "ask-task-$client_id" faites par les clients WPShop n'est plus modifié quand on clique sur le titre de la tâche.
 * 3277 - Backend : La modification d'un commentaire dans un point est maintenant plus intuitif.
 * 3320 - Backend : Après le déplaçement d'un point d'une tâche à l'autre, on peut maintenant le trier.

= 1.2.1.6 =
= Correction / Fix =
 * 2957 - Correction du frontend pour faire une demande de tâche

= 1.2.1.5 =
= Correction / Fix =
 * Fix du log

= 1.2.1.4 =
= Correction / Fix =
 * Fix commit

= 1.2.1.3 =
= Correction / Fix =
 * Remove wpeo_users folder in module folder

= 1.2.1.2 =
= Correction / Fix =
 * wpeo_util file already declared

= 1.2.1.1 =
= Correction / Fix =
 * Change log

= 1.2.1.0 =
= Amélioration / Improvment =
 * 2557 - La fenêtre de droite n'est plus afficher à l'ouverture la page / The right window isn't displayed on the opening page.
 * 2558 - Le slug de la tâche s'adapte au titre / The task slug adapt from the title
 * 2559 - Affiches toutes les tâches associées au client ou à leurs commandes dans le compte client
 * 2561 - Ajout des nonces de WordPress pour la sécurité
 * 2562 - Ne génère plus 2 caractères d'affichage inatendu lors de l'activation
 * 2569 - Ajout du shortcode [wpeo_task id='n'] pour afficher une tâche dans le front
 * 2572 - Affiches les gravatars sur les utilisateurs / Display gravatar on users
 * 2579 - Le bouton "voir la tâche" n'est plus disponible
 * 2581 - Traduction du texte : Write your point here... en Écrivez votre point ici...
 * 2590 - Animation quand on ajoutes et supprimes un point
 * 2591 - Animation quand on modifie un utilisateur
 * 2592 - Animation à la fenêtre de droite
 * 2604 - On peut maintenant changer le responsable
 * 2606 - Toutes les réponses des requêtes AJAX sont maintenant en JSON
 * 2641 - On peut maintenant filtrer les tâches des autres utilisateurs pour les voir.
 * 2643 - Quand l'extension WPShop est activée, on peut maintenant filtrer les tâches par clients/commandes.
 * 2649 - Ajout d'un préfixe devant les catégories dans le code HTML
 * 2654 - Le formulaire pour ajouter un commentaire à un point à été déplacé en haut de la liste des commentaires
 * 2657 - Ajout des couleurs sur les utilisateurs
 * 2658 - Toutes les méthodes AJAX laisses maintenant une trace dans le log
 * 2665 - Ajout des droits
 * 2689 - Relancer le calcul du temps des tâches manuellement, ajout du bouton "recompiler le temps"
 * 2707 - On peut maintenant transférer les points sur une tâche vers une autre
 * 2741 - Le bouton "Recompile time" est caché
 * 2742 - Le client WPShop peut ajouter un commentaire sur un point
 * 2743 - Le client WPShop peut faire une demande de tâche
 * 2770 - Manque la date de création de la tache
 * 2790 - Ajout de CSS pour les nouvelles fonctionnalitées
 * 2860 - Nouvelle traduction
 * 2704 - On peut déplacer les points dans une tâche
 * 2870 - Infobulle sur les utilisateurs

= Correction / Fix =
 * 2676 - Augmenter la taille des gravatar dans les USER (32x32 actuellement) en 50x50 pour éviter la pixellisation
 * 2722 - Clear filter quand la tâche est fermé
 * 2724 - Avatar vide responsable
 * 2831 - Lorsque qu'on appuie entrée pour envoyer un point vers une autre tâche, la page admin-ajax.php ne s'affiche plus.
 * 2832 - On ne peut plus envoyer un point vers une tâche inexistante.
 * 2833 - La croix pour fermer la fenêtre de droite est maintenant actif coté post type.
 * 2834 - Résolution du problème de Masonry qui faisait apparaître la fenêtre de droite en dessous des autres fenêtres coté post type.
 * 2854 - Quand on supprime une tâche, toutes les tâches sont affichées selon les filtres sélectionné

= 1.2 =
 * 2021 - Affiches les taches actives pour la personne en premier / Display actives tasks for the user in the first display
 * 2160 - Traduction / Translate
 * 2219 - On peut maintenant éditer un commentaire / Now you can edit a comment.
 * 2233 - Ajout des initiales pour l'utilisateur qui est responsable de la tâche / Add initial for the user who is in charge of the task
 * 2235 - Supprimes le bouton "Archive" et utilises une catégorie pour gérer les archives / Delete the "Archive" button and manage archives with a category.
 * 2437 - Ajoutes la class "active" pour le point ciblé / Add class "active" for the target point
 * 2438 - Ajoutes une dashicons "close" pour fermer la fenêtre à droite / Add dashicons "close" for exit the right window
 * 2440 - Gravatar dans le header de la fenêtre de droite / Gravatar in the header on the right window
 * 2441 - Lors de l'édition d'un commentaire ajoutes un loader / When edit comment add a loader
 * 2443 - Ajoutes l'utilisateur qui à crée le point / Add the creator of the point
 * 2491 - Nouveau template pour la fenêtre de droite des points / New template for the right window points
 * 2528 - Voir si le point est completé ou pas dans la fenêtre à droite / View if the point is completed or not in the right window
 * 2529 - Voir le nombre de commentaire sur un point dans la fenêtre à droite / View the number of comment on the point in the right window
 * 2534 - Bouton mes tâches affectées / Button affected task

= 1.1 =
 * Archive / Archiver
 * Add the button "View task" / Ajout du bouton "Voir la tâche"
 * Use the Heart WordPress to refresh the content of the task / Utilises le cœur de WordPress pour actualiser le contenu d'une tâche
 * Added block "Screen Options" to manage the user's viewing preferences / Ajout du bloc "Options de l'écran" pour gérer les préférences d'affichage de l'utilisateur
 * Manage display for tasks associated to another element / Gestion de l'affichage des tâches affectées à un élément
 * Create a controller module with the user / Créer un module avec le controller user
 * Update some class CSS / Met à jour quelques élements CSS


= 1.0 =
 * Créer une tâche / Create a task.
 * Créer un point / Create a point.
 * Organiser vos tâches / Organizing your tasks.
 * Affecter des utilisateurs / Assign users.
 * Exporter une tâche ou plusieurs tâches / Export a task or multiple tasks ( txt )
