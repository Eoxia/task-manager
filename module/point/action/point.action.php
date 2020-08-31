<?php
/**
 *  Les actions relatives aux points.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux points
 */
class Point_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_edit_point', array( $this, 'ajax_edit_point' ) );
		add_action( 'wp_ajax_edit_created_date', array( $this, 'callback_edit_created_date' ) );
		add_action( 'wp_ajax_change_date_point', array( $this, 'ajax_change_date_point' ) );
		add_action( 'wp_ajax_delete_point', array( $this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_order_point', array( $this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_complete_point', array( $this, 'ajax_complete_point' ) );
		add_action( 'wp_ajax_load_point', array( $this, 'ajax_load_point' ) );

		add_action( 'wp_ajax_search_task', array( $this, 'ajax_search_task' ) );
		add_action( 'wp_ajax_move_point_to', array( $this, 'ajax_move_point_to' ) );
		add_action( 'wp_ajax_update_statut_task', array( $this, 'update_statut_task' ) );

		add_action( 'wp_ajax_update_statut_task', array( $this, 'ajax_load_task_if_user_option' ) );
	}

	/**
	 * Met à jour le contenu d'un point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.8.0
	 */
	public function ajax_edit_point() {
		check_ajax_referer( 'edit_point' );

		$point_id  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$completed = ( isset( $_POST['completed'] ) && 'true' === $_POST['completed'] ) ? true : false; // WPCS: CSRF ok.
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content   = ! empty( $_POST['content'] ) ? $_POST['content'] : '';
		$toggle    = ( isset( $_POST['toggle'] ) && 'true' == $_POST['toggle'] ) ? true : false;
		$notif      = ( isset( $_POST['notif'] ) && ! empty( $_POST['notif'] ) ) ? $_POST['notif']  : array();



		$data  = Point_Class::g()->edit_point( $point_id, $parent_id, $content, $completed );
		$point = $data['point'];
		$task  = $data['task'];

		$first_edit_meta = '';

		if ( ! empty( $point_id ) ) {
			$first_edit_meta = get_comment_meta( $point_id, '_tm_first_edit_point', true );
		}

		if ( ! empty( $point_id ) && $first_edit_meta != -1 ) {
			$first_edit_meta = 1;
			update_comment_meta( $point_id, '_tm_first_edit_point', -1 );
		}

		do_action( 'tm_edit_point', $point, $task );

		$task = Task_Class::g()->get( array( 'id' => $parent_id ), true );

		if( ! empty( $notif ) ){
			Notify_Class::g()->send_notification_followers_are_tags( $notif, $task->data['id'], $point->data['id'], 0 );
		}

		if ( ! empty( $task->data['user_info']['affected_id'] ) && $first_edit_meta == 1 ) {
			foreach ( $task->data['user_info']['affected_id'] as $affected_id ) {
				if ( $affected_id != get_current_user_id() ) {
					Notify_Class::g()->add_notification( $affected_id, get_current_user_id(), $task->data['user_info']['affected_id'], $point->data['id'], 'point', TM_NOTIFY_CREATE_TASK );
				}
			}
		}

		ob_start();
		if ( ! $toggle ) {
			Point_Class::g()->display( $parent_id );
		} else {
			Task_Class::g()->display_bodies( array( $point ) );
		}

		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => 'taskManager',
				'module'           => 'newPoint',
				'callback_success' => ! empty( $point_id ) ? 'editedPointSuccess' : 'addedPointSuccess',
				'task_id'          => $parent_id,
				'task'             => $task,
				'point'            => $point,
				'toggle'           => $toggle,
			)
		);
	}

	/**
	 * Change la date du point
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 *
	 * @todo: nonce
	 */
	public function ajax_change_date_point() {
		$point_id   = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$mysql_date = ! empty( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';

		if ( empty( $point_id ) || empty( $mysql_date ) ) {
			wp_send_json_error();
		}

		$point = Point_Class::g()->get(
			array(
				'id' => $point_id,
			),
			true
		);

		$point->date = $mysql_date;

		Point_Class::g()->update( $point );

		wp_send_json_success();
	}

	/**
	 * Change la date de création d'un projet.
	 *
	 * @return void
	 *
	 * @since 3.0.2
	 * @version 3.0.2
	 */
	public function callback_edit_created_date() {
//		check_ajax_referer( 'edit_created_date' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$created_date   = ! empty( $_POST['created_date'] ) ?  sanitize_text_field( $_POST['created_date'] ) : current_time( 'mysql' );

		$point = point_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);

		$point->data['date'] = $created_date;

		Point_Class::g()->update( $point );
		wp_send_json_success();
	}

	/**
	 * Supprimes le point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_delete_point() {
		check_ajax_referer( 'delete_point' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$point = Point_Class::g()->get(
			array(
				'id' => $point_id,
			),
			true
		);

		$point->data['status'] = 'trash';

		Point_Class::g()->update( $point->data );

		$task = Task_Class::g()->get(
			array(
				'id' => $point->data['post_id'],
			),
			true
		);

		$task->data['time_info']['elapsed'] = $task->data['time_info']['elapsed'] - $point->data['time_info']['elapsed'];

		if ( $point->data['completed'] ) {
			$task->data['count_completed_points']--;
		} else {
			$task->data['count_uncompleted_points']--;
		}

		$task = Task_Class::g()->update( $task->data );

		$time_task = $task->data['time_info']['elapsed'];

		do_action( 'tm_delete_point', $point );

		wp_send_json_success(
			array(
				'time'             => $time_task,
				'namespace'        => 'taskManager',
				'module'           => 'point',
				'callback_success' => 'deletedPointSuccess',
				'point'            => $point,
			)
		);
	}

	/**
	 * Modifie l'ordre des points dans une tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 * @todo nonce
	 */
	public function ajax_edit_order_point() {
		$task_id        = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$order_point_id = ! empty( $_POST['order_point_id'] ) ? (array) $_POST['order_point_id'] : array();

		if ( empty( $task_id ) || empty( $order_point_id ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $order_point_id ) ) {
			foreach ( $order_point_id as $key => $point_id ) {
				$point = Point_Class::g()->get(
					array(
						'id' => $point_id,
					),
					true
				);

				$point->data['order'] = (int) $key;
				Point_Class::g()->update( $point->data );
			}
		}

		wp_send_json_success();
	}

	/**
	 * Complete or uncomplete a point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_complete_point() {
		check_ajax_referer( 'edit_point' );

		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$point_id  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$complete  = ( isset( $_POST['complete'] ) && 'true' === $_POST['complete'] ) ? true : false;
		$comment   = ( isset( $_POST['comment'] ) && 'true' === $_POST['comment'] ) ? true : false;
		$by_prompt = ( isset( $_POST['by_prompt'] ) && 'true' === $_POST['by_prompt'] ) ? true : false;

		if ( $comment ) {
			$content = ! empty( $_POST['content'] ) ? trim( $_POST['content'] ) : '';
			$time    = ! empty( $_POST['time'] ) ? (int) $_POST['time'] : 0;

			Task_Comment_Class::g()->edit_comment( $parent_id, $point_id, $content, '', $time );

		}

		if ( $by_prompt ) {
			Point_Class::g()->complete_point( $point_id, $complete );

			$task = Task_Class::g()->get( array( 'id' => $parent_id ), true );

			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task', array(
				'task' => $task,
			) );
			wp_send_json_success( array(
				'namespace'        => 'taskManager',
				'module'           => 'point',
				'callback_success' => 'completedWithPrompt',
				'id'               => $parent_id,
				'view'             => ob_get_clean(),
			) );
		} else {
			$point = Point_Class::g()->complete_point( $point_id, $complete);

			$task = Task_Class::g()->get( array( 'id' => $parent_id ), true );

			if ( ! empty( $task->data['user_info']['affected_id'] ) && $complete ) {
				foreach ( $task->data['user_info']['affected_id'] as $affected_id ) {
					if ( $affected_id != get_current_user_id() ) {
						Notify_Class::g()->add_notification( $affected_id, get_current_user_id(), $task->data['user_info']['affected_id'], $point_id, 'point', TM_NOTIFY_ACTION_COMPLETE );
					}
				}
			}

			wp_send_json_success( array(
				'completed' => $point->data['completed'],
			) );
		}
	}

	/**
	 * Charges les points complétés d'une tâche et renvoie la vue.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 */
	public function ajax_load_point() {
		check_ajax_referer( 'load_point' );

		$task_id   = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$frontend  = ( isset( $_POST['frontend'] ) && 'true' == $_POST['frontend'] ) ? true : false;
		$completed = ! empty( $_POST['point_state'] ) ? sanitize_text_field( $_POST['point_state'] ) : 'uncompleted';
		$completed = ( 'completed' === $completed ) ? true : false;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$points = Point_Class::g()->get(
			array(
				'post_id'    => $task_id,
				'type'       => Point_Class::g()->get_type(),
				'meta_key'   => '_tm_completed',
				'meta_value' => $completed,
			)
		);

		$view = 'backend';
		if ( $frontend ) {
			$view = 'frontend';
		}

		ob_start();
		/*\eoxia\View_Util::exec(
			'task-manager',
			'point',
			$view . '/points',
			array(
				'comment_id' => 0,
				'point_id'   => 0,
				'parent_id'  => $task_id,
				'points'     => $points,
			)
		);*/
		Point_Class::g()->display( $task_id, false, 0, $completed );
		wp_send_json_success(
			array(
				'namespace'        => $frontend ? 'taskManagerFrontend' : 'taskManager',
				'module'           => 'newPoint',
				'callback_success' => 'loadedPointSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Recherche dans les tâches avec le term $_GET['term'].
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_search_task() {
		$term          = sanitize_text_field( $_GET['term'] );
		$founded_by_id = false;

		$query = new \WP_Query(
			array(
				'post_type'   => 'wpeo-task',
				's'           => $term,
				'post_status' => array( 'publish', 'draft', 'archive' ),
			)
		);

		$tasks_founded = array();

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				if ( $post->ID == $term ) {
					$founded_by_id = true;
				}

				$tasks_founded[] = array(
					'label' => '#' . $post->ID . ' ' . $post->post_title,
					'value' => '#' . $post->ID . ' ' . $post->post_title,
					'id'    => $post->ID,
				);
			}
		}

		$term = (int) $term;
		if ( ! $founded_by_id && ! empty( $term ) && is_int( $term ) ) {
			$post = get_post( $term );

			if ( ! empty( $post ) ) {
				if ( 'wpeo-task' === $post->post_type ) {
					$tasks_founded[] = array(
						'label' => '#' . $post->ID . ' ' . $post->post_title,
						'value' => '#' . $post->ID . ' ' . $post->post_title,
						'id'    => $post->ID,
					);
				}
			}
		}

		if ( empty( $tasks_founded ) ) {
			$tasks_founded[] = array(
				'label' => __( 'No task found', 'task-manager' ),
				'value' => __( 'No task found', 'task-manager' ),
				'id'    => 0,
			);
		}

		wp_die( wp_json_encode( $tasks_founded ) );
	}

	/**
	 * Déplaces le point vers la tache parent "to_element_id".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_move_point_to() {
		check_ajax_referer( 'move_point_to' );

		$task_id    = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id   = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$to_task_id = ! empty( $_POST['to_task_id'] ) ? (int) $_POST['to_task_id'] : 0;

		if ( empty( $task_id ) || empty( $point_id ) || empty( $to_task_id ) ) {
			wp_send_json_error();
		}

		$current_task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$to_task = Task_Class::g()->get(
			array(
				'id' => $to_task_id,
			),
			true
		);

		$point = Point_Class::g()->get(
			array(
				'id' => $point_id,
			),
			true
		);

		$current_task->data['time_info']['elapsed'] = $current_task->data['time_info']['elapsed'] - $point->data['time_info']['elapsed'];
		$to_task->data['time_info']['elapsed']      = $to_task->data['time_info']['elapsed'] + $point->data['time_info']['elapsed'];

		if ( $point->data['completed'] ) {
			$point->data['order'] = $to_task->data['count_completed_points'];
			$current_task->data['count_completed_points']--;
			$to_task->data['count_completed_points']++;
		} else {
			$point->data['order'] = $to_task->data['count_uncompleted_points'];
			$current_task->data['count_uncompleted_points']--;
			$to_task->data['count_uncompleted_points']++;
		}

		$current_task = Task_Class::g()->update( $current_task->data );
		$to_task      = Task_Class::g()->update( $to_task->data );

		$point->data['post_id'] = $to_task_id;
		$point                  = Point_Class::g()->update( $point->data );

		$comments = Task_Comment_Class::g()->get(
			array(
				'post_id' => $task_id,
				'parent'  => $point_id,
				'status'  => 1,
			)
		);

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				if ( 0 !== $comment->data['id'] ) {
					$comment->data['post_id'] = $to_task_id;

					Task_Comment_Class::g()->update( $comment->data );
				}
			}
		}

		do_action( 'tm_after_move_point_to', $point, $current_task );

		wp_send_json_success(
			array(
				'namespace'                 => 'taskManager',
				'module'                    => 'point',
				'callback_success'          => 'movedPointTo',
				'point'                     => $point,
				'current_task'              => $current_task,
				'to_task'                   => $to_task,
				'current_task_elapsed_time' => $current_task->data['time_info']['elapsed'],
				'to_task_elapsed_time'      => $to_task->data['time_info']['elapsed'],
			)
		);
	}

	public function update_statut_task(){
		check_ajax_referer( 'update_statut_task' );

		$id     = isset( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : 0;
		$statut = isset( $_POST[ 'statut' ] ) ? sanitize_text_field( $_POST[ 'statut' ] ) : '';

		if( ! $id || ! $statut ){
			wp_send_json_error( 'id ou statut invalide' );
		}
		$task = Point_Class::g()->update(
			array(
				'id'     => $task_id,
				'status' => $statut
			)
		);

	}
}

new Point_Action();
