<?php
/**
 * Initialise les actions liées à la barre de recherche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage action
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Les actions relatives aux tâches.
 */
class Search_Bar_Action {

	/**
	 * Initialise les actions liées à la barre de recherche.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_all_task', array( $this, 'callback_load_all_task' ) );
		add_action( 'wp_ajax_load_my_task', array( $this, 'callback_load_my_task' ) );
		add_action( 'wp_ajax_load_archived_task', array( $this, 'ajax_load_archived_task' ) );
	}

	/**
	 * Charges toutes les tâches expecté celles qui sont archivées.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_load_all_task() {
		check_ajax_referer( 'load_all_task' );

		ob_start();
		echo do_shortcode( '[task_manager_dashboard_content]' );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'module' => 'task',
			'callback_success' => 'loadedAllTask',
		) );
	}

	/**
	 * Charges toutes les tâches au l'utilisateur courant est affecté soit en tant que responsable ou followers.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_load_my_task() {
		check_ajax_referer( 'load_my_task' );

		ob_start();

		$tasks = Task_Class::g()->get( array(
			'post_parent' => 0,
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation'	=> 'OR',
				array(
					'key' => 'wpeo_task',
					'value' => '{"user_info":{"owner_id":' . get_current_user_id() . ',',
					'compare' => 'like',
				),
				array(
					'key'			=> 'wpeo_task',
					'value'		=> '"affected_id":[' . get_current_user_id() . ']',
					'compare'	=> 'like',
				),
				array(
					'key'			=> 'wpeo_task',
					'value'		=> '"affected_id":[' . get_current_user_id() . ',',
					'compare'	=> 'like',
				),
				array(
					'key'			=> 'wpeo_task',
					'value'		=> '"affected_id":\\[[0-9,]+,' . get_current_user_id() . '\\]',
					'compare'	=> 'REGEXP',
				),
				array(
					'key'			=> 'wpeo_task',
					'value'		=> '"affected_id":\\[[0-9,]+,' . get_current_user_id() . '[0-9,]+\\]',
					'compare'	=> 'REGEXP',
				),
			),
		) );

		View_Util::exec( 'task', 'backend/main', array(
			'tasks' => $tasks,
		) );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'module' => 'searchBar',
			'callback_success' => 'loadedMyTask',
		) );
	}

	/**
	 * Charges toutes les tâches archivées
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_archived_task() {
		check_ajax_referer( 'load_archived_task' );

		$tasks = Task_Class::g()->get( array(
			'post_status' => 'archive',
		) );

		ob_start();
		View_Util::exec( 'task', 'backend/main', array(
			'tasks' => $tasks,
		) );

		wp_send_json_success( array(
			'module' => 'tag',
			'callback_success' => 'loadedArchivedTask',
			'view' => ob_get_clean(),
		) );
	}
}

new Search_Bar_Action();
