<?php
/**
 * Gestions des filtres utilisés pour l'import des tâches et points.
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
 * Gestions des filtres utilisés pour l'import des tâches et points.
 */
class Import_Filter {

	/**
	 * Appels des initialisations des filtres pour l'import des tâches.
	 */
	public function __construct() {
		add_filter( 'tm_posts_metabox_project_dashboard', array( $this, 'add_import_button' ), 10, 2 );
		add_filter( 'task_manager_task_header_actions_after', array( $this, 'add_import_button_task_toogle' ), 10, 2 );
	}

	/**
	 * Ajoute le bouton permettant d'importer des tâches avec du contenu sur un POST.
	 *
	 * @param string $current_output Le contenu actuel à afficher.
	 * @param string $post           Le post sur lequel le filtre est actuellement appelé.
	 *
	 * @return string La vue avec notre bouton en plus.
	 */
	public function add_import_button( $current_output, $post ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'import',
			'backend/inline-modal',
			array(
				'post' => $post,
			)
		);
		$current_output .= ob_get_clean();

		return $current_output;
	}

	/**
	 * Affiche le bouton permettant d'importer du contenu (des points) dans une tâche.
	 *
	 * @param integer    $task_id L'identifiant de la tâche sur laquelle on veut importer des points.
	 * @param Task_Class $task    La définition complète de la tâche.
	 */
	public function add_import_button_task_toogle( $task_id, $task ) {
		\eoxia\View_Util::exec(
			'task-manager',
			'import',
			'backend/ajax-modal-open-button',
			array(
				'task_id' => $task_id,
				'task'    => $task,
			)
		);
	}

}

new Import_Filter();
