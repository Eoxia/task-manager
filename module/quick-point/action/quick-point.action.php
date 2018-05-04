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

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$quick_point_filter = new Quick_Point_Filter();
		add_filter( 'tm_point_before', array( $quick_point_filter, 'callback_tm_point_before' ) );
		add_filter( 'tm_point_after', array( $quick_point_filter, 'callback_point_after' ) );

		$point = Point_Class::g()->get(array(
			'id' => '0',
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-content', array(
			'task_id' => $task_id,
			'point'   => $point,
		) );
		$modal_content_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-input-valid' );
		$modal_buttons_view = ob_get_clean();

		wp_send_json_success( array(
			'view'         => $modal_content_view,
			'buttons_view' => $modal_buttons_view,
		) );
	}
}
new Quick_Point_Action();
