<?php
/**
 * Le fichier action du module Quick-Point.
 *
 * @author ||||||||
 * @since 1.6.1
 * @version 1.6.1
 * @copyright 2018+
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action of Quick_Point.
 */
class Quick_Point_Action {

	/**
	 * Constructor
	 *
	 * @since 1.6.1
	 * @version 1.6.1
	 */
	public function __construct() {
			add_action( 'wp_ajax_quick_add_comment', array( $this, 'callback_quick_add_comment' ) );
	}

	/**
	 * Ajoute une function qui charge les vues .
	 * vu du btn validé + modal vue principale .
	 *
	 * @since 1.6.1
	 * @version 1.6.1
	 */
	public function callback_quick_add_comment() {
		check_ajax_referer( 'quick_add_comment' );
		$quictime_filter = new Quick_Point_Filter();
		add_filter( 'tm_point_before', array( $quictime_filter, 'callback_tm_point_before' ) );
		add_filter( 'tm_point_after', array( $quictime_filter, 'input_text' ) );
		$point   = Point_Class::g()->get(array(
			'id' => '0',
		), true );
		$task_id = $_POST['task_id'];

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal', array(
			'task_id' => $task_id,
			'point'   => $point,
		) );
		$ob_clean_modal = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick-point', 'modal-input-valid' );
		$ob_clean_modal_btn = ob_get_clean();


		wp_send_json_success( array(
			'view'         => $ob_clean_modal,
			'buttons_view' => $ob_clean_modal_btn,
		) );
	}
}
new Quick_Point_Action();
