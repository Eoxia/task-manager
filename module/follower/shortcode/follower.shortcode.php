<?php
/**
 * Gestion des shortcodes en relation aux followers.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des shortcodes en relation aux catégories.
 */
class Follower_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_shortcode( 'task_manager_task_follower', array( $this, 'callback_task_manager_task_follower' ) );
	}

	/**
	 * Permet d'afficher les followers dans le footer d'une tâche.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_task_manager_task_follower( $param ) {
		$task_id = ! empty( $param['task_id'] ) ? (int) $param['task_id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$followers = array();

		if ( ! empty( $task->user_info['affected_id'] ) ) {
			$followers = User_Class::g()->get( array(
				'include' => $task->user_info['affected_id'],
			) );
		}

		View_Util::exec( 'follower', 'backend/main', array(
			'task' => $task,
			'followers' => $followers,
		) );
	}
}

new Follower_Shortcode();
