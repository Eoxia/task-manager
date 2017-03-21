<?php
/**
 * Gestion des shortcodes en relation avec les utilisateurs.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package user
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des shortcodes en relation avec les utilisateurs.
 */
class Task_User_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task_manager_owner_task
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_shortcode( 'task_manager_owner_task', array( $this, 'callback_task_manager_owner_task' ) );
	}

	/**
	 * Affiches l'avatar du responsable de la tâche.
	 *
	 * @param  array $param  Tableau contenant des informations sur la tâche.
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_task_manager_owner_task( $param ) {
		$avatar_url = get_avatar_url( $param['owner_id'], 32 );

		View_Util::exec( 'user', 'backend/owner/main', array(
			'owner_id' => $param['owner_id'],
			'task_id' => $param['task_id'],
			'avatar_url' => $avatar_url,
		) );
	}
}

new Task_User_Shortcode();
