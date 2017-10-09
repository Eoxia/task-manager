<?php
/**
 * Les actions relatives aux outils.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux outils.
 */
class Tools_Action {

	/**
	 * Initialise les actions liées aux outils.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
		add_action( 'wp_ajax_paginate_point', array( $this, 'callback_paginate_point' ) );
	}

	/**
	* Initialise le menu dans l'onglet 'Outils' du menu de WordPress.
	*
	* @since 1.5.0
	* @version 1.5.0
	*
	* @return void
	*/
	public function callback_admin_menu() {
		add_management_page( 'Task Manager', 'Task Manager', 'manage_options', 'taskmanager-tools', array( Tools_Class::g(), 'display' ) );
	}

	public function callback_paginate_point() {
		ob_start();
		Tools_Class::g()->display();
		wp_die( ob_get_clean() );
	}
}

new Tools_Action();
