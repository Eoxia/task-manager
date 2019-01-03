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
	public function display( $task_id, $frontend = false ) {
		$comment_id = ! empty( $_GET['comment_id'] ) ? (int) $_GET['comment_id'] : 0;
		$point_id   = ! empty( $_GET['point_id'] ) ? (int) $_GET['point_id'] : 0;

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$points = self::g()->get( array(
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
		) );

		$points_completed = array();
		// Dans le frontend, les points complétés sont affichées directement.
		if ( $frontend ) {
			$points_completed = self::g()->get( array(
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
			) );
		}

		$point_schema = self::g()->get( array(
			'schema' => true,
		), true );

		$args = array(
			'task'               => $task,
			'task_id'            => $task_id,
			'comment_id'         => $comment_id,
			'point_id'           => $point_id,
			'points_uncompleted' => $points,
			'points_completed'   => $points_completed,
			'point_schema'       => $point_schema,
		);

		if ( $frontend ) {
			\eoxia\View_Util::exec( 'task-manager', 'point', 'frontend/main', $args );
		} else {
			\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/main', $args );
		}
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
		$point = $this->get( array(
			'id' => $point_id,
		), true );

		$task = Task_Class::g()->get( array(
			'id' => $point->data['post_id'],
		), true );

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
		$content = str_replace( '<div>', '<br>', trim( $content ) );
		$content = wp_kses( $content, array(
			'br'      => array(),
			'tooltip' => array(
				'class' => array(),
			),
		) );

		if ( empty( $parent_id ) ) {
			wp_send_json_error();
		}

		$point_args = array(
			'id'      => $point_id,
			'post_id' => $parent_id,
			'content' => $content,
		);

		$task = null;

		$point = $this->update( $point_args, true );

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

}

Point_Class::g();
