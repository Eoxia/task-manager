<?php
/**
 * Gestion des actions pour les mises à jours.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des "actions" pour le module de mise à jour des données suite aux différentes version de l'extension
 */
class Update_Manager_Action extends \eoxia\Update_Manager_Action {

	/**
	 * Instanciation de la classe de gestions des mises à jour des données suite aux différentes versions de l'extension
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
		add_action( 'wp_loaded', array( $this, 'automatic_update_redirect' ) );
		add_action( 'wp_ajax_tm_redirect_to_dashboard', array( $this, 'callback_tm_redirect_to_dashboard' ) );
	}

	/**
	 * AJAX Callback - Return the website url
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function callback_tm_redirect_to_dashboard() {
		$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['task-manager']->version );
		if ( 3 === strlen( $version ) ) {
			$version *= 10;
		}
		update_option( \eoxia\Config_Util::$init['task-manager']->key_last_update_version, $version );
		delete_option( \eoxia\Config_Util::$init['task-manager']->key_waiting_updates );

		wp_send_json_success( array(
			'updateComplete'  => true,
			// Translators: 1. Start of link to dashboard 2. End of link to dashboard.
			'doneDescription' => sprintf( __( 'You will be redirect to Task Manager main dashboard. %1$sClick here if nothing append%2$s', 'task-manager' ), '<a href="" >', '</a>' ),
			'url'             => admin_url( 'admin.php?page=' . \eoxia\Config_Util::$init['task-manager']->dashboard_page_url ),
		) );
	}
}

new Update_Manager_Action();
