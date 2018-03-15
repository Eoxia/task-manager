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
	private $limit = 50;

	/**
	 * Le constructeur
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_task_manager_update_1600_calcul_number_points', array( $this, 'callback_task_manager_update_1600_calcul_number_points' ) );
		add_action( 'wp_ajax_task_manager_update_1600_points', array( $this, 'callback_task_manager_update_1600_points' ) );

		add_action( 'wp_ajax_task_manager_update_1600_calcul_number_comments', array( $this, 'callback_task_manager_update_1600_calcul_number_comments' ) );
		add_action( 'wp_ajax_task_manager_update_1600_comments', array( $this, 'callback_task_manager_update_1600_comments' ) );
		add_action( 'wp_ajax_task_manager_update_1600_comment_status', array( $this, 'callback_task_manager_update_1600_comment_status' ) );
		add_action( 'wp_ajax_task_manager_update_1600_lost_datas', array( $this, 'callback_task_manager_update_1600_lost_datas' ) );

		add_action( 'wp_ajax_task_manager_update_1600_history_time', array( $this, 'callback_task_manager_update_1600_history_time' ) );
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

		$count_points = (int) $wpdb->get_var( $this->prepare_request( 'count( COMMENT.comment_ID )', true, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.

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
		$timestamp_debut = microtime( true );
		$done            = false;
		$count_point     = ! empty( $_POST['args']['countPoint'] ) ? (int) $_POST['args']['countPoint'] : 0;
		$index           = ! empty( $_POST['args']['index'] ) ? (int) $_POST['args']['index'] : 0;

		global $wpdb;
		$task_schema = Task_Class::g()->get_schema();

		$count_point_updated = get_option( '_tm_update_1600_point_updated', true );
		if ( empty( $count_point_updated ) ) {
			$count_point_updated = 0;
		}

		$points = $wpdb->get_results( $this->prepare_request( 'COMMENT.comment_ID, COMMENT.comment_approved, COMMENT.comment_content', true, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.
		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				$the_point = Point_Class::g()->update( array(
					'id'      => (int) $point->comment_ID,
					'type'    => Point_Class::g()->get_type(),
					'content' => $point->comment_content,
					'status'  => ( '-34071' === $point->comment_approved ? 'trash' : '1' ),
				) );

				$the_point->data['completed']      = $the_point->data['point_info']['completed'];
				$the_point->data['order']          = $this->search_position( $the_point );
				$the_point->data['count_comments'] = 0;

				if ( ! empty( $the_point->data['post_id'] ) ) {
					$comments = Task_Comment_Class::g()->get( array(
						'post_id' => $the_point->data['post_id'],
						'parent'  => $the_point->data['id'],
						'status'  => '-34070',
					) );

					if ( ! empty( $comments ) ) {
						$the_point->data['count_comments'] = count( $comments );
					}
				}

				$the_point = Point_Class::g()->update( $the_point->data );

				if ( Point_Class::g()->get_type() !== $the_point->data['type'] ) {
					\eoxia\LOG_Util::log( 'Comment #' . $the_point->data['id'] . ' type is not equal wpeo_point', 'task-manager' );
				} else {
					$count_point_updated++;
					update_option( '_tm_update_1600_point_updated', $count_point_updated );
				}

				if ( $the_point->data['completed'] ) {
					$count_completed_point = get_post_meta( $the_point->data['post_id'], $task_schema['count_completed_points']['field'], true );

					if ( empty( $count_completed_point ) ) {
						$count_completed_point = 0;
					}

					$count_completed_point++;
					update_post_meta( $the_point->data['post_id'], $task_schema['count_completed_points']['field'], $count_completed_point );
				} else {
					$count_uncompleted_point = get_post_meta( $the_point->data['post_id'], $task_schema['count_uncompleted_points']['field'], true );

					if ( empty( $count_uncompleted_point ) ) {
						$count_uncompleted_point = 0;
					}

					$count_uncompleted_point++;
					update_post_meta( $the_point->data['post_id'], $task_schema['count_uncompleted_points']['field'], $count_uncompleted_point );
				}
			}
		}

		$index += $this->limit;

		if ( $index >= $count_point ) {
			$index = $count_point;
			$done  = true;
		}

		$timestamp_fin = microtime( true );
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
	 * [prepare_request description]
	 *
	 * @param  [type] $column_to_get [description]
	 * @param  [type] $paginate      [description]
	 * @param  [type] $comparison    [description]
	 *
	 * @return [type]                [description]
	 */
	public function prepare_request( $column_to_get, $paginate, $comparison, $type ) {
		global $wpdb;

		$query_string = "SELECT {$column_to_get}
		 FROM {$wpdb->comments} AS COMMENT
			JOIN {$wpdb->posts} AS TASK ON TASK.ID = COMMENT.comment_post_ID
		 WHERE COMMENT.comment_parent {$comparison} %d
			AND COMMENT.comment_approved IN ( '-34070', '-34071' )
			AND COMMENT.comment_type IN ( '', %s )
			AND TASK.post_type = %s";

		if ( ! empty( $paginate ) ) {
			$query_string .= ' LIMIT 0, ' . $this->limit;
		}

		$query = $wpdb->prepare( $query_string, array(
			0,
			$type,
			Task_Class::g()->get_type(),
		) );

		return $query;
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

		$count_comment = (int) $wpdb->get_var( $this->prepare_request( 'count(COMMENT.comment_ID)', 0, '!=', Task_Comment_Class::g()->get_type() ) );// WPCS: unprepared sql.

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
		$timestamp_debut = microtime( true );
		$done            = false;
		$count_comment   = ! empty( $_POST['args']['countComment'] ) ? (int) $_POST['args']['countComment'] : 0;
		$index           = ! empty( $_POST['args']['index'] ) ? (int) $_POST['args']['index'] : 0;

		global $wpdb;

		$comments = $wpdb->get_results( $this->prepare_request( 'COMMENT.comment_ID, COMMENT.comment_approved, COMMENT.comment_content', true, '!=', Task_Comment_Class::g()->get_type() ) ); // WPCS: unprepared sql.
		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$the_comment = Task_Comment_Class::g()->update( array(
					'id'      => (int) $comment->comment_ID,
					'type'    => Task_Comment_Class::g()->get_type(),
					'content' => $comment->comment_content,
					'status'  => ( '-34071' === $comment->comment_approved ? 'trash' : '1' ),
				) );

				if ( Task_Comment_Class::g()->get_type() !== $the_comment->data['type'] ) {
					\eoxia\LOG_Util::log( 'Comment #' . $the_comment->data['id'] . ' type is not equal wpeo_time', 'task-manager' );
				}
			}
		}

		$index += $this->limit;

		if ( $index >= $count_comment ) {
			$index = $count_comment;
			$done  = true;
		}

		$timestamp_fin = microtime( true );
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
	 * Récupère les metas des "history_time" qui contiennent la valeur input_date
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_history_time() {
		global $wpdb;
		$history_time_todo = 0;
		$done_history_time = 0;

		$history_time_to_repair = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id, meta_value FROM {$wpdb->commentmeta} WHERE meta_key LIKE %s AND meta_value LIKE %s", $wpdb->esc_like( 'wpeo_history_time' ), '%' . $wpdb->esc_like( 'date_input' ) . '%' ) );

		if ( ! empty( $history_time_to_repair ) ) {
			$history_time_todo = count( $history_time_todo );
			foreach ( $history_time_to_repair as $meta_definition ) {
				$meta = json_decode( $meta_definition->meta_value, true );
				if ( ! empty( $meta ) && ! empty( $meta['due_date'] ) && ! empty( $meta['due_date']['date_input'] ) ) {
					$meta['due_date']   = $meta['due_date']['date_input']['date'];
					$done_history_time += $wpdb->update( $wpdb->commentmeta, array( 'meta_value' => wp_json_encode( $meta ) ), array( 'meta_id' => $meta_definition->meta_id ) );
				}
			}
		}

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				// Translators: 1. Number of history time updated. 2. Number of history time to update.
				'doneDescription' => sprintf( __( '%1$s history_time have been treated on %2$s', 'task-manager' ), $done_history_time, $history_time_todo ),
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
			'id' => $point->data['post_id'],
		), true );

		if ( empty( $task ) ) {
			$position = false;
		} else {
			$position = array_search( $point->data['id'], $task->data['task_info']['order_point_id'] );
		}

		if ( false === $position ) {
			\eoxia\LOG_Util::log( 'No order for the point #' . $point->data['id'] . ' setted to 0 in task #' . $task->data['id'] . '(' . wp_json_encode( $task->data['task_info']['order_point_id'] ) . ')', 'task-manager' );
			$position = 0;
		}

		return $position;
	}

	/**
	 * Modifie les valeurs de comment approved dans la base de données pour utiliser les valeurs de base de WordPress.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_comment_status() {
		global $wpdb;
		$point_updated        = 0;
		$comment_updated      = 0;
		$history_time_updated = 0;

		// Mise à jour des -34070 en 1.
		$comment_updated      += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => Task_Comment_Class::g()->get_type(),
		) );
		$point_updated        += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => Point_Class::g()->get_type(),
		) );
		$history_time_updated += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => History_Time_Class::g()->get_type(),
		) );

		// Mise à jour des -34071 en trash.
		$comment_updated      += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => Task_Comment_Class::g()->get_type(),
		) );
		$point_updated        += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => Point_Class::g()->get_type(),
		) );
		$history_time_updated += $wpdb->update( $wpdb->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => History_Time_Class::g()->get_type(),
		) );

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				// Translators: 1. The point number treated by the update manager. 2. The comment number treated by the update manager. 3. The history time number treated by the update manager.
				'doneDescription' => sprintf( __( '%1$s points, %2$s comments, %3$s history_time have been treated', 'task-manager' ), $point_updated, $comment_updated, $history_time_updated ),
				'more'            => true,
			),
		) );
	}

	/**
	 * Corrige les entrées de la table comment ayant des données corrompues.
	 *
	 * Si le commentaire possède un comment_parent mais pas de comment_post_ID.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_lost_datas() {
		global $wpdb;
		$todo_comments = 0;
		$done_comments = 0;

		$orphelan_lines = $wpdb->get_results( $wpdb->prepare( "
			SELECT C.comment_ID, P.comment_post_ID
			FROM {$wpdb->comments} AS C
				INNER JOIN {$wpdb->comments} AS P ON P.comment_ID = C.comment_parent
			WHERE C.comment_approved = %s
			AND C.comment_type = %s
			AND C.comment_parent != %d
			AND C.comment_post_ID = %d", '-34070', '', 0, 0 ) );
		if ( ! empty( $orphelan_lines ) ) {
			$todo_comments = count( $orphelan_lines );
			foreach ( $orphelan_lines as $comment ) {
				$done_comments += $wpdb->update( $wpdb->comments, array( 'comment_post_ID' => $comment->comment_post_ID ), array( 'comment_ID' => $comment->comment_ID ) );
			}
		}

		wp_send_json_success( array(
			'done' => true,
			'args' => array(
				// Translators: 1. describe the lines numbers affected by the update. 2. Lines number to update.
				'doneDescription' => sprintf( __( '%1$s lines have been treated on %2$s', 'task-manager' ), $done_comments, $todo_comments ),
				'more'            => true,
			),
		) );
	}

}

new Update_160();
