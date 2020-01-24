<?php
/**
 * Gestion des points
 *
 * @since 1.3.4
 * @version 1.6.0
 * @package Task-Manager\point
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des points
 */
class Point_Class extends \eoxia\Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = 'task_manager\Point_Model';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_point';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'point';

	/**
	 * Le type du commentaire
	 *
	 * @var string
	 */
	protected $type = 'wpeo_point';

	/**
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * Statut personnalisé pour l'élément.
	 *
	 * @var string
	 */
	protected $status = '1';

	/**
	 * Affiches les points d'une tâche.
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @param integer $task_id   L'ID de la tâche.
	 * @param boolean $frontend  true si l'affichage est sur le front end, sinon false.
	 *
	 * @return void
	 *
	 * @todo Ajouter "comment_id" et "point_id" en paramètre. Et renommer en selected_*
	 */
	public function display( $task_id, $frontend = false, $point_id_quicktime = 0, $completed = false ) {
		$comment_id = ! empty( $_GET['comment_id'] ) ? (int) $_GET['comment_id'] : 0;
		$point_id   = ! empty( $_GET['point_id'] ) ? (int) $_GET['point_id'] : 0;

		if( $point_id_quicktime != 0 ){
			$point_id = (int) $point_id_quicktime;
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		if ( ! $completed ) {
			$points = self::g()->get(
				array(
					'post_id'    => $task->data['id'],
					'type'       => self::g()->get_type(),
					'meta_key'   => '_tm_order',
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',
					'meta_query' => array(
						array(
							'key'     => '_tm_completed',
							'value'   => false,
							'compare' => '=',
						),
					),
				)
			);
		}

		// Dans le frontend, les points complétés sont affichées directement.
		if ( $frontend || $completed ) {
			$points = self::g()->get(
				array(
					'post_id'    => $task->data['id'],
					'type'       => self::g()->get_type(),
					'meta_key'   => '_tm_order',
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',
					'meta_query' => array(
						array(
							'key'     => '_tm_completed',
							'value'   => true,
							'compare' => '=',
						),
					),
				)
			);
		}

		$point_schema = self::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		$args = array(
			'task'               => $task,
			'task_id'            => $task_id,
			'comment_id'         => $comment_id,
			'point_id'           => $point_id,
			'points'             => $points,
			'point_schema'       => $point_schema,
		);

		Task_Class::g()->display_bodies( $points, $task );

//		if ( $frontend ) {
//			\eoxia\View_Util::exec( 'task-manager', 'point', 'frontend/main', $args );
//		} else {
//			\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/main', $args );
//		}
	}

	/**
	 * Complète un point en base de donnée.
	 *
	 * @since 1.7.0
	 * @version 1.7.0
	 *
	 * @param  int     $point_id     L'ID du point.
	 * @param  boolean $completed    True ou false.
	 * @param  boolean $is_new_point True ou false.
	 *
	 * @return boolean               True ou false.
	 */
	public function complete_point( $point_id, $completed, $is_new_point = false ) {
		$point = $this->get(
			array(
				'id' => $point_id,
			),
			true
		);

		$task = Task_Class::g()->get(
			array(
				'id' => $point->data['post_id'],
			),
			true
		);

		$point->data['completed'] = $completed;

		if ( $completed ) {
			$task->data['count_completed_points']++;

			if ( ! $is_new_point ) {
				$task->data['count_uncompleted_points']--;
			}
			$point->data['time_info']['completed_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		} else {
			if ( ! $is_new_point ) {
				$task->data['count_completed_points']--;
			}

			$task->data['count_uncompleted_points']++;
			$point->data['time_info']['uncompleted_point'][ get_current_user_id() ][] = current_time( 'mysql' );
		}

		$point = $this->update( $point->data );

		Task_Class::g()->update( $task->data );

		do_action( 'tm_complete_point', $point );

		return $point;
	}

	/**
	 * Mise à jour d'un point.
	 *
	 * @since 1.8.0
	 * @version 1.8.0
	 *
	 * @param integer $point_id  L'ID du point.
	 * @param integer $parent_id L'ID du parent.
	 * @param string  $content   Le contenu du point.
	 * @param boolean $completed Complete le point si true.
	 *
	 * @return Point_Model Les données du point.
	 */
	public function edit_point( $point_id, $parent_id, $content, $completed ) {
		/*$content = wp_kses(
			$content,
			array(
				'br'      => array(),
				'tooltip' => array(
					'class' => array(),
				),
			)
		);*/

		if ( empty( $parent_id ) ) {
			wp_send_json_error();
		}

		$point_args = array(
			'id'      => $point_id,
			'post_id' => $parent_id,
			'content' => $content,
		);

		$task = null;

		$point = $this->update( $point_args );

		// Dans le cas ou c'est un nouveau point.
		if ( 0 === $point_id ) {
			$point = $this->complete_point( $point->data['id'], $completed, true );

			$task = Task_Class::g()->get( array( 'id' => $parent_id ), true );
		}

		$point->data['content'] = stripslashes( $point->data['content'] );

		return array(
			'point' => $point,
			'task'  => $task,
		);
	}

	public function display_prompt_complete_point() {
	    \eoxia\View_Util::exec( 'task-manager', 'point', 'backend/prompt-complete' );
    }

	public function get_point_last_update( $task_id ) {

		$now = strtotime( 'now' );
		$point = Point_Class::g()->get( array( 'id' => $task_id ), true );
		/*if ( action ) {
			$last_update = $task->data['last_update']['rendered']['mysql'];
		}else {*/
		$last_update = $point->data['date']['rendered']['mysql'];
		//}
		$time = strtotime( 'now + 1 hour' ) - strtotime( $last_update );
		$last_update = $this->time_elapsed( $time );
		return $last_update;
	}

	/**
	 * Calcul le temps écoulé depuis le dernier commentaire ajouté pour ne pas avoir a faire le calcul dans le commentaire a chaque changement de projet.
	 *
	 * @param   Comment_Object  $object  L'objet Comment_Object.
	 * @param   array           $args    Des paramètres complémentaires pour permettre d'agir sur l'élement.
	 *
	 * @return Comment_Object        Les données de la tâche avec les données complémentaires.
	 * @version 1.5.1
	 *
	 * @since   1.5.0
	 */
	public function calcul_elapsed_time() {
		$calculed_elapsed = 15;
		$current_user     = get_current_user_id();
		if ( ! empty( $current_user ) ) {
			$user = Follower_Class::g()->get(
				array(
					'include' => $current_user,
				),
				true
			);
			if ( true == $user->data['_tm_auto_elapsed_time'] ) {
				// Récupération du dernier commentaire ajouté dans la base.
				$query                   = $GLOBALS['wpdb']->prepare(
					"SELECT TIMEDIFF( %s, COMMENT.comment_date ) AS DIFF_DATE
					FROM {$GLOBALS['wpdb']->comments} AS COMMENT
						INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_id
						INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_id = COMMENT.comment_parent
						INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_id
					WHERE COMMENT.user_id = %d
						AND COMMENT.comment_date >= %s
						AND COMMENTMETA.meta_key = %s
						AND COMMENT.comment_approved != 'trash'
						AND POINT.comment_approved != 'trash'
						AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )
					ORDER BY COMMENT.comment_date DESC
					LIMIT 1",
					current_time( 'mysql' ),
					$current_user,
					current_time( 'Y-m-d 00:00:00' ),
					'wpeo_time'
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
					$calculed_elapsed = $the_interval;
				}
			}
		}

		return $calculed_elapsed;
	}
}

Point_Class::g();
