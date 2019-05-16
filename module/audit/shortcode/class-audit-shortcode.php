<?php
/**
 * Gestion des shortcodes en relation des audits.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des shortcodes en relation des audits.
 */
class Audit_Shortcode {

		/**
		 * Constructeur
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 */
		public function __construct() {
			add_shortcode( 'audit', array( $this, 'callback_audit' ) );
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
		public function callback_audit( $param ) {

			$param = shortcode_atts(
				array(
					'id'				 => 0,
					// 'post_parent'    => 0,
					'posts_per_page' => \eoxia\Config_Util::$init['task-manager']->task->posts_per_page,
					'with_wrapper'   => 1,
				),
				$param,
				'task'
			);


			if ( ! is_array( $param['id'] ) && ! empty( $param['id'] ) ) {
				$param['task_id'] = $param['id'];
			}

			$with_wrapper = false;
			if ( 1 === $param['with_wrapper'] ) {
				$with_wrapper = true;
			}

			$tasks = Task_Class::g()->get_tasks( $param );

			ob_start();
			if ( ! is_admin() ) {
				\eoxia\View_Util::exec(
					'task-manager',
					'task',
					'frontend/main',
					array(
						'tasks'        => $tasks,
						'with_wrapper' => $with_wrapper,
					)
				);
			} else {
				\eoxia\View_Util::exec(
					'task-manager',
					'task',
					'backend/main',
					array(
						'tasks'        => $tasks,
						'with_wrapper' => $with_wrapper,
					)
				);
			}

			return ob_get_clean();
		}
}

new Audit_Shortcode();
