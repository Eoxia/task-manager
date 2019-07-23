<?php
/**
 * Action principale de Quick Point
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action principale de Quick Point
 */
class Quick_Point_Action {

	/**
	 * Constructeur
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 */
	public function __construct() {
			add_action( 'wp_ajax_load_modal_quick_point', array( $this, 'callback_load_modal_quick_point' ) );
			add_action( 'tm_edit_point', array( $this, 'callback_tm_edit_point' ), 10, 2 );
	}

	/**
	 * Charges le contenu de la modal Quick Point
	 *
	 * Ajoutes deux filtres pour ajouter une checkbox et un input type text
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 */
	public function callback_load_modal_quick_point() {
		check_ajax_referer( 'load_modal_quick_point' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$reload  = ( isset( $_POST['reload'] ) && 'true' == $_POST['reload'] ) ? true : false;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$quick_point_filter = new Quick_Point_Filter();
		add_filter( 'tm_point_before', array( $quick_point_filter, 'callback_tm_point_before' ), 10, 2 );
		add_filter( 'tm_point_after', array( $quick_point_filter, 'callback_point_after' ), 10, 2 );

		$point = Point_Class::g()->get(
			array(
				'schema' => 'true',
			),
			true
		);// 28/06/2019

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'quick-point',
			'modal-quickpoint',
			array(
				'point'       => $point,
				'parent_id'   => $task_id,
				'comment_id'  => 0,
				'point_id'    => $point->data['id'],
				'quick_point' => true,
			)
		);
		$modal_content_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-input-valid' );
		$modal_buttons_view = ob_get_clean();

		$response = array(
			'view'         => $modal_content_view,
			'buttons_view' => $modal_buttons_view,
		);

		if ( $reload ) {
			$response['namespace']        = 'taskManager';
			$response['module']           = 'quickPoint';
			$response['callback_success'] = 'reloadModal';
		}

		wp_send_json_success( $response );
	}

	/**
	 * Le callback quand on a créer un nouveau point depuis l'interface des points rapides.
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 *
	 * @param  Point_Model $point Les données du nouveau point.
	 * @param  Task_Model  $task  Les données de la tâche.
	 *
	 * @return void
	 */
	public function callback_tm_edit_point( $point, $task ) {
		$tm_point_is_quick_point = ( isset( $_POST['tm_point_is_quick_point'] ) && 'true' == $_POST['tm_point_is_quick_point'] ) ? true : false;

		if ( $tm_point_is_quick_point ) {
			$comment = Task_Comment_Class::g()->get(
				array(
					'parent' => $point->data['id'],
					'number' => 1,
				),
				true
			);

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'point',
				'backend/point',
				array(
					'point'      => $point,
					'parent_id'  => $task->data['id'],
					'point_id'   => 0,
					'comment_id' => 0,
				)
			);
			$point_view = ob_get_clean();

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'quick-point',
				'modal-success',
				array(
					'task'    => $task,
					'point'   => $point,
					'comment' => $comment,
				)
			);
			$modal_view = ob_get_clean();

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'quick-point',
				'modal-success-buttons',
				array(
					'task' => $task,
				)
			);
			$modal_buttons_view = ob_get_clean();

			wp_send_json_success(
				array(
					'view'               => $point_view,
					'modal_view'         => $modal_view,
					'modal_buttons_view' => $modal_buttons_view,
					'namespace'          => 'taskManager',
					'module'             => 'quickPoint',
					'callback_success'   => 'addedPointSuccess',
					'task_id'            => $task->data['id'],
					'task'               => $task,
					'point'              => $point,
				)
			);
		}
	}

}
new Quick_Point_Action();
