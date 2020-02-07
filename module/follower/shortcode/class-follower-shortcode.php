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
		add_shortcode( 'task_manager_task_waiting_for', array( $this, 'callback_task_manager_waiting_for' ) );
		add_shortcode( 'task_manager_dropdown_users', array( $this, 'callback_task_manager_dropdown_users' ) );
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

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$followers = array();

		if ( ! empty( $task->data['user_info']['affected_id'] ) ) {
			$followers = Follower_Class::g()->get(
				array(
					'include' => $task->data['user_info']['affected_id'],
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/main',
			array(
				'task'      => $task,
				'followers' => $followers,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Permet d'afficher les utilisateurs en attente.
	 *
	 * @param array $param Les paramètres du shortcode.
	 *
	 * @since 3.1.0
	 * @version 3.1.0
	 */
	public function callback_task_manager_waiting_for( $param ) {
		$task_id = ! empty( $param['task_id'] ) ? (int) $param['task_id'] : 0;

		$task = Point_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$followers = array();

		if ( ! empty( $task->data['waiting_for'] ) ) {
			$followers = Follower_Class::g()->get(
				array(
					'include' => $task->data['waiting_for'],
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/waiting/main',
			array(
				'task'      => $task,
				'followers' => $followers,
			)
		);

		return ob_get_clean();
	}

	public function callback_task_manager_dropdown_users() {
		$users = get_users(
			array(
				'roles' => 'administrator',
			)
		);

		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/dropdown/main', array(
			'users' => $users,
		) );
	}
}

new Follower_Shortcode();
