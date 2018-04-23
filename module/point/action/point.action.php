<?php
/**
 *  Les actions relatives aux points.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
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
		add_action( 'wp_ajax_change_date_point', array( $this, 'ajax_change_date_point' ) );
		add_action( 'wp_ajax_delete_point', array( $this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_order_point', array( $this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_complete_point', array( $this, 'ajax_complete_point' ) );
		add_action( 'wp_ajax_load_completed_point', array( $this, 'ajax_load_completed_point' ) );

		add_action( 'wp_ajax_search_task', array( $this, 'ajax_search_task' ) );
		add_action( 'wp_ajax_move_point_to', array( $this, 'ajax_move_point_to' ) );
	}

	/**
	 * Met à jour le contenu d'un point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_edit_point() {
		check_ajax_referer( 'edit_point' );

		$point_id  = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content   = ! empty( $_POST['content'] ) ? $_POST['content'] : '';

		$content = str_replace( '<div>', '<br>', trim( $content ) );
		$content = wp_kses( $content, array(
			'br' => array(),
			'tooltip' => array(
				'class' => array(),
			)
		) );

		if ( empty( $parent_id ) ) {
			wp_send_json_error();
		}

		$point_args = array(
			'id'      => $point_id,
			'post_id' => $parent_id,
			'content' => $content,
		);

		if ( 0 === $point_id ) {
			$task = Task_Class::g()->get( array(
				'id' => $parent_id,
			), true );

			$task->data['count_uncompleted_points']++;

			Task_Class::g()->update( $task->data );

			$point_args['order'] = ( $task->data['count_uncompleted_points'] - 1 ); // - 1 car la valeur est incrémenté juste avant.
		}

		$point = Point_Class::g()->update( $point_args, true );

		$point->data['content'] = stripslashes( $point->data['content'] );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
			'point'      => $point,
			'parent_id'  => $parent_id,
			'point_id'   => 0,
			'comment_id' => 0,
		) );

		wp_send_json_success( array(
			'view'             => ob_get_clean(),
			'namespace'        => 'taskManager',
			'module'           => 'point',
			'callback_success' => ! empty( $point_id ) ? 'editedPointSuccess' : 'addedPointSuccess',
		) );
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

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

		$point->date = $mysql_date;

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

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

		$point->data['status'] = 'trash';

		Point_Class::g()->update( $point->data );

		$task = Task_Class::g()->get( array(
			'id' => $point->data['post_id'],
		), true );

		$task->data['time_info']['elapsed'] = $task->data['time_info']['elapsed'] - $point->data['time_info']['elapsed'];

		if ( $point->data['completed'] ) {
			$task->data['count_completed_points']--;
		} else {
			$task->data['count_uncompleted_points']--;
		}

		$task = Task_Class::g()->update( $task->data );

		do_action( 'tm_delete_point', $point );

		wp_send_json_success( array(
			'time'             => \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] ),
			'namespace'        => 'taskManager',
			'module'           => 'point',
			'callback_success' => 'deletedPointSuccess',
		) );
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
				$point = Point_Class::g()->get( array(
					'id' => $point_id,
				), true );

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
		check_ajax_referer( 'complete_point' );

		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$complete = ( isset( $_POST['complete'] ) && 'true' === $_POST['complete'] ) ? true : false;

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

		$task = Task_Class::g()->get( array(
			'id' => $point->data['post_id'],
		), true );

		$point->data['completed'] = $complete;

		if ( $complete ) {
			$point->data['order'] = $task->data['count_completed_points'];

			$task->data['count_completed_points']++;
			$task->data['count_uncompleted_points']--;
			$point->data['time_info']['completed_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		} else {
			$point->data['order'] = $task->count_uncompleted_points;

			$task->data['count_completed_points']--;
			$task->data['count_uncompleted_points']++;
			$point->data['time_info']['uncompleted_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		}

		Point_Class::g()->update( $point->data );
		Task_Class::g()->update( $task->data );

		do_action( 'tm_complete_point', $point );

		wp_send_json_success();
	}

	/**
	 * Charges les points complétés d'une tâche et renvoie la vue.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 */
	public function ajax_load_completed_point() {
		check_ajax_referer( 'load_completed_point' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$points = Point_Class::g()->get( array(
			'post_id'    => $task_id,
			'type'       => Point_Class::g()->get_type(),
			'meta_key'   => '_tm_completed',
			'meta_value' => true,
		) );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/points', array(
			'comment_id' => 0,
			'point_id'   => 0,
			'parent_id'  => $task_id,
			'points'     => $points,
		) );
		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'point',
			'callback_success' => 'loadedCompletedPoint',
			'view'             => ob_get_clean(),
		) );
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

		$query = new \WP_Query( array(
			'post_type'   => 'wpeo-task',
			's'           => $term,
			'post_status' => array( 'publish', 'draft', 'archive' ),
		) );

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

		$current_task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$to_task = Task_Class::g()->get( array(
			'id' => $to_task_id,
		), true );

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

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

		$comments = Task_Comment_Class::g()->get( array(
			'post_id' => $task_id,
			'parent'  => $point_id,
			'status'  => 1,
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				if ( 0 !== $comment->data['id'] ) {
					$comment->data['post_id'] = $to_task_id;

					Task_Comment_Class::g()->update( $comment->data );
				}
			}
		}

		do_action( 'tm_after_move_point_to', $point, $current_task );

		wp_send_json_success( array(
			'namespace'                 => 'taskManager',
			'module'                    => 'point',
			'callback_success'          => 'movedPointTo',
			'point'                     => $point,
			'current_task'              => $current_task,
			'to_task'                   => $to_task,
			'current_task_elapsed_time' => \eoxia\Date_Util::g()->convert_to_custom_hours( $current_task->data['time_info']['elapsed'] ),
			'to_task_elapsed_time'      => \eoxia\Date_Util::g()->convert_to_custom_hours( $to_task->data['time_info']['elapsed'] ),
		) );
	}
}

new Point_Action();
