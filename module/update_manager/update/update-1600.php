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
	private $limit = 100;
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
		$timestamp_debut = microtime(true);
		$done        = false;
		$count_point = ! empty( $_POST['args']['countPoint'] ) ? (int) $_POST['args']['countPoint'] : 0;
		$index       = ! empty( $_POST['args']['index'] ) ? (int) $_POST['args']['index'] : 0;

		global $wpdb;
		$task_schema = Task_Class::g()->get_schema();

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
					0,
					$this->limit,
				)
			)
		);

		$points = array();

		if ( ! empty( $point_ids ) ) {
			$points = Point_Class::g()->get( array(
				'comment__in' => $point_ids,
				'status'      => '-34070',
			) );
		}

		$count_point_updated = get_option( '_tm_update_1600_point_updated', true );

		if ( empty( $count_point_updated ) ) {
			$count_point_updated = 0;
		}

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				$point->type           = Point_Class::g()->get_type();
				$point->completed      = $point->point_info['completed'];
				$point->order          = $this->search_position( $point );
				$point->count_comments = 0;

				if ( ! empty( $point->post_id ) ) {
					$comments = Task_Comment_Class::g()->get( array(
						'post_id' => $point->post_id,
						'parent'  => $point->id,
						'status'  => '-34070',
					) );

					if ( ! empty( $comments ) ) {
						$point->count_comments = count( $comments );
					}
				}

				$point = Point_Class::g()->update( $point );

				if ( Point_Class::g()->get_type() !== $point->type ) {
					\eoxia\LOG_Util::log( 'Point #' . $point->id . ' type is not equal wpeo_point', 'task-manager' );
				} else {
					$count_point_updated++;
					update_option( '_tm_update_1600_point_updated', $count_point_updated );
				}

				if ( $point->completed ) {
					$count_completed_point = get_post_meta( $point->post_id, $task_schema['count_completed_points']['field'], true );

					if ( empty( $count_completed_point ) ) {
						$count_completed_point = 0;
					}

					$count_completed_point++;
					update_post_meta( $point->post_id, $task_schema['count_completed_points']['field'], $count_completed_point );
				} else {
					$count_uncompleted_point = get_post_meta( $point->post_id, $task_schema['count_uncompleted_points']['field'], true );

					if ( empty( $count_uncompleted_point ) ) {
						$count_uncompleted_point = 0;
					}

					$count_uncompleted_point++;
					update_post_meta( $point->post_id, $task_schema['count_uncompleted_points']['field'], $count_uncompleted_point );
				}
			}
		}

		$index += $this->limit;

		if ( $index >= $count_point ) {
			$index = $count_point;
			$done  = true;
		}

		$timestamp_fin = microtime(true);
		$difference_ms = $timestamp_fin - $timestamp_debut;
		\eoxia\LOG_Util::log( $difference_ms, 'task-manager' );

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
<<<<<<< HEAD
		$timestamp_debut = microtime(true);
=======
		$start_func = microtime( true );

>>>>>>> d6b3fa926587d8e0107952e030d7a45160d87410
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
					0,
					$this->limit,
				)
			)
		);

		$comments = array();

		if ( ! empty( $comment_ids ) ) {
			$comments = Task_Comment_Class::g()->get( array(
				'comment__in' => $comment_ids,
				'status'      => '-34070',
			) );
		}

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$start_ms = microtime( true );
				$comment->type = Task_Comment_Class::g()->get_type();
<<<<<<< HEAD
				$comment       = Task_Comment_Class::g()->update( $comment );

				if ( Task_Comment_Class::g()->get_type() !== $comment->type ) {
					\eoxia\LOG_Util::log( 'Comment #' . $comment->id . ' type is not equal wpeo_time', 'task-manager' );
				}
=======
				Task_Comment_Class::g()->update( $comment );
				$end_ms = microtime( true );
				\eoxia\LOG_Util::log( 'Comment #' . $comment->id . ' done in ' . ( $end_ms - $start_ms ), 'task-manager' );
>>>>>>> d6b3fa926587d8e0107952e030d7a45160d87410
			}
		}

		$index += $this->limit;

		if ( $index >= $count_comment ) {
			$index = $count_comment;
			$done  = true;
		}
		$end_func = microtime( true );
		\eoxia\LOG_Util::log( 'Comment function done in ' . ( $end_func - $start_func ), 'task-manager' );

		$timestamp_fin = microtime(true);
		$difference_ms = $timestamp_fin - $timestamp_debut;
		\eoxia\LOG_Util::log( 'Update comment: ' . $difference_ms, 'task-manager' );

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

	/**
	 * Recherches la position du point dans le tableau "order_point_id" de la tâche.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @param  Point_Model $point Les données du point.
	 * @return integer            La position du point.
	 */
	public function search_position( $point ) {
		$position = false;

		$task = Task_Class::g()->get( array(
			'id' => $point->post_id,
		), true );

		if ( empty( $task ) ) {
			$position = false;
		} else {
<<<<<<< HEAD
			$position = array_search( $point->id, $task->task_info['order_point_id'] );
=======
			$position = array_search( $point->id, (int) $task->task_info['order_point_id'], true );
>>>>>>> d6b3fa926587d8e0107952e030d7a45160d87410
		}

		if ( false === $position ) {
			\eoxia\LOG_Util::log( 'No order for the point #' . $point->id . ' setted to 0 in task #' . $task->id, 'task-manager' );
			$position = 0;
		}

		return $position;
	}

}

new Update_160();
