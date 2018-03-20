<?php
/**
 * Classe gérant les mises à jour.
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
 * Classe gérant les mises à jour.
 */
class Update_Manager extends \Eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Récupères les mises à jour en attente et appel la vue "main" du module "update_manager".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function display() {
		\eoxia\View_Util::exec( 'eo-framework', 'wpeo_update_manager', 'main', array(
			'waiting_updates' => get_option( \eoxia\Config_Util::$init['task-manager']->key_waiting_updates, array() ),
			'redirect_action' => 'tm_redirect_to_dashboard',
			'dashboard_url'   => \eoxia\Config_Util::$init['task-manager']->dashboard_page_url,
		) );
	}

}
