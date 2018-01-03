<?php
/**
 * Mise à jour des données pour la version 1.6.0
 *
 * @author Eoxia
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
 * Mise à jour des données pour la version 1.6.0
 */
class Update_160 {
	/**
	 * Limite de mise à jour des tâches par requêtes.
	 *
	 * @var integer
	 */
	private $limit_task = 50;
	/**
	 * Instanciate update for current version
	 */
	public function __construct() {
		add_action( 'wp_ajax_task_manager_update_1600_task_compiled_time', array( $this, 'callback_task_manager_update_1600_task_compiled_time' ) );
	}

	/**
	 * Récupères les commentaires de la tâche et créer la donnée compilé pour cette tâche.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_task_compiled_time() {
		wp_send_json_success( array(
			'done' => true,
		) );
	}
}

new Update_160();
