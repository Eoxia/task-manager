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
class Update_1600 {
	/**
	 * Limite de mise à jour des éléments par requêtes.
	 *
	 * @var integer
	 */
	private static $limit = 50;

	/**
	 * Le constructeur
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_task_manager_update_1600_lost_datas', array( $this, 'callback_task_manager_update_1600_lost_datas' ) );

		add_action( 'wp_ajax_task_manager_update_1600_points', array( $this, 'callback_task_manager_update_1600_points' ) );

		add_action( 'wp_ajax_task_manager_update_1600_comments', array( $this, 'callback_task_manager_update_1600_comments' ) );

		add_action( 'wp_ajax_task_manager_update_1600_history_time', array( $this, 'callback_task_manager_update_1600_history_time' ) );

		add_action( 'wp_ajax_task_manager_update_1600_comment_status', array( $this, 'callback_task_manager_update_1600_comment_status' ) );

		add_action( 'wp_ajax_task_manager_update_1600_archived_task', array( $this, 'callback_task_manager_update_1600_archived_task' ) );
	}

	/**
	 * Corrige les entrées de la table comment ayant des données corrompues.
	 *
	 * Si le commentaire possède un comment_parent mais pas de comment_post_ID.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_lost_datas() {
		check_ajax_referer( 'task_manager_update_1600_lost_datas' );
		$done_comments = 0;
		$todo_comments = 0;

		$query          = $GLOBALS['wpdb']->prepare( "
			SELECT C.comment_ID, P.comment_post_ID
			FROM {$GLOBALS['wpdb']->comments} AS C
				INNER JOIN {$GLOBALS['wpdb']->comments} AS P ON P.comment_ID = C.comment_parent
			WHERE C.comment_approved = %s
			AND C.comment_type = %s
			AND C.comment_parent != %d
			AND C.comment_post_ID = %d", '-34070', '', 0, 0 );
		$orphelan_lines = $GLOBALS['wpdb']->get_results( $query );
		if ( ! empty( $orphelan_lines ) ) {
			$todo_comments = count( $orphelan_lines );
			foreach ( $orphelan_lines as $comment ) {
				$done_comments += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_post_ID' => $comment->comment_post_ID ), array( 'comment_ID' => $comment->comment_ID ) );
			}
		}

		wp_send_json_success( array(
			'updateComplete'    => false,
			'done'              => true,
			'progression'       => '',
			// Translators: 1. Number of treated comments 2. Previsionnal number of comments to treat.
			'doneDescription'   => sprintf( __( '%1$s lines have been treated on %2$s', 'task-manager' ), $done_comments, $todo_comments ),
			'doneElementNumber' => $done_comments,
			'errors'            => null,
		) );
	}

	/**
	 * Récupères le nombre de points
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return integer Le nombre total de points à traiter dans cette mise à jour.
	 */
	public static function callback_task_manager_update_1600_calcul_number_points() {
		$count_points = (int) $GLOBALS['wpdb']->get_var( self::prepare_request( 'count( COMMENT.comment_ID )', true, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.
		return $count_points;
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
		check_ajax_referer( 'task_manager_update_1600_points' );

		$timestamp_debut = microtime( true );
		$done            = false;
		$count_point     = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;
		$index           = ! empty( $_POST['done_number'] ) ? (int) $_POST['done_number'] : 0;

		$task_schema = Task_Class::g()->get_schema();

		$count_point_updated = get_option( '_tm_update_1600_point_updated', true );
		if ( empty( $count_point_updated ) ) {
			$count_point_updated = 0;
		}

		$points = $GLOBALS['wpdb']->get_results( self::prepare_request( 'COMMENT.comment_ID, COMMENT.comment_approved, COMMENT.comment_content', true, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.
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

		$index += self::$limit;

		if ( $index >= $count_point ) {
			$index = $count_point;
			$done  = true;
		}

		$timestamp_fin = microtime( true );
		$difference_ms = $timestamp_fin - $timestamp_debut;
		\eoxia\LOG_Util::log( $difference_ms, 'task-manager' );

		wp_send_json_success( array(
			'updateComplete'     => false,
			'done'               => $done,
			'progression'        => $index . '/' . $count_point,
			'progressionPerCent' => 0 !== $count_point ? ( ( $index * 100 ) / $count_point ) : 0,
			// Translators: 1. Number of treated points 2. Previsionnal number of points to treat.
			'doneDescription'    => sprintf( __( '%1$s points ( type, status ) updated on %2$s', 'task-manager' ), $index, $count_point ),
			'doneElementNumber'  => $index,
			'errors'             => null,
		) );
	}

	/**
	 * Récupères le nombre de commentaire
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return integer Le nombre total de commentaire à traiter dans cette mise à jour.
	 */
	public static function callback_task_manager_update_1600_calcul_number_comments() {
		$count_comment = (int) $GLOBALS['wpdb']->get_var( self::prepare_request( 'count(COMMENT.comment_ID)', 0, '!=', Task_Comment_Class::g()->get_type() ) );// WPCS: unprepared sql.
		return $count_comment;
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
		check_ajax_referer( 'task_manager_update_1600_comments' );

		$timestamp_debut = microtime( true );
		$done            = false;
		$count_comment   = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;
		$index           = ! empty( $_POST['done_number'] ) ? (int) $_POST['done_number'] : 0;

		$comments = $GLOBALS['wpdb']->get_results( self::prepare_request( 'COMMENT.comment_ID, COMMENT.comment_approved, COMMENT.comment_content', true, '!=', Task_Comment_Class::g()->get_type() ) ); // WPCS: unprepared sql.
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

		$index += self::$limit;

		if ( $index >= $count_comment ) {
			$index = $count_comment;
			$done  = true;
		}

		$timestamp_fin = microtime( true );
		$difference_ms = $timestamp_fin - $timestamp_debut;
		\eoxia\LOG_Util::log( 'Update comment: ' . $difference_ms, 'task-manager' );

		wp_send_json_success( array(
			'updateComplete'     => false,
			'done'               => $done,
			'progression'        => $index . '/' . $count_comment,
			'progressionPerCent' => 0 !== $count_comment ? ( ( $index * 100 ) / $count_comment ) : 0,
			// Translators: 1. Number of treated comments 2. Previsionnal number of comments to treat.
			'doneDescription'    => sprintf( __( '%1$s comments ( type, status ) updated on %2$s', 'task-manager' ), $index, $count_comment ),
			'doneElementNumber'  => $index,
			'errors'             => null,
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
		check_ajax_referer( 'task_manager_update_1600_history_time' );

		$done_history_time = 0;
		$history_time_todo = 0;

		$history_time_to_repair = $GLOBALS['wpdb']->get_results( $GLOBALS['wpdb']->prepare( "SELECT meta_id, meta_value FROM {$GLOBALS['wpdb']->commentmeta} WHERE meta_key LIKE %s AND meta_value LIKE %s", $GLOBALS['wpdb']->esc_like( 'wpeo_history_time' ), '%' . $GLOBALS['wpdb']->esc_like( 'date_input' ) . '%' ) );

		if ( ! empty( $history_time_to_repair ) ) {
			$history_time_todo = count( $history_time_to_repair );
			foreach ( $history_time_to_repair as $meta_definition ) {
				$meta = json_decode( $meta_definition->meta_value, true );
				if ( ! empty( $meta ) && ! empty( $meta['due_date'] ) && ! empty( $meta['due_date']['date_input'] ) ) {
					$meta['due_date']   = $meta['due_date']['date_input']['date'];
					$done_history_time += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->commentmeta, array( 'meta_value' => wp_json_encode( $meta ) ), array( 'meta_id' => $meta_definition->meta_id ) );
				}
			}
		}

		wp_send_json_success( array(
			'updateComplete'     => false,
			'done'               => true,
			'progression'        => $done_history_time . '/' . $history_time_todo,
			'progressionPerCent' => 100,
			// Translators: 1. Number of treated history time 2. Previsonnal number of history time to treat.
			'doneDescription'    => sprintf( __( '%1$s history_time have been treated on %2$s', 'task-manager' ), $done_history_time, $history_time_todo ),
			'doneElementNumber'  => $done_history_time,
			'errors'             => null,
		) );
	}

	/**
	 * Modifie les valeurs de comment approved dans la base de données pour utiliser les valeurs de base de WordPress.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_comment_status() {
		check_ajax_referer( 'task_manager_update_1600_comment_status' );

		$comment_updated      = 0;
		$point_updated        = 0;
		$history_time_updated = 0;

		// Mise à jour des -34070 en 1.
		$comment_updated      += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => Task_Comment_Class::g()->get_type(),
		) );
		$point_updated        += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => Point_Class::g()->get_type(),
		) );
		$history_time_updated += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 1 ), array(
			'comment_approved' => -34070,
			'comment_type'     => History_Time_Class::g()->get_type(),
		) );

		// Mise à jour des -34071 en trash.
		$comment_updated      += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => Task_Comment_Class::g()->get_type(),
		) );
		$point_updated        += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => Point_Class::g()->get_type(),
		) );
		$history_time_updated += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_approved' => 'trash' ), array(
			'comment_approved' => -34071,
			'comment_type'     => History_Time_Class::g()->get_type(),
		) );

		wp_send_json_success( array(
			'updateComplete'     => false,
			'done'               => true,
			'progression'        => '',
			'progressionPerCent' => 100,
			// Translators: 1. Number of points treated 2. Number of comments treated 3. Number o history time treated.
			'doneDescription'    => sprintf( __( '%1$s points, %2$s comments, %3$s history_time have been treated', 'task-manager' ), $point_updated, $comment_updated, $history_time_updated ),
			'doneElementNumber'  => ( $point_updated + $comment_updated + $history_time_updated ),
			'errors'             => null,
		) );
	}

	/**
	 * Mise à jour des tâches archivées.
	 * SUppression de la catégorie archive et mise à jour du statut de la tâche en 'archive' si ce n'est pas déjà le cas.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_archived_task() {
		check_ajax_referer( 'task_manager_update_1600_archived_task' );
		$done_tasks = 0;
		$todo_tasks = 0;
		$archive_id = 0;
		$errors     = array();

		$query                = $GLOBALS['wpdb']->prepare( "
			SELECT TR.object_id AS ID, P.post_status, T.term_id
			FROM {$GLOBALS['wpdb']->term_relationships} AS TR
				INNER JOIN {$GLOBALS['wpdb']->term_taxonomy} AS TT ON TT.term_taxonomy_id = TR.term_taxonomy_id
				INNER JOIN {$GLOBALS['wpdb']->terms} AS T ON T.term_id = TT.term_id
				INNER JOIN {$GLOBALS['wpdb']->posts} AS P ON ( P.ID = TR.object_id )
			WHERE T.slug = %s
				AND TT.taxonomy = %s
				AND P.post_type = %s", 'archive', 'wpeo_tag', Task_Class::g()->get_type() );
		$archived_task_by_tag = $GLOBALS['wpdb']->get_results( $query );
		if ( ! empty( $archived_task_by_tag ) ) {
			foreach ( $archived_task_by_tag as $task ) {
				// Change le statut de la tâche si nécessaire.
				if ( 'archive' !== $task->post_status ) {
					$todo_tasks++;
					$update_task = $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, array( 'post_status' => 'archive' ), array( 'ID' => $task->ID ) );
					if ( false !== $update_task ) {
						$done_tasks++;
					} else {
						// Translators: The task identifer.
						$errors[] = sprintf( __( 'An error occured while modifying task %d', 'task-manager' ), $task->ID );
					}
				}
				// Supprime le tag 'archive' des relations de la tâche.
				wp_remove_object_terms( $task->ID, $task->term_id, 'wpeo_tag' );
			}
		}
		// Supprime le term 'archive' de la base.
		if ( 0 === $archive_id ) {
			$archive_term = get_term_by( 'slug', 'archive', 'wpeo_tag' );
			if ( false !== $archive_term ) {
				$archive_id = (int) $archive_term->term_id;
			}
		}
		if ( ! empty( $archive_id ) ) {
			wp_delete_term( $archive_id, 'wpeo_tag' );
		}

		wp_send_json_success( array(
			'updateComplete'     => false,
			'done'               => true,
			'progression'        => '',
			'progressionPerCent' => 100,
			// Translators: 1. Number of treated tasks 2. Number of tasks to treat.
			'doneDescription'    => sprintf( __( '%1$d tasks have been marked as archived on %2$d', 'task-manager' ), $done_tasks, $todo_tasks ),
			'doneElementNumber'  => $done_tasks,
			'errors'             => $errors,
		) );
	}

	/**
	 * Définition de la requête permettant de récupérer la liste des points et des commentaires.
	 *
	 * @param string  $column_to_get La liste des colonnes à retourner.
	 * @param boolean $paginate      Faut il paginer la requête.
	 * @param string  $comparison    Permet de définir si on vérifie des commentaires (!=) ou des points (=).
	 * @param string  $type          Le type d'élémént que l'on souhaite récupérer (points / commentaires).
	 *
	 * @return string                La requête préparée.
	 */
	public static function prepare_request( $column_to_get, $paginate, $comparison, $type ) {
		$query_string = "SELECT {$column_to_get}
		 FROM {$GLOBALS['wpdb']->comments} AS COMMENT
			JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = COMMENT.comment_post_ID
		 WHERE COMMENT.comment_parent {$comparison} %d
			AND COMMENT.comment_approved IN ( '-34070', '-34071' )
			AND COMMENT.comment_type IN ( '', %s )
			AND TASK.post_type = %s";

		if ( ! empty( $paginate ) ) {
			$query_string .= ' LIMIT 0, ' . self::$limit;
		}

		$query = $GLOBALS['wpdb']->prepare( $query_string, array(
			0,
			$type,
			Task_Class::g()->get_type(),
		) ); // WPCS: unprepared sql.

		return $query;
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

}

new Update_1600();
