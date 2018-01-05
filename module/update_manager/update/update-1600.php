<?php
/**
 * Mise à jour des données pour la version 1.6.0
 *
 * @author Eoxia
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mise à jour des données pour la version 1.6.0
 */
class Update_160 {
	/**
	 * Limite de mise à jour des éléments par requêtes.
	 *
	 * @var integer
	 */
	private $limit = 20;
	/**
	 * Instanciate update for current version
	 */
	public function __construct() {
		add_action( 'wp_ajax_task_manager_update_1600_calcul_number_points', array( $this, 'callback_task_manager_update_1600_calcul_number_points' ) );
		add_action( 'wp_ajax_task_manager_update_1600_points', array( $this, 'callback_task_manager_update_1600_points' ) );

		add_action( 'wp_ajax_task_manager_update_1600_calcul_number_comments', array( $this, 'callback_task_manager_update_1600_calcul_number_comments' ) );
		add_action( 'wp_ajax_task_manager_update_1600_comments', array( $this, 'callback_task_manager_update_1600_comments' ) );
	}

	/**
	 * Récupères le nombre de points
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_calcul_number_points() {
		global $wpdb;

		$count_points = (int) $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT count(COMMENT.comment_ID) FROM {$wpdb->comments} AS COMMENT
				JOIN {$wpdb->posts} AS TASK
					ON TASK.ID = COMMENT.comment_post_ID
				WHERE
					COMMENT.comment_parent=%d AND
					COMMENT.comment_approved=%s AND
					COMMENT.comment_type=%s AND
					TASK.post_type=%s",
				array(
					0,
					'-34070',
					'',
					Task_Class::g()->get_post_type(),
				)
			)
		);

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				'countPoint'      => $count_points,
				'moreDescription' => '(0/' . $count_points . ')',
				'more'            => true,
			),
		) );
	}

	/**
	 * Récupères les commentaires et y ajoutes un type et fait la mise à jour de la meta "elapsed".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_points() {
		$done          = false;
		$count_point   = ! empty( $_POST['args']['countPoint'] ) ? (int) $_POST['args']['countPoint'] : 0;
		$index         = ! empty( $_POST['args']['index'] ) ? (int) $_POST['args']['index'] : 0;

		global $wpdb;

		$point_ids = $wpdb->get_col(
			$wpdb->prepare(
				"
				SELECT COMMENT.comment_ID FROM {$wpdb->comments} AS COMMENT
				JOIN {$wpdb->posts} AS TASK
					ON TASK.ID = COMMENT.comment_post_ID
				WHERE
					COMMENT.comment_parent=%d AND
					COMMENT.comment_approved=%s AND
					COMMENT.comment_type=%s AND
					TASK.post_type=%s
				LIMIT %d, %d",
				array(
					0,
					'-34070',
					'',
					Task_Class::g()->get_post_type(),
					$index,
					$this->limit,
				)
			)
		);

		$points = Point_Class::g()->get( array(
			'comment__in' => $point_ids,
			'status'      => '-34070',
		) );

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				if ( ! is_array( $point->time_info['elapsed'] ) ) {
					\eoxia\LOG_Util::log( 'Update 1600 point #' . $point->id . ' elapsed value : ' . $point->time_info['elapsed'], 'task-manager' );
				}
				$point->time_info['elapsed'] = array( $point->time_info['elapsed'] );
				\eoxia\LOG_Util::log( 'Update 1600 points (' . $index . '/' . $count_point . '): ' . json_encode( $point ), 'task-manager' );
				Point_Class::g()->update( $point );
			}
		}

		$index += $this->limit;

		if ( $index >= $count_point ) {
			$index = $count_point;
			$done  = true;
		}

		wp_send_json_success( array(
			'done' => $done,
			'args' => array(
				'index'           => $index,
				'countPoint'      => $count_point,
				'moreDescription' => '(' . $index . '/' . $count_point . ')',
				'doneDescription' => $done ? __( 'Update point type and elapsed time', 'task-manager' ) . '(' . $index . '/' . $count_point . ')' : '',
				'resetArgs'       => $done ? true : false,
				'more'            => true,
			),
		) );
	}

	/**
	 * Récupères le nombre de commentaire
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_calcul_number_comments() {
		global $wpdb;

		$count_comment = (int) $wpdb->get_var(
			$wpdb->prepare(
				"
				SELECT count(COMMENT.comment_ID) FROM {$wpdb->comments} AS COMMENT
				JOIN {$wpdb->posts} AS TASK
					ON TASK.ID = COMMENT.comment_post_ID
				WHERE
					COMMENT.comment_parent!=%d AND
					COMMENT.comment_approved=%s AND
					COMMENT.comment_type=%s AND
					TASK.post_type=%s",
				array(
					0,
					'-34070',
					'',
					Task_Class::g()->get_post_type(),
				)
			)
		);

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				'countComment'    => $count_comment,
				'moreDescription' => '(0/' . $count_comment . ')',
				'more'            => true,
			),
		) );
	}

	/**
	 * Récupères les commentaires et y ajoutes un type et fait la mise à jour de la meta "elapsed".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_comments() {
		$done          = false;
		$count_comment = ! empty( $_POST['args']['countComment'] ) ? (int) $_POST['args']['countComment'] : 0;
		$index         = ! empty( $_POST['args']['index'] ) ? (int) $_POST['args']['index'] : 0;

		global $wpdb;

		$comment_ids = $wpdb->get_col(
			$wpdb->prepare(
				"
				SELECT COMMENT.comment_ID FROM {$wpdb->comments} AS COMMENT
				JOIN {$wpdb->posts} AS TASK
					ON TASK.ID = COMMENT.comment_post_ID
				WHERE
					COMMENT.comment_parent!=%d AND
					COMMENT.comment_approved=%s AND
					COMMENT.comment_type=%s AND
					TASK.post_type=%s
				LIMIT %d, %d",
				array(
					0,
					'-34070',
					'',
					Task_Class::g()->get_post_type(),
					$index,
					$this->limit,
				)
			)
		);

		$comments = Task_Comment_Class::g()->get( array(
			'comment__in' => $comment_ids,
			'status'      => '-34070',
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->time_info['elapsed'] = (array) $comment->time_info['elapsed'];
				Task_Comment_Class::g()->update( $comment );
			}
		}

		$index += $this->limit;

		if ( $index >= $count_comment ) {
			$index = $count_comment;
			$done  = true;
		}

		wp_send_json_success( array(
			'done' => $done,
			'args' => array(
				'index'           => $index,
				'countComment'    => $count_comment,
				'moreDescription' => '(' . $index . '/' . $count_comment . ')',
				'doneDescription' => $done ? __( 'Update comment type and elapsed time', 'task-manager' ) . '(' . $index . '/' . $count_comment . ')' : '',
				'resetArgs'       => $done ? true : false,
				'more'            => true,
			),
		) );
	}

}

new Update_160();
