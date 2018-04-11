<?php
/**
 * Classe relatives à l'admin bar.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe relatives à l'admin bar.
 */
class Admin_Bar_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	protected function construct() { }

	/**
	 * Ajoutes le button "Quick Task" dans le sous menu "Create" de l'admin bar de WordPress.
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 *
	 * @return void
	 */
	public function init_quick_time( $wp_admin_bar ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'admin-bar', 'backend/button-quick-time' );
		$button_open_popup = array(
			'id'     => 'button-open-popup-quick-task',
			'parent' => 'new-content',
			'title'  => ob_get_clean(),
		);

		$wp_admin_bar->add_node( $button_open_popup );
	}

	/**
	 * Ajoutes le logo de TaskManager et le nombre de demande faites par les clients.
	 * En cliquant dessus, renvoies vers la page "task-manager-indicator".
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 * @return void
	 */
	public function init_search( $wp_admin_bar ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'admin-bar', 'backend/main' );
		$view = ob_get_clean();

		$button_open_popup = array(
			'id'    => 'button-search-task',
			'title' => $view,
		);

		$wp_admin_bar->add_node( $button_open_popup );
	}
}

new Admin_Bar_Class();
