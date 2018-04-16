<?php
/**
 * Gestion des actions liées à la barre d'administration de WordPress.
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
 * Gestion des actions liées à la barre d'administration de WordPress.
 */
class Admin_Bar_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );

		add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

		add_action( 'wp_ajax_load_popup_quick_time', array( $this, 'ajax_load_popup_quick_time' ) );
		add_action( 'admin_post_search_task', array( $this, 'callback_search_task' ) );
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_admin_enqueue_scripts() {
		$module = 'admin-bar';
		wp_enqueue_script( 'task-manager-admin-bar', \eoxia\Config_Util::$init['task-manager']->$module->url . 'asset/js/admin-bar.js', array( 'jquery', 'jquery-form', 'jquery-ui-datepicker' ), \eoxia\Config_Util::$init['task-manager']->version, true );
	}

	/**
	 * Permet d'afficher le champ de recherche.
	 * Permet d'ajouter le bouton "Temps rapides" dans le menu "Créer" de WordPress.
	 *
	 * @since 1.0.0
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
			Admin_Bar_Class::g()->init_customer_link( $wp_admin_bar );
		}
	}

	/**
	 * Appel la méthode "display" de Quick_Time_Class.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function ajax_load_popup_quick_time() {
		check_ajax_referer( 'load_popup_quick_time' );

		ob_start();
		Quick_Time_Class::g()->display_list();
		wp_send_json_success( array(
			'view'         => ob_get_clean(),
			'buttons_view' => '&nbsp;',
		) );
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
