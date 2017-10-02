<?php
/**
 * Gestion des shortcodes en relation des tâches.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des shortcodes en relation des tâches.
 */
class Task_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task
	 */
	public function __construct() {
		add_shortcode( 'task', array( $this, 'callback_task' ) );
	}

	/**
	 * Le shortcode pour afficher les tâches
	 *
	 * @param  array $param Les paramètres du shortcode.
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.4.0
	 */
	public function callback_task( $param ) {
		$param = shortcode_atts( array(
			'id' => 0,
			'categories_id' => array(),
			'users_id' => array(),
			'term' => '',
			'status' => 'any',
			'offset' => 0,
			'post_parent' => 0,
			'posts_per_page' => \eoxia\Config_Util::$init['task-manager']->task->posts_per_page,
			'frontend' => false,
			'with_wrapper' => true,
		), $param, 'task' );

		if ( ! is_array( $param['categories_id'] ) && ! empty( $param['categories_id'] ) ) {
			$param['categories_id'] = explode( ',', $param['categories_id'] );
		}

		if ( ! is_array( $param['categories_id'] ) ) {
			$param['categories_id'] = array();
		}

		if ( ! is_array( $param['users_id'] ) && ! empty( $param['users_id'] ) ) {
			$param['users_id'] = explode( ',', $param['users_id'] );
		}

		if ( ! is_array( $param['users_id'] ) ) {
			$param['users_id'] = array();
		}

		$tasks = Task_Class::g()->get_tasks( $param );

		if ( $param['frontend'] ) {
			\eoxia\View_Util::exec( 'task-manager', 'task', 'frontend/main', array(
				'tasks' => $tasks,
				'with_wrapper' => $param['with_wrapper'],
			) );
		} else {
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/main', array(
				'tasks' => $tasks,
				'with_wrapper' => $param['with_wrapper'],
			) );
		}
	}
}

new Task_Shortcode();
