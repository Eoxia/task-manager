<?php
/**
 * Gestion des actions pour les mises à jours.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
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
class Update_Manager_Action {

	/**
	 * Instanciation de la classe de gestions des mises à jour des données suite aux différentes versions de l'extension
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'automatic_update_redirect' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );

		add_action( 'wp_ajax_tm_redirect_to_dashboard', array( $this, 'callback_tm_redirect_to_dashboard' ) );
	}

	/**
	 * On récupère la version actuelle de l'extension principale pour savoir si une mise à jour est nécessaire
	 * On regarde également si des mises à jour n'ont pas été faite suite à un suivi des mises à jours non régulier
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function automatic_update_redirect() {
		$waiting_updates = get_option( '_tm_waited_updates', array() );

		if ( ! strpos( $_SERVER['REQUEST_URI'], 'admin-ajax.php' ) ) {
			$current_version_to_check = (int) str_replace( '.', '', \eoxia\Config_Util::$init['task-manager']->version );
			$last_version_done        = (int) get_option( \eoxia\Config_Util::$init['task-manager']->key_last_update_version, 1500 );

			if ( 3 === strlen( $current_version_to_check ) ) {
				$current_version_to_check *= 10;
			}

			if ( $last_version_done !== $current_version_to_check ) {
				$update_data_path = \eoxia\Config_Util::$init['task-manager']->update_manager->path . 'data/';

				for ( $i = ( (int) substr( $last_version_done, 0, 4 ) + 1 ); $i <= $current_version_to_check; $i++ ) {
					if ( is_file( $update_data_path . 'update-' . $i . '-data.php' ) ) {
						require_once $update_data_path . 'update-' . $i . '-data.php';
						$waiting_updates[ $i ] = $datas;

						update_option( '_tm_waited_updates', $waiting_updates );
					}
				}
			}
		}
	}

	/**
	 * Ajoutes une page invisible qui vas permettre la gestion des mises à jour.
	 *
	 * @return void
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function callback_admin_menu() {
		add_submenu_page( '123', __( 'Task Manager Update', 'task-manager' ), __( 'Task Manager Update', 'task-manager' ), 'manage_options', \eoxia\Config_Util::$init['task-manager']->update_page_url, array( Update_Manager::g(), 'display' ) );
	}

	/**
	 * AJAX Callback - Return the website url
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function callback_tm_redirect_to_dashboard() {
		$error_version = ! empty( $_POST['error_version'] ) ? sanitize_text_field( $_POST['error_version'] ) : '';
		$error_status  = ! empty( $_POST['error_status'] ) ? sanitize_text_field( $_POST['error_status'] ) : '';
		$error_text    = ! empty( $_POST['error_text'] ) ? sanitize_text_field( $_POST['error_text'] ) : '';

		if ( ! empty( $error_version ) ) {
			\eoxia\LOG_Util::log( apply_filters( 'digi_update_redirect_to_dashboard', 'FIN - Mise à jour ' . $error_version, $error_version, $error_status, $error_text ), 'task-manager' );
		}

		\eoxia\LOG_Util::log( 'mise à jour end', 'task-manager' );

		$version = (int) str_replace( '.', '', \eoxia\Config_Util::$init['task-manager']->version );
		if ( 3 === strlen( $version ) ) {
			$version *= 10;
		}
		update_option( \eoxia\Config_Util::$init['task-manager']->key_last_update_version, $version );
		delete_option( '_tm_waited_updates' );

		$data = array(
			'url'     => admin_url( 'admin.php?page=wpeomtm-dashboard' ),
			'message' => __( 'Redirect to Task Manager', 'task-manager' ),
		);

		wp_send_json_success( $data );
	}

}

new Update_Manager_Action();
