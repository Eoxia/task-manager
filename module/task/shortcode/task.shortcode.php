<?php
/**
 * Gestion des shortcodes en relation des tâches.
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
 * Gestion des shortcodes en relation des tâches.
 */
class Task_Shortcode {

	/**
	 * Ce constructeur ajoute le shortcode suivant:
	 *
	 * - task_manager_dashboard_content
	 */
	public function __construct() {
		add_shortcode( 'task_manager_dashboard_content', array( $this, 'callback_task_manager_dashboard_content' ) );
	}

	public function callback_task_manager_dashboard_content() {
		$tasks = Task_Class::g()->get( array(
			'post_parent' => 0,
		) );

		View_Util::exec( 'task', 'backend/main', array(
			'tasks' => $tasks,
		) );
	}
}

new Task_Shortcode();
