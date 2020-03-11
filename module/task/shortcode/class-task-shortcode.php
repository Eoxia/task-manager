<?php
/**
 * Gestion des shortcodes en relation des tâches.
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
 * Gestion des shortcodes en relation des tâches.
 */
class Task_Shortcode {

	/**
	 * Constructeur
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'task', array( $this, 'callback_task' ) );
	}

	/**
	 * Le shortcode pour afficher les tâches
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param  array $param Les paramètres du shortcode.
	 *
	 * @return HTML Le code HTML permettant d'afficher une tâche.
	 */
	public function callback_task( $param ) {
		$element_per_page = get_user_meta( get_current_user_id(), '_tm_task_per_page', true );
		$element_per_page = empty( $element_per_page ) ? 10 : $element_per_page;

		$param = shortcode_atts(
			array(
				'id'              => 0,
				'point_id'        => 0,
				'task_id'		  => 0,
				'categories_id'   => array(),
				'users_id'        => array(),
				'term'            => '',
				'status'          => 'any',
				'offset'          => 0,
				'post_parent'     => 0,
				'not_parent_type' => array(),
				// 'post_parent'    => 0,
				'posts_per_page'  => $element_per_page,
				'with_wrapper'    => 1,
			),
			$param,
			'task'
		);

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

		if ( ! is_array( $param['id'] ) && ! empty( $param['id'] ) ) {
			$param['task_id'] = $param['id'];
		}

		if ( ! is_array( $param['point_id'] ) && ! empty( $param['point_id'] ) ) {
			$param['point_id'] = $param['point_id'];
		}

		if ( ! is_array( $param['not_parent_type'] ) && ! empty( $param['not_parent_type'] ) ) {
			$param['not_parent_type'] = explode( ',', $param['not_parent_type'] );
		}

		$with_wrapper = false;
		if ( 1 === $param['with_wrapper'] ) {
			$with_wrapper = true;
		}

		$tasks        = Task_Class::g()->get_tasks( $param );
		$number_tasks = Task_Class::g()->get_tasks( $param, true );

		ob_start();
		if ( ! is_admin() ) {
			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'frontend/main',
				array(
					'tasks'        => $tasks,
					'number_tasks' => $number_tasks,
					'with_wrapper' => $with_wrapper,
				)
			);
		} elseif ( ! empty( $tasks ) ) {
			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/main',
				array(
					'tasks'        => $tasks,
					'number_tasks' => $number_tasks,
					'with_wrapper' => $with_wrapper,
				)
			);
		} else {
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/search-results-empty'
			);
		}

		return ob_get_clean();
	}
}

new Task_Shortcode();
