<?php
/**
 * Appelle la vue principale de l'application
 *
 * @package TaskManager\Plugin
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
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @return void
	 */
	public function display() {
		$term = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';

		require( PLUGIN_TASK_MANAGER_PATH . '/core/view/main.view.php' );
	}
}

new Task_Manager_Class();
