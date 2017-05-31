<?php
/**
 * Appelle la vue principale de l'application
 *
 * @package TaskManager\Plugin
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Appelle la vue permettant d'afficher la navigation
 */
class Task_Manager_Class extends Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function display() {
		$term = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : ''; // WPCS: CSRF ok.
		$id = (int) $term;
		$categories_id_selected = ! empty( $_GET['categories_id_selected'] ) ? sanitize_text_field( $_GET['categories_id_selected'] ) : ''; // WPCS: CSRF ok.
		$follower_id_selected = ! empty( $_GET['follower_id_selected'] ) ? sanitize_text_field( $_GET['follower_id_selected'] ) : ''; // WPCS: CSRF ok.

		require( PLUGIN_TASK_MANAGER_PATH . '/core/view/main.view.php' );
	}
}

new Task_Manager_Class();
