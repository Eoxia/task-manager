<?php
/**
 * Les actions relatives aux temps rapides.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux temps rapides.
 */
class Quick_Time_Action {

	/**
	 * Initialise les actions liées aux temps rapides.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );

		add_action( 'wp_ajax_quick_time_add_comment', array( $this, 'ajax_quick_time_add_comment' ) );

		add_action( 'wp_ajax_open_setting_quick_time', array( $this, 'ajax_open_setting_quick_time' ) );
		add_action( 'wp_ajax_quick_task_setting_refresh_point', array( $this, 'ajax_quick_task_setting_refresh_point' ) );
		add_action( 'wp_ajax_add_config_quick_time', array( $this, 'ajax_add_config_quick_time' ) );
		add_action( 'wp_ajax_remove_config_quick_time', array( $this, 'ajax_remove_config_quick_time' ) );
	}

	/**
	 * Initialise la metabox pour afficher les temps rapides.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_admin_init() {
		add_meta_box( 'tm-indicator-quick-task', __( 'Quick time', 'task-manager' ), array( Quick_Time_Class::g(), 'display' ), 'task-manager-indicator', 'normal' );
	}

	/**
	 * Ajoutes des commentaires
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function ajax_quick_time_add_comment() {
		check_ajax_referer( 'quick_time_add_comment' );

		$comments = ! empty( $_POST['comments'] ) ? (array) $_POST['comments'] : array();

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment['task_id']  = (int) $comment['task_id'];
				$comment['point_id'] = (int) $comment['point_id'];
				$comment['content']  = sanitize_text_field( $comment['content'] );
				$comment['time']     = (int) $comment['time'];

				$comment = Task_Comment_Class::g()->update( array(
					'post_id'   => $comment['task_id'],
					'parent_id' => $comment['point_id'],
					'content'   => $comment['content'],
					'time_info' => array(
						'elapsed' => $comment['time'],
					),
				) );
			}
		}

		ob_start();
		Quick_Time_Class::g()->display_list();
		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'quickTime',
			'callback_success' => 'quickTimeAddedComment',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Appel la méthode "get_setting_quick_time" de Quick_Time_Class pour récupérer les configurations de temps rapide de l'utilisateur.
	 * Puis renvoie la vue à la modaL.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function ajax_open_setting_quick_time() {
		$quicktimes = Quick_Time_Class::g()->get_quicktimes();
		sort( $quicktimes );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/setting/main', array(
			'quicktimes' => $quicktimes,
		) );
		$view = ob_get_clean();
		wp_send_json_success( array(
			'view' => $view,
		) );
	}

	/**
	 * Récupères les points selon l'id de la tâche.
	 * Renvoies la vue du <select>.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 * @todo: 18/12/2017: nonce
	 */
	public function ajax_quick_task_setting_refresh_point() {
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		if ( empty( $task ) || empty( $task->task_info['order_point_id'] ) ) {
			wp_send_json_error();
		}

		$points = Point_Class::g()->get( array(
			'post_id'     => $task->id,
			'orderby'     => 'comment__in',
			'comment__in' => $task->task_info['order_point_id'],
			'status'      => -34070,
		) );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/setting/points', array(
			'points' => $points,
		) );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'quickTime',
			'callback_success' => 'settingRefreshedPoint',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Ajoutes le commentaire dans le tableau "$meta_quick_time" de la meta de l'utilisateur.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function ajax_add_config_quick_time() {
		check_ajax_referer( 'add_config_quick_time' );

		$task_id  = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$content  = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';

		if ( empty( $task_id ) || empty( $point_id ) ) {
			wp_send_json_error();
		}

		$meta = get_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, true );

		$data = array(
			'task_id'   => $task_id,
			'point_id'  => $point_id,
			'content'   => $content,
			'displayed' => array(),
		);

		$meta[] = $data;

		update_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, $meta );

		$data = quicktime_format_data( $data );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/setting/item', array(
			'key'        => $task_id . '-' . $point_id,
			'quick_time' => $data,
		) );
		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'quickTime',
			'callback_success' => 'addedConfigQuickTime',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Supprimes le commentaire dans le tableau "$meta_quick_time" de la meta de l'utilisateur.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function ajax_remove_config_quick_time() {
		check_ajax_referer( 'remove_config_quick_time' );

		$task_id  = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		if ( empty( $task_id ) || empty( $point_id ) ) {
			wp_send_json_error();
		}

		$quicktimes = get_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, true );

		if ( ! empty( $quicktimes ) ) {
			foreach ( $quicktimes as $key => $quicktime ) {
				if ( $quicktime['task_id'] === $task_id && $quicktime['point_id'] === $point_id ) {
					$quicktimes = array_splice( $quicktimes, $key, 1 );
				}
			}
		}

		update_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, $meta );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'quickTime',
			'callback_success' => 'deletedConfigQuickTime',
		) );
	}
}

new Quick_Time_Action();