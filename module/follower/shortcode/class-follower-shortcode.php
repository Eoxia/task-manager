<?php
/**
 * Gestion des shortcodes en relation aux followers.
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
 * Gestion des shortcodes en relation aux catégories.
 */
class Follower_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_shortcode( 'task_manager_task_follower', array( $this, 'callback_task_manager_task_follower' ) );
	}

	/**
	 * Permet d'afficher les followers dans le footer d'une tâche.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 */
	public function callback_task_manager_task_follower( $param ) {
		$task_id = ! empty( $param['task_id'] ) ? (int) $param['task_id'] : 0;

		$task = Task_Class::g()->get( array(
			'p' => $task_id,
		), true );

		$followers = array();

		if ( ! empty( $task->data['user_info']['affected_id'] ) ) {
			$followers = Follower_Class::g()->get( array(
				'include' => $task->data['user_info']['affected_id'],
			) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/main', array(
			'task'      => $task,
			'followers' => $followers,
		) );

		return ob_get_clean();
	}
}

new Follower_Shortcode();
