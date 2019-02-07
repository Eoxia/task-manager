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
	 * Si le commentaire possède un comment_parent mais pas de comment_post_id.
	 *
	 * @return void
	 */
	public function callback_task_manager_update_1600_lost_datas() {
		check_ajax_referer( 'task_manager_update_1600_lost_datas' );

		// Supprimes l'option qui peut être déjà incrémenter et ne pas faire la mise à jour de toutes les données des points.
		$delete_status = delete_option( '_tm_update_1600_point_updated' );
		\eoxia\LOG_Util::log( 'Delete _tm_update_1600_point_updated with return status: ' . $delete_status, 'task-manager' );

		$done_comments = 0;
		$todo_comments = 0;

		$query          = $GLOBALS['wpdb']->prepare(
			"
			SELECT C.comment_id, P.comment_post_id
			FROM {$GLOBALS['wpdb']->comments} AS C
				INNER JOIN {$GLOBALS['wpdb']->comments} AS P ON P.comment_id = C.comment_parent
			WHERE C.comment_approved = %s
			AND C.comment_type = %s
			AND C.comment_parent != %d
			AND C.comment_post_id = %d",
			'-34070',
			'',
			0,
			0
		);
		$orphelan_lines = $GLOBALS['wpdb']->get_results( $query );
		if ( ! empty( $orphelan_lines ) ) {
			$todo_comments = count( $orphelan_lines );
			foreach ( $orphelan_lines as $comment ) {
				$done_comments += $GLOBALS['wpdb']->update( $GLOBALS['wpdb']->comments, array( 'comment_post_id' => $comment->comment_post_id ), array( 'comment_id' => $comment->comment_id ) );
			}
		}

		wp_send_json_success(
			array(
				'updateComplete'    => false,
				'done'              => true,
				'progression'       => '',
				// Translators: 1. Number of treated comments 2. Previsionnal number of comments to treat.
				'doneDescription'   => sprintf( __( '%1$s lines have been treated on %2$s', 'task-manager' ), $done_comments, $todo_comments ),
				'doneElementNumber' => $done_comments,
				'errors'            => null,
			)
		);
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
		$count_points = (int) $GLOBALS['wpdb']->get_var( self::prepare_request( 'count( COMMENT.comment_id )', false, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.
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
		$total_number    = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;

		$task_schema  = Task_Class::g()->get_schema();
		$point_schema = Point_Class::g()->get_schema();

		$count_point_updated = get_option( '_tm_update_1600_point_updated', 0 );

		$points = $GLOBALS['wpdb']->get_results( self::prepare_request( 'COMMENT.comment_id, COMMENT.comment_approved, COMMENT.comment_content, COMMENT.comment_post_id', true, '=', Point_Class::g()->get_type() ) ); // WPCS: unprepared sql.
		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				$comment_metas = get_comment_meta( (int) $point->comment_id );

				$the_point_data['comment_id']       = (int) $point->comment_id;
				$the_point_data['comment_type']     = Point_Class::g()->get_type();
				$the_point_data['comment_approved'] = ( ( '-34071' === $point->comment_approved ) || ( 'trash' === $point->comment_approved ) ? 'trash' : '1' );

				$comment_update = wp_update_comment( $the_point_data );
				if ( 0 === $comment_update ) {
					\eoxia\LOG_Util::log( 'Update for comment #' . (int) $point->comment_id . ' failed', 'task-manager' );
				} else {
					$count_point_updated++;
					update_option( '_tm_update_1600_point_updated', $count_point_updated );
				}

				// Mise à jour des metas du point.
				// Nombre de commentaires.
				$tm_count_comment = 0;
				if ( ! empty( $point->comment_post_id ) ) {
					$comments = Task_Comment_Class::g()->get(
						array(
							'post_id' => $point->comment_post_id,
							'parent'  => (int) $point->comment_id,
							'status'  => '-34070',
						)
					);

					if ( ! empty( $comments ) ) {
						$tm_count_comment = count( $comments );
					}
				}
				update_comment_meta( (int) $point->comment_id, $point_schema['count_comments']['field'], $tm_count_comment );

				// Position du point dans la tâche.
				update_comment_meta( (int) $point->comment_id, $point_schema['order']['field'], $this->search_position( (int) $point->comment_id, $point->comment_post_id ) );

				// Statut du point terminé / en cours.
				if ( ! empty( $comment_metas ) && ! empty( $comment_metas[ Point_Class::g()->get_meta_key() ] ) && ! isset( $comment_metas[ $point_schema['completed']['field'] ] ) ) {
					$wpeo_point_meta = json_decode( $comment_metas[ Point_Class::g()->get_meta_key() ][0] );
					if ( true === $wpeo_point_meta->point_info->completed ) {
						$meta_name = $task_schema['count_uncompleted_points']['field'];
						update_comment_meta( (int) $point->comment_id, $point_schema['completed']['field'], true );
					} else {
						$meta_name = $task_schema['count_completed_points']['field'];
						update_comment_meta( (int) $point->comment_id, $point_schema['completed']['field'], false );
					}
					$task_number_point = get_post_meta( $point->comment_post_id, $meta_name, true );
					if ( empty( $count_completed_point ) ) {
						$task_number_point = 0;
					}
					$task_number_point++;
					update_post_meta( $point->comment_post_id, $meta_name, $task_number_point );
				}
			}
		}

		if ( $count_point_updated >= $total_number ) {
			$count_point_updated = $total_number;
			$done                = true;
		}

		$timestamp_fin = microtime( true );
		$difference_ms = $timestamp_fin - $timestamp_debut;
		\eoxia\LOG_Util::log( $difference_ms, 'task-manager' );

		wp_send_json_success(
			array(
				'updateComplete'     => false,
				'done'               => $done,
				'progression'        => $count_point_updated . '/' . $total_number,
				'progressionPerCent' => 0 !== $count_point_updated ? ( ( $count_point_updated * 100 ) / $total_number ) : 0,
				// Translators: 1. Number of treated points 2. Previsionnal number of points to treat.
				'doneDescription'    => sprintf( __( '%1$s points ( type, status ) updated on %2$s', 'task-manager' ), $count_point_updated, $total_number ),
				'doneElementNumber'  => $count_point_updated,
				'errors'             => null,
			)
		);
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
		$count_comment = (int) $GLOBALS['wpdb']->get_var( self::prepare_request( 'count(COMMENT.comment_id)', false, '!=', Task_Comment_Class::g()->get_type() ) );// WPCS: unprepared sql.
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

		$done                  = false;
		$count_comment         = ! empty( $_POST['total_number'] ) ? (int) $_POST['total_number'] : 0;
		$index                 = ! empty( $_POST['done_number'] ) ? (int) $_POST['done_number'] : 0;
		$count_comment_updated = 0;

		$comments = $GLOBALS['wpdb']->get_results( self::prepare_request( 'COMMENT.comment_id, COMMENT.comment_approved, COMMENT.comment_content, COMMENT.comment_post_id', true, '!=', Task_Comment_Class::g()->get_type() ) ); // WPCS: unprepared sql.
		if ( ! empty( $comments ) ) {
			$comment_filter = new Comment_Filter();
			foreach ( $comments as $comment ) {
				$the_comment_data['comment_id']       = (int) $comment->comment_id;
				$the_comment_data['comment_type']     = Task_Comment_Class::g()->get_type();
				$the_comment_data['comment_approved'] = ( ( '-34071' === $comment->comment_approved ) || ( 'trash' === $comment->comment_approved ) ? 'trash' : '1' );
				$point_updates                        = wp_update_comment( $the_comment_data );
				if ( 0 === $point_updates ) {
					\eoxia\LOG_Util::log( 'Update for comment #' . (int) $comment->comment_id . ' failed', 'task-manager' );
				} else {
					$count_comment_updated++;
					update_option( '_tm_update_1600_comment_updated', $count_comment_updated );
				}
			}
		}

		$index += self::$limit;

		if ( $index >= $count_comment ) {
			$index = $count_comment;
			$done  = true;
		}

		wp_send_json_success(
			array(
				'updateComplete'     => false,
				'done'               => $done,
				'progression'        => $index . '/' . $count_comment,
				'progressionPerCent' => 0 !== $count_comment ? ( ( $index * 100 ) / $count_comment ) : 0,
				// Translators: 1. Number of treated comments 2. Previsionnal number of comments to treat.
				'doneDescription'    => sprintf( __( '%1$s comments ( type, status ) updated on %2$s', 'task-manager' ), $index, $count_comment ),
				'doneElementNumber'  => $index,
				'errors'             => null,
			)
		);
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

		wp_send_json_success(
			array(
				'updateComplete'     => false,
				'done'               => true,
				'progression'        => $done_history_time . '/' . $history_time_todo,
				'progressionPerCent' => 100,
				// Translators: 1. Number of treated history time 2. Previsonnal number of history time to treat.
				'doneDescription'    => sprintf( __( '%1$s history_time have been treated on %2$s', 'task-manager' ), $done_history_time, $history_time_todo ),
				'doneElementNumber'  => $done_history_time,
				'errors'             => null,
			)
		);
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
		$comment_updated      += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 1 ),
			array(
				'comment_approved' => -34070,
				'comment_type'     => Task_Comment_Class::g()->get_type(),
			)
		);
		$point_updated        += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 1 ),
			array(
				'comment_approved' => -34070,
				'comment_type'     => Point_Class::g()->get_type(),
			)
		);
		$history_time_updated += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 1 ),
			array(
				'comment_approved' => -34070,
				'comment_type'     => History_Time_Class::g()->get_type(),
			)
		);

		// Mise à jour des -34071 en trash.
		$comment_updated      += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 'trash' ),
			array(
				'comment_approved' => -34071,
				'comment_type'     => Task_Comment_Class::g()->get_type(),
			)
		);
		$point_updated        += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 'trash' ),
			array(
				'comment_approved' => -34071,
				'comment_type'     => Point_Class::g()->get_type(),
			)
		);
		$history_time_updated += $GLOBALS['wpdb']->update(
			$GLOBALS['wpdb']->comments,
			array( 'comment_approved' => 'trash' ),
			array(
				'comment_approved' => -34071,
				'comment_type'     => History_Time_Class::g()->get_type(),
			)
		);

		wp_send_json_success(
			array(
				'updateComplete'     => false,
				'done'               => true,
				'progression'        => '',
				'progressionPerCent' => 100,
				// Translators: 1. Number of points treated 2. Number of comments treated 3. Number o history time treated.
				'doneDescription'    => sprintf( __( '%1$s points, %2$s comments, %3$s history_time have been treated', 'task-manager' ), $point_updated, $comment_updated, $history_time_updated ),
				'doneElementNumber'  => ( $point_updated + $comment_updated + $history_time_updated ),
				'errors'             => null,
			)
		);
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

		$query                = $GLOBALS['wpdb']->prepare(
			"
			SELECT TR.object_id AS ID, P.post_status, T.term_id
			FROM {$GLOBALS['wpdb']->term_relationships} AS TR
				INNER JOIN {$GLOBALS['wpdb']->term_taxonomy} AS TT ON TT.term_taxonomy_id = TR.term_taxonomy_id
				INNER JOIN {$GLOBALS['wpdb']->terms} AS T ON T.term_id = TT.term_id
				INNER JOIN {$GLOBALS['wpdb']->posts} AS P ON ( P.ID = TR.object_id )
			WHERE T.slug = %s
				AND TT.taxonomy = %s
				AND P.post_type = %s",
			'archive',
			'wpeo_tag',
			Task_Class::g()->get_type()
		);
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
				if ( 0 === $archive_id ) {
					// Récupère l'identifiant de la catégorie "archive".
					$archive_id = $task->term_id;
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
			$deleted_archive_state = wp_delete_term( $archive_id, 'wpeo_tag' );

			if ( ! is_wp_error( $deleted_archive_state ) ) {
				\eoxia\LOG_Util::log( 'Tag archive has beed deleted with return state: ' . $deleted_archive_state, 'task-manager' );
			} else {
				\eoxia\LOG_Util::log( 'Tag archive has beed deleted with return state: ' . wp_json_encode( $deleted_archive_state ), 'task-manager' );
			}
		}

		wp_send_json_success(
			array(
				'updateComplete'     => false,
				'done'               => true,
				'progression'        => '',
				'progressionPerCent' => 100,
				// Translators: 1. Number of treated tasks 2. Number of tasks to treat.
				'doneDescription'    => sprintf( __( '%1$d tasks have been marked as archived on %2$d', 'task-manager' ), $done_tasks, $todo_tasks ),
				'doneElementNumber'  => $done_tasks,
				'errors'             => $errors,
			)
		);
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
		$comment_approved = array(
			'trash',
			'',
			'-34070',
			'-34071',
		);
		$comment_type     = array(
			'',
			$type,
		);
		$prepare_args     = array(
			0,
			Task_Class::g()->get_type(),
		);

		$query_string = "SELECT {$column_to_get}
		 FROM {$GLOBALS['wpdb']->comments} AS COMMENT
			JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = COMMENT.comment_post_id
		 WHERE COMMENT.comment_parent {$comparison} %d
		 	AND TASK.post_type = %s";

		$sub_query_string = array();
		foreach ( $comment_approved as $status ) {
			foreach ( $comment_type as $type ) {
				if ( 'trash' !== $status || '' === $type ) {
					$sub_query_string[] = '( COMMENT.comment_approved = %s AND COMMENT.comment_type = %s )';
					$prepare_args[]     = $status;
					$prepare_args[]     = $type;
				}
			}
		}
		if ( ! empty( $sub_query_string ) ) {
			$query_string .= ' AND ( ' . implode( ' OR ', $sub_query_string ) . ' ) ';
		}

		if ( ! empty( $paginate ) ) {
			$query_string .= ' LIMIT 0, ' . self::$limit;
		}

		$query = $GLOBALS['wpdb']->prepare( $query_string, $prepare_args ); // WPCS: unprepared sql.

		if ( Point_Class::g()->get_type() === $type ) {
			// @temp_code echo __LINE__ . " - " . $query . "<hr/>";exit;
		}

		return $query;
	}

	/**
	 * Recherches la position du point dans le tableau "order_point_id" de la tâche.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @param_  Point_Model $point Les données du point.
	 * @param [type] $point_id [id du point].
	 * @param [type] $task_id  [id de la tache].
	 * @return integer            La position du point.
	 */
	public function search_position( $point_id, $task_id ) {
		$position = false;

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		if ( empty( $task ) ) {
			$position = false;
		} else {
			$position = array_search( $point_id, $task->data['task_info']['order_point_id'] );
		}

		if ( false === $position ) {
			// \eoxia\LOG_Util::log( 'No order for the point #' . $point->data['id'] . ' setted to 0 in task #' . $task->data['id'] . '(' . wp_json_encode( $task->data['task_info']['order_point_id'] ) . ')', 'task-manager' );
			$position = 0;
		}

		return $position;
	}

}

new Update_1600();
