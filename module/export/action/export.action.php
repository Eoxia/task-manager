<?php
/**
 * Les actions relatives a l'export des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.1
 * @version 1.5.1
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives a l'export des tâches.
 */
class Export_Action {
	/**
	 * Initialise l'export des tâches.
	 *
	 * @since 1.5.1
	 * @version 1.5.1
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_export_popup', array( $this, 'callback_load_export_popup' ) );
		add_action( 'wp_ajax_export_task', array( $this, 'callback_export_task' ) );
	}

	/**
	 * Exportes les points de la tâche dans un fichier .txt
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.5.1
	 */
	public function callback_export_task() {
		check_ajax_referer( 'export_task' );

		$response         = array(
			'namespace'        => 'taskManager',
			'module'           => 'taskExport',
			'callback_success' => 'exportedTask',
		);
		$export_file      = false;
		$task_id          = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$export_type      = ! empty( $_POST['export_type'] ) ? (string) $_POST['export_type'] : 0;
		$date_from        = ! empty( $_POST['date_from'] ) ? (string) $_POST['date_from'] : null;
		$date_to          = ! empty( $_POST['date_to'] ) ? (string) $_POST['date_to'] : null;
		$include_comments = ! empty( $_POST['include_comments'] ) && ( 'true' === $_POST['include_comments'] ) ? (bool) $_POST['include_comments'] : false;
		$display_id       = ! empty( $_POST['display_id'] ) && ( 'true' === $_POST['display_id'] ) ? (bool) $_POST['display_id'] : false;

		if ( empty( $task_id ) ) {
			wp_send_json_error( array( __( 'No task selected for export', 'task-manager' ) ) );
		}

		$build_args = array(
			'with_comments' => $include_comments,
			'with_id'       => $display_id,
		);

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$get_args = array(
			'post_id'  => $task->data['id'],
			'orderby'  => 'meta_value_num',
			'order'    => 'ASC',
			'meta_key' => '_tm_order',
			'status'   => 1,
		);

		if ( 'by_date' === $export_type && ( null !== $date_from || null !== $date_to ) ) {
			$query = $GLOBALS['wpdb']->prepare(
				"SELECT GROUP_CONCAT( DISTINCT( P.comment_id ) ) AS pointsID
				FROM {$GLOBALS['wpdb']->comments} AS P
					JOIN {$GLOBALS['wpdb']->posts} AS T ON ( T.ID=P.comment_post_id )
					LEFT JOIN {$GLOBALS['wpdb']->comments} AS C ON ( C.comment_parent = P.comment_id )
				WHERE P.comment_parent = %d
					AND T.post_type = %s
					AND P.comment_post_id = %d
					AND ( ( P.comment_date >= %s AND P.comment_date <= %s )
						OR ( C.comment_date >= %s AND C.comment_date <= %s ) )",
				0,
				Task_Class::g()->get_type(),
				$task->data['id'],
				$date_from . ' 00:00:00',
				$date_to . ' 23:59:59',
				$date_from . ' 00:00:00',
				$date_to . ' 23:59:59'
			);

			$get_args['comment__in'] = explode( ',', $GLOBALS['wpdb']->get_var( $query ) );

			$build_args['date_query'] = array(
				'inclusive' => true,
			);
			if ( null !== $date_from && null !== $date_to ) {
				$build_args['date_query']['relation'] = 'AND';
			}
			if ( null !== $date_from ) {
				$build_args['date_query']['after'] = array(
					'year'  => substr( $date_from, 0, 4 ),
					'month' => substr( $date_from, 5, 2 ),
					'day'   => substr( $date_from, 8, 2 ),
				);
			}
			if ( null !== $date_to ) {
				$build_args['date_query']['before'] = array(
					'year'  => substr( $date_to, 0, 4 ),
					'month' => substr( $date_to, 5, 2 ),
					'day'   => substr( $date_to, 8, 2 ),
				);
			}
		}

		$points              = Point_Class::g()->get( $get_args );

		$cumul_time = 0;
		if( ! empty( $points ) ){
			foreach( $points as $point ){
				$cumul_time += $point->data[ 'time_info' ][ 'elapsed' ];
			}
		}

		$response['content'] = Export_Class::g()->build_data( $task, $points, $build_args );

		$response['time'] = $cumul_time;

		if ( $export_file ) {
			$exported_file        = Export_Class::g()->export_to_file( $task, $exported_data );
			$response['url']      = $exported_file['url'];
			$response['filename'] = $exported_file['name'];
		}

		wp_send_json_success( $response );
	}

	/**
	 * Charge la popu permettant de paramètrer l'export d'une tâche
	 *
	 * @method callback_load_export_popup
	 *
	 * @since 1.5.1
	 * @version 1.5.1
	 *
	 * @return void
	 */
	public function callback_load_export_popup() {
		check_ajax_referer( 'load_export_popup' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'export',
			'main',
			array(
				'task_id'   => $id,
				'from_date' => \eoxia\Date_Util::g()->fill_date( date( 'Y-m-d H:i:s', strtotime( 'first day of this month' ) ) ),
				'to_date'   => \eoxia\Date_Util::g()->fill_date( date( 'Y-m-d H:i:s', strtotime( 'last day of this month' ) ) ),
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'taskExport',
				'callback_success' => 'loadedExportPopup',
				'view'             => ob_get_clean(),
				'buttons_view'     => '',
			)
		);
	}

}

new Export_Action();
