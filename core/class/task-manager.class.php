<?php
/**
 * Classes principale du plugin.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 0.1.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classes principale du plugin.
 */
class Task_Manager_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour l'utilisation de Singleton_Util.
	 *
	 * @since 0.1.0
	 * @version 1.5.0
	 */
	protected function construct() {}

	/**
	 * La méthode qui permet d'afficher la page
	 *
	 * @since 0.1.0
	 * @version 1.5.0
	 *
	 * @return void
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
