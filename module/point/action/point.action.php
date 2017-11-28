<?php
/**
 *  Les actions relatives aux points.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
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
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		/** Point */
		add_action( 'wp_ajax_edit_point', array( $this, 'ajax_edit_point' ) );
		add_action( 'wp_ajax_change_date_point', array( $this, 'ajax_change_date_point' ) );
		add_action( 'wp_ajax_delete_point', array( $this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_order_point', array( $this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_complete_point', array( $this, 'ajax_complete_point' ) );
		add_action( 'wp_ajax_load_completed_point', array( $this, 'ajax_load_completed_point' ) );

		add_action( 'wp_ajax_load_point_properties', array( $this, 'ajax_load_point_properties' ) );
		add_action( 'wp_ajax_search_task', array( $this, 'ajax_search_task' ) );
		add_action( 'wp_ajax_move_point_to', array( $this, 'ajax_move_point_to' ) );
	}

	/**
	 * Met à jour le contenu d'un point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function ajax_edit_point() {
		check_ajax_referer( 'edit_point' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content = ! empty( $_POST['content'] ) ? $_POST['content'] : '';

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

		$point = Point_Class::g()->update( array(
			'id' => $point_id,
			'post_id' => $parent_id,
			'content' => $content,
		) );

		$point->content = stripslashes( $point->content );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
			'point' => $point,
			'parent_id' => $parent_id,
			'point_id' => 0,
			'comment_id' => 0,
		) );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManager',
			'module' => 'point',
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
	*/
	public function ajax_change_date_point() {
		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
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
	 * @version 1.5.0
	 */
	public function ajax_delete_point() {
		check_ajax_referer( 'delete_point' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

		$point->status = 'trash';

		Point_Class::g()->update( $point );

		$task = Task_Class::g()->get( array(
			'id' => $point->post_id,
		), true );

		if( ( $key = array_search( $point_id, $task->task_info['order_point_id'] ) ) !== false ) {
			array_splice( $task->task_info['order_point_id'], $key, 1 );
		}
		$task->time_info['elapsed'] -= $point->time_info['elapsed'];
		$task = Task_Class::g()->update( $task );

		do_action( 'tm_delete_point', $point );

		wp_send_json_success( array(
			'time' => \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'] ),
			'namespace' => 'taskManager',
			'module' => 'point',
			'callback_success' => 'deletedPointSuccess',
		) );
	}

	/**
	 * Modifie l'ordre des points dans une tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 * @todo nonce
	 */
	public function ajax_edit_order_point() {
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$order_point_id = ! empty( $_POST['order_point_id'] ) ? (array) $_POST['order_point_id'] : array();

		if ( empty( $task_id ) || empty( $order_point_id ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $order_point_id ) ) {
			foreach ( $order_point_id as $key => $point_id ) {
				$order_point_id[ (int) $key ] = (int) $point_id;
			}
		}

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$points = Point_Class::g()->get( array(
			'post_id' => $task->id,
			'orderby' => 'comment__in',
			'comment__in' => $task->task_info['order_point_id'],
			'status' => -34070,
		) );

		$points_completed = array_filter( $points, function( $point ) {
			return true === $point->point_info['completed'];
		} );

		$new_order_point_id = array();

		if ( ! empty( $order_point_id ) ) {
			foreach ( $order_point_id as $key => $point_id ) {
				$point = Point_Class::g()->get( array(
					'id' => $point_id,
				), true );
				if ( 'trash' !== $point->status ) {
					$new_order_point_id[ (int) $key ] = (int) $point->id;
				}
			}
		}

		if ( ! empty( $points_completed ) ) {
			foreach ( $points_completed as $point_completed ) {
				if ( 'trash' !== $point_completed->status ) {
					$new_order_point_id[] = (int) $point_completed->id;
				}
			}
		}

		$task->task_info['order_point_id'] = $new_order_point_id;
		Task_Class::g()->update( $task );

		wp_send_json_success();
	}

	/**
	 * Complete or uncomplete a point.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function ajax_complete_point() {
		check_ajax_referer( 'complete_point' );

		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$complete = ( isset( $_POST['complete'] )  && 'true' === $_POST['complete'] ) ? true : false;

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
			'status' => '-34070',
		), true );

		$point->point_info['completed'] = $complete;

		if ( $complete ) {
			$point->time_info['completed_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		} else {
			$point->time_info['uncompleted_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		}

		Point_Class::g()->update( $point );

		do_action( 'tm_complete_point', $point );

		wp_send_json_success();
	}

	/**
	 * Charges les points complétés d'une tâche et renvoie la vue.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_completed_point() {
		check_ajax_referer( 'load_completed_point' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$completed_points = array();

		if ( ! empty( $task->task_info['order_point_id'] ) ) {
			$points = Point_Class::g()->get( array(
				'post_id' => $task->id,
				'orderby' => 'comment__in',
				'comment__in' => $task->task_info['order_point_id'],
				'status' => -34070,
			) );

			$completed_points = array_filter( $points, function( $point ) {
				return true === $point->point_info['completed'];
			} );
		}

		ob_start();
		if ( ! empty( $completed_points ) ) {
			foreach ( $completed_points as $point ) {
				\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
					'parent_id' => $task->id,
					'point' => $point,
					'point_id' => 0,
					'comment_id' => 0,
				) );
			}
		}
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'point',
			'callback_success' => 'loadedCompletedPoint',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Charges les propriétés du point et renvoie la vue à la réponse AJAX.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_point_properties() {
		check_ajax_referer( 'load_point_properties' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$point = Point_Class::g()->get( array(
			'comment__in' => array( $point_id ),
			'status' => -34070,
		), true );

		$point->author = Follower_Class::g()->get( array(
			'include' => array( $point->author_id ),
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/properties', array(
			'point' => $point,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'view' => ob_get_clean(),
			'module' => 'point',
			'callback_success' => 'loadedPointProperties',
		) );
	}

	/**
	 * Recherche dans les tâches avec le term $_GET['term'].
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_search_task() {
		$term = sanitize_text_field( $_GET['term'] );
		$founded_by_id = false;

		$query = new \WP_Query( array(
			'post_type' => 'wpeo-task',
			's' => $term,
			'post_status' => array( 'publish', 'draft', 'archive' ),
		) );

		$tasks_founded = array();

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				if ( $post->ID == $term ) {
					$founded_by_id = true;
				}

				$tasks_founded[] = array(
					'label' => '#' . $post->ID . ' - ' . $post->post_title,
					'value' => $post->post_title,
					'id' => $post->ID,
				);
			}
		}

		$term = (int) $term;
		if ( !$founded_by_id && ! empty( $term ) && is_int( $term ) ) {
			$post = get_post( $term );

			if ( ! empty( $post ) ) {
				if ( 'wpeo-task' === $post->post_type ) {
					$tasks_founded[] = array(
						'label' => '#' . $post->ID . ' - ' . $post->post_title,
						'value' => $post->post_title,
						'id' => $post->ID,
					);
				}
			}
		}

		if ( empty( $tasks_founded ) ) {
			$tasks_founded[] = array(
				'label' => __( 'No task found', 'task-manager' ),
				'value' => __( 'No task found', 'task-manager' ),
				'id' => 0,
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
	 * @version 1.5.0
	 */
	public function ajax_move_point_to() {
		check_ajax_referer( 'move_point_to' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
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
			'comment__in' => array( $point_id ),
			'status' => -34070,
		), true );

		$key = array_search( $point_id, $current_task->task_info['order_point_id'], true );

		if ( false !== $key ) {
			array_splice( $current_task->task_info['order_point_id'], $key, 1 );
			$current_task->time_info['elapsed'] -= $point->time_info['elapsed'];
		} else {
			wp_send_json_error();
		}

		$to_task->task_info['order_point_id'][] = (int) $point_id;

		$to_task->time_info['elapsed'] += $point->time_info['elapsed'];

		$current_task = Task_Class::g()->update( $current_task );
		$to_task = Task_Class::g()->update( $to_task );

		$point->post_id = $to_task_id;
		$point = Point_Class::g()->update( $point );

		$comments = Task_Comment_Class::g()->get( array(
			'post_id' => $task_id,
			'parent' => $point_id,
			'status' => -34070,
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				if ( 0 !== $comment->id ) {
					$comment->post_id = $to_task_id;

					Task_Comment_Class::g()->update( $comment );
				}
			}
		}

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'point',
			'callback_success' => 'movedPointTo',
			'point' => $point,
			'current_task' => $current_task,
			'to_task' => $to_task,
		) );
	}
}

new Point_Action();
