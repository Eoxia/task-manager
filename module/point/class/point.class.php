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
}

Point_Class::g();
