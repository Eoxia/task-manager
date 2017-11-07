<?php
/**
 * Les actions relatives aux indications.
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
 * Les actions relatives aux indications.
 */
class Indicator_Action {

	/**
	 * Initialise les actions liées au indications.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 30 );
	}

	/**
	 * Ajoutes la page 'Indicator' dans le sous menu de Task Manager.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_admin_menu() {
		$title = __( 'Indicator', 'task-manager' );
		$title = apply_filters( 'tm_indicator_menu_title', $title );

		add_submenu_page( 'wpeomtm-dashboard', $title, $title, 'manage_options', 'task-manager-indicator', array( Indicator_Class::g(), 'callback_submenu_page' ) );
		add_meta_box( 'tm-indicator-activity', __( 'My daily activity', 'task-manager' ), array( Indicator_Class::g(), 'callback_my_daily_activity' ), 'task-manager-indicator-my-activity', 'normal' );
	}
}

new Indicator_Action();
