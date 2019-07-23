<?php
/**
 * Gestion des actions liées à la barre d'administration de WordPress.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @since     1.0.0
 * @version   1.6.0
 * @copyright 2015-2017 Eoxia
 * @package   Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des actions liées à la barre d'administration de WordPress.
 */
class Admin_Bar_Action {


	/**
	 * Le constructeur
	 *
	 * @since   1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'wp_ajax_load_popup_quick_time', array( $this, 'ajax_load_popup_quick_time' ) );
		add_action( 'admin_post_search_task', array( $this, 'callback_search_task' ) );
	}

	/**
	 * Permet d'afficher le champ de recherche.
	 * Permet d'ajouter le bouton "Temps rapides" dans le menu "Créer" de WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.2.0
	 *
	 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
	 *
	 * @return void
	 */
	public function callback_admin_bar_menu( $wp_admin_bar ) {
		if ( current_user_can( 'administrator' ) ) {
			Admin_Bar_Class::g()->init_quick_time( $wp_admin_bar );
			Admin_Bar_Class::g()->init_search( $wp_admin_bar );
		}

	}

	/**
	 * Appel la méthode "display" de Quick_Time_Class.
	 *
	 * @since 1.6.0
	 */
	public function ajax_load_popup_quick_time() {
		check_ajax_referer( 'load_popup_quick_time' );

		/*ob_start();
		_e( 'Quick time', 'task-manager' );
		Quick_Time_Class::g()->display_setting_button();
		$modal_title = ob_get_clean();*/
		$modal_title = esc_html__( 'Quicktime PAGE', 'task-manager' );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'quick_time',
			'backend/buttons-save'
		);
		$viewbutton = ob_get_clean();

		ob_start();
		Quick_Time_Class::g()->display_list();
		$view = ob_get_clean();
		wp_send_json_success(
			array(
				'modal_title'      => $modal_title,
				'view'             => $view,
				'buttons_view'     => $viewbutton,
			)
		);
	}


	/**
	 * [callback_search_task description]
	 *
	 * @return void
	 */
	public function callback_search_task() {
		check_ajax_referer( 'search_task' );
		$term = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		wp_safe_redirect( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $term ) );
		exit;
	}
}

new Admin_Bar_Action();
