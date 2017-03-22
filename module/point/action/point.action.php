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
		add_action( 'wp_ajax_complete_point', array( $this, 'ajax_complete_point' ) );
		add_action( 'wp_ajax_load_completed_point', array( $this, 'ajax_load_completed_point' ) );
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
}

new Point_Action();
