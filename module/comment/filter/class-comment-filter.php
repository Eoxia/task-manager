<?php
/**
 * Gestion des filtres relatives aux commentaires
 *
 * @since 1.6.0
 * @version 1.6.0
 * @package Task-Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gestion des filtres relatives aux commentaires
 */
class Comment_Filter {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		$current_type = Task_Comment_Class::g()->get_type();
		add_filter( "eo_model_{$current_type}_after_get", array( $this, 'calcul_elapsed_time' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_put", array( $this, 'compile_time' ), 10, 2 );
		add_filter( "eo_model_{$current_type}_after_post", array( $this, 'compile_time' ), 10, 2 );
	}

	/**
	 * Compiles le temps.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @param Comment_Object $object L'objet Comment_Object.
	 * @param array          $args   Des paramètres complémentaires pour permettre d'agir sur l'élement.
	 *
	 * @return Comment_Object        Les données de la tâche avec les données complémentaires.
	 */
	public function compile_time( $object, $args ) {
		$point = Point_Class::g()->get( array(
			'id'     => $object->data['parent_id'],
			'status' => array( '1', 'trash' ),
		), true );

		$task = Task_Class::g()->get( array(
			'id'          => $object->data['post_id'],
			'post_status' => array(
				'any',
				'archive',
				'trash',
			),
		), true );

		$point_updated_elapsed = $point->data['time_info']['elapsed'];
		$task_updated_elapsed  = $task->data['time_info']['elapsed'];

		if ( ! is_object( $point ) || ! is_object( $task ) ) {
			\eoxia\LOG_Util::log( sprintf( 'Point for update data compilation %s', wp_json_encode( $point ) ), 'task-manager-compile-update' );
			\eoxia\LOG_Util::log( sprintf( 'Task for update data compilation %s', wp_json_encode( $task ) ), 'task-manager-compile-update' );
			\eoxia\LOG_Util::log( sprintf( 'Object for update data compilation %s', wp_json_encode( $object ) ), 'task-manager-compile-update' );
		}

		if ( 'trash' === $object->data['status'] ) {
			$point_updated_elapsed -= $object->data['time_info']['elapsed'];
			$task_updated_elapsed  -= $object->data['time_info']['elapsed'];
		} else {
			if ( isset( $object->data['time_info']['old_elapsed'] ) ) {
				$point_updated_elapsed -= $object->data['time_info']['old_elapsed'];
				$task_updated_elapsed  -= $object->data['time_info']['old_elapsed'];
			}
			$point_updated_elapsed += $object->data['time_info']['elapsed'];
			$task_updated_elapsed  += $object->data['time_info']['elapsed'];
		}

		$point->data['time_info']['elapsed'] = $point_updated_elapsed;
		$task->data['time_info']['elapsed']  = $task_updated_elapsed;
		$point->data['content']              = addslashes( $point->data['content'] );
		$object->data['point']               = Point_Class::g()->update( $point->data );
		$object->data['task']                = Task_Class::g()->update( $task->data );

		return $object;
	}

	/**
	 * Calcul le temps écoulé depuis le dernier commentaire ajouté pour ne pas avoir a faire le calcul dans le commentaire a chaque changement de projet.
	 *
	 * @since 1.5.0
	 * @version 1.5.1
	 *
	 * @param Comment_Object $object L'objet Comment_Object.
	 * @param array          $args   Des paramètres complémentaires pour permettre d'agir sur l'élement.
	 *
	 * @return Comment_Object        Les données de la tâche avec les données complémentaires.
	 */
	public function calcul_elapsed_time( $object, $args ) {
		if ( 0 === $object->data['id'] ) {
			$current_user = get_current_user_id();
			if ( ! empty( $current_user ) ) {
				$user = Follower_Class::g()->get( array(
					'include' => $current_user,
				), true );
				if ( true === $user->data['_tm_auto_elapsed_time'] ) {
					// Récupération du dernier commentaire ajouté dans la base.
					$query                   = $GLOBALS['wpdb']->prepare(
						"SELECT TIMEDIFF( %s, COMMENT.comment_date ) AS DIFF_DATE
						FROM {$GLOBALS['wpdb']->comments} AS COMMENT
							INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_ID
							INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = COMMENT.comment_parent
							INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
						WHERE COMMENT.user_id = %d
							AND COMMENT.comment_date >= %s
							AND COMMENTMETA.meta_key = %s
							AND COMMENT.comment_approved != 'trash'
							AND POINT.comment_approved != 'trash'
							AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )
						ORDER BY COMMENT.comment_date DESC
						LIMIT 1",
						current_time( 'mysql' ), $current_user, current_time( 'Y-m-d 00:00:00' ), 'wpeo_time'
					);
					$time_since_last_comment = $GLOBALS['wpdb']->get_var( $query );
					if ( ! empty( $time_since_last_comment ) ) {
						$the_interval    = 0;
						$time_components = explode( ':', $time_since_last_comment );
						// Convert hours in minutes.
						if ( ! empty( $time_components[0] ) ) {
							$the_interval += $time_components[0] * 60;
						}
						if ( ! empty( $time_components[1] ) ) {
							$the_interval += $time_components[1];
						}
						$object->data['time_info']['calculed_elapsed'] = $the_interval;
					}
				}
			}
		}

		return $object;
	}
}

new Comment_Filter();
