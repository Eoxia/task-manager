<?php
/**
 * Gestion de l'admin bar de WordPress.
 *
 * @package Task Manager
 * @subpackage Module/task
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion de l'admin bar de WordPress.
 */
class Admin_Bar_Action {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'admin_post_search_task', array( $this, 'callback_search_task' ) );
	}

	/**
	 * [callback_admin_bar_menu description]
	 * @param  [type] $wp_admin_bar [description]
	 * @return [type]               [description]
	 */
	public function callback_admin_bar_menu( $wp_admin_bar ) {
		if ( current_user_can( 'administrator' ) ) {

			ob_start();
			View_Util::exec( 'admin-bar', 'backend/main' );
			$view = ob_get_clean();

			$button_open_popup = array(
				'id'       	=> 'button-search-task',
				'title'			=> $view,
			);

			$wp_admin_bar->add_node( $button_open_popup );
		}
	}

	/**
	 * [callback_search_task description]
	 * @return [type] [description]
	 */
	public function callback_search_task() {
		check_ajax_referer( 'search_task' );
		$term = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		wp_safe_redirect( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $term ) );
		exit;
	}
}

new Admin_Bar_Action();
