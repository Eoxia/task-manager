<?php
/**
 *  Les actions relatives aux points.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

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
		add_action( 'wp_ajax_delete_point', array( $this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_order_point', array( $this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_complete_point', array( $this, 'ajax_complete_point' ) );
		add_action( 'wp_ajax_load_completed_point', array( $this, 'ajax_load_completed_point' ) );

		add_action( 'wp_ajax_load_point_properties', array( $this, 'ajax_load_point_properties' ) );
		add_action( 'wp_ajax_search_task', array( $this, 'ajax_search_task' ) );
		add_action( 'wp_ajax_move_point_to', array( $this, 'ajax_move_point_to' ) );
	}

	/**
	 * Update the content of a point.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_edit_point() {
		check_ajax_referer( 'edit_point' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$content = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : '';

		if ( empty( $parent_id ) || empty( $content ) ) {
			wp_send_json_error();
		}

		$point = Point_Class::g()->update( array(
			'id' => $point_id,
			'post_id' => $parent_id,
			'content' => $content,
		) );

		ob_start();
		View_Util::exec( 'point', 'backend/point', array(
			'point' => $point,
			'parent_id' => $parent_id,
		) );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'module' => 'point',
			'callback_success' => 'addedPointSuccess',
		) );
	}


	/**
	 * Supprimes le point.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_delete_point() {
		check_ajax_referer( 'delete_point' );

		$point_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$point = Point_Class::g()->get( array(
			'id' => $point_id,
		), true );

		$point->status = 'trash';

		Point_Class::g()->update( $point );

		wp_send_json_success( array(
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
				$order_point_id[ $key ] = (int) $point_id;
			}
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
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
				$new_order_point_id[ $key ] = $point_id;
			}
		}

		if ( ! empty( $points_completed ) ) {
			foreach ( $points_completed as $point_completed ) {
				$new_order_point_id[] = $point_completed->id;
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
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_complete_point() {
		check_ajax_referer( 'complete_point' );

		$point_id = ! empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$complete = ( isset( $_POST['complete'] )  && 'true' === $_POST['complete'] ) ? true : false;

		$point = Point_Class::g()->get( array(
			'comment__in' => array( $point_id ),
			'status' => '-34070',
		), true );

		$point->point_info['completed'] = $complete;

		if ( $complete ) {
			$point->time_info['completed_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		} else {
			$point->time_info['uncompleted_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		}

		Point_Class::g()->update( $point );

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
			'post__in' => array( $task_id ),
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
				View_Util::exec( 'point', 'backend/point', array(
					'parent_id' => $task->id,
					'point' => $point,
				) );
			}
		}
		wp_send_json_success( array(
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

		ob_start();
		View_Util::exec( 'point', 'backend/properties', array(
			'point' => $point,
		) );

		wp_send_json_success( array(
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

		$query = new \WP_Query( array(
			'post_type' => 'wpeo-task',
			's' => $term,
			'post_status' => array( 'publish', 'draft', 'archive' ),
		) );

		$tasks_founded = array();

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$tasks_founded[] = array(
					'label' => $post->post_title,
					'value' => $post->post_title,
					'id' => $post->ID,
				);
			}
		}

		wp_die( wp_json_encode( $tasks_founded ) );
	}

	/**
	 * Déplaces le point vers la tache parent "to_element_id".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
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
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$to_task = Task_Class::g()->get( array(
			'post__in' => array( $to_task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$key = array_search( $point_id, $current_task->task_info['order_point_id'], true );

		if ( false !== $key ) {
			unset( $current_task->task_info['order_point_id'][ $key ] );
		} else {
			wp_send_json_error();
		}

		$to_task->task_info['order_point_id'][] = $point_id;

		Task_Class::g()->update( $current_task );
		Task_Class::g()->update( $to_task );

		$point = Point_Class::g()->get( array(
			'comment__in' => array( $point_id ),
			'status' => -34070,
		), true );

		$point->post_id = $to_task_id;

		Point_Class::g()->update( $point );

		wp_send_json_success( array(
			'module' => 'point',
			'callback_success' => 'movedPointTo',
		) );
	}
}

new Point_Action();
