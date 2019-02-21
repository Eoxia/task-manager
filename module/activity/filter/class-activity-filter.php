<?php
/**
 * Gestions des filtres utilisés pour l'affichage de la dernière activité dans un post.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager\Import\Filter
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestions des filtres utilisés pour laffichage de la dernière activité dans un post.
 */
class Activity_Filter {

	/**
	 * Appels des initialisations des filtres pour l'import des tâches.
	 */
	public function __construct() {
		add_filter( 'tm_task_header', array( $this, 'task_display_type_choice' ), 10, 2 );
	}

	/**
	 * Ajoute les boutons permettant de choisir le mode d'affichage dans la tâche: mode normale ou en mode "activité"
	 *
	 * @param  string     $current_output Le contenu actuel du filtre que l'on va modifier.
	 * @param  Task_Model $task           La tâche sur laquelle on se trouve.
	 *
	 * @return string                     Le contenu modifié.
	 */
	public function task_display_type_choice( $current_output, $task ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'activity',
			'backend/task-header-button',
			array(
				'task' => $task,
			)
		);
		$current_output .= ob_get_clean();

		return $current_output;
	}

}

new Activity_Filter();
