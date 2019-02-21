<?php
/**
 * Gestion des shortcodes en relation avec les utilisateurs.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des shortcodes en relation avec les utilisateurs.
 */
class Owner_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task_manager_owner_task
	 *
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_shortcode( 'task_manager_owner_task', array( $this, 'callback_task_manager_owner_task' ) );
	}

	/**
	 * Affiches l'avatar du responsable de la tâche.
	 *
	 * @param  array $param  Tableau contenant des informations sur la tâche.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return HTML Le code HTML permettant l'affichage de la zone correspondant au propriétaire d'une tâche.
	 */
	public function callback_task_manager_owner_task( $param ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'owner',
			'backend/main',
			array(
				'task_id'  => $param['task_id'],
				'owner_id' => $param['owner_id'],
			)
		);

		return ob_get_clean();
	}

}

new Owner_Shortcode();
