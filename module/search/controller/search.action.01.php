<?php
if ( ! defined( 'ABSPATH' ) ) {	exit; }

class Search_Action_01 {

	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'admin_post_search_task', array( $this, 'callback_search_task' ) );
	}

	/**
	 * Permet d'afficher le champs de recherche qui vas être affiché dans la barre de WordPress.
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 *
	 * @return void
	 *
	 * @since 1.0.1.0
	 * @version 1.0.1.0
	 */
	public function callback_admin_bar_menu( $wp_admin_bar ) {
		if ( current_user_can( 'administrator' ) ) {

			ob_start();
			require( wpeo_template_01::get_template_part( WPEOMTM_SEARCH_DIR, WPEOMTM_SEARCH_TEMPLATES_MAIN_DIR, 'backend', 'search.view', '' ) );
			$view = ob_get_clean();

			$button_open_popup = array(
				'id'       	=> 'button-search-task',
				'title'			=> $view,
			);

			$wp_admin_bar->add_node( $button_open_popup );
		}
	}

	public function callback_search_task() {
		check_ajax_referer( 'search_task' );

		$term = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		global $task_controller;

		wp_safe_redirect( admin_url( 'admin.php?page=wpeomtm-dashboard&s=' . $term ) );
		exit;
	}
}

global $search_action;
$search_action = new Search_Action_01();
