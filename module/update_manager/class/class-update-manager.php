<?php
/**
 * Classe gérant les mises à jour.
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
 * Classe gérant les mises à jour de Task Manager.
 */
class Update_Manager extends \eoxia\Singleton_Util {

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
		$waiting_updates = get_option( '_tm_waited_updates', array() );
		\eoxia\View_Util::exec( 'task-manager', 'update_manager', 'main', array(
			'waiting_updates' => $waiting_updates,
		) );
	}

}

new Update_Manager();
