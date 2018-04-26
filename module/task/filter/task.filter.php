<?php
/**
 * Les filtres relatives aux tâches.
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
 * Les filtres relatives aux tâches.
 */
class Task_Filter {

	/**
	 * Constructeur
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_filter( 'task_manager_dashboard_title', array( $this, 'callback_dashboard_title' ) );
		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ), 12 );
		add_filter( 'task', array( $this, 'callback_dashboard_content' ), 10, 2 );

		add_filter( 'task_header_action', array( $this, 'callback_task_header_action' ), 10, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_elapsed' ), 11, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_button' ), 20, 2 );

		/** Window */
		add_filter( 'task_window_sub_header_task_controller', array( $this, 'callback_task_window_sub_header' ), 10, 2 );
		add_filter( 'task_window_information_task_controller', array( $this, 'callback_task_window_information' ), 10, 2 );
		add_filter( 'task_window_action_task_controller', array( $this, 'callback_task_window_action' ), 10, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_window_footer' ), 10, 2 );
	}

	public function callback_dashboard_title( $string ) {
		$url = wp_nonce_url( add_query_arg( array( 'action' => 'create_task' ), admin_url( 'admin-post.php' ) ), 'wpeo_nonce_create_task' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/button-add', array(
			'url' => $url,
		) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_dashboard_filter( $string ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/filter-tab' );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_dashboard_content( $string, $post_parent ) {
		if ( $post_parent == 0 ) {
			$list_task = Task_Class::g()->get( array( 'post_parent' => 0,
			'meta_query' => array(
				array(
					'key' => 'wpeo_task',
					'value' => '{"user_info":{"owner_id":' . get_current_user_id(),
						'compare' => 'like',
					)
				)
				)
			);
		}
		else {
			$list_task = Task_Class::g()->get( array( 'post_parent' => $post_parent ) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/list-task', array(
			'list_task' => $list_task,
		) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_header_action( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header-button', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_header_information_elapsed( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/time-elapsed', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_header_information_button( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/information-button', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}
}

new Task_Filter();
