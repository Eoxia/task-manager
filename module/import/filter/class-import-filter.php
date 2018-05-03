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
	}

	/**
	 * Ajoute le bouton permettant d'importer du contenu dans une tâche ou d'importer des tâches avec du contenu.
	 *
	 * @param string $current_output Le contenu actuel à afficher.
	 * @param string $post           Le post sur lequel le filtre est actuellement appelé.
	 *
	 * @return string La vue avec notre bouton en plus.
	 */
	public function add_import_button( $current_output, $post ) {
		$get_exemple_file = wp_remote_get( \eoxia\Config_Util::$init['task-manager']->import->url . '/test.txt' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'import', 'backend/modal', array(
			'default_content' => wp_remote_retrieve_body( $get_exemple_file ),
			'post'            => $post,
		) );
		$current_output .= ob_get_clean();

		return $current_output;
	}

}

new Import_Filter();
