<?php
/**
 * Les actions relatives à la chronologie
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage action
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Les actions relatives à la chronologie
 */
class Timeline_Action {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'callback_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_user_profile' ) );

		add_action( 'personal_options_update', array( $this, 'callback_options_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'callback_options_update' ) );

		add_action( 'wp_ajax_load_timeline_user', array( $this, 'ajax_load_timeline_user' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 30 );
	}


	public function callback_user_profile( $user ) {
		require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'user', 'profile' ) );
	}

	public function callback_options_update( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;

		update_user_meta( $user_id, 'working_time', taskmanager\util\wpeo_util::convert_to_minut( $_POST['working_time'] ) );
	}

	/**
	 * Charges la timeline d'un utilisateur
	 *
	 * @return void
	 */
	public function ajax_load_timeline_user() {
		ob_start();

		global $task_timeline;

		$list_year = $task_timeline->generate_year();
		$current_month = date( 'n' );
		$current_day = date( 'd' );
		$user_id = $_POST['user_id'];

		require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'year' ) );

		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * [callback_admin_menu description]
	 * @return [type] [description]
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'wpeomtm-dashboard', __( 'Timeline', 'task-manager' ), __( 'Timeline', 'task-manager' ), 'manage_task_manager', 'tm-timeline', array( Timeline_Class::g(), 'callback_submenu_page' ) );
	}
}

new Timeline_Action();
