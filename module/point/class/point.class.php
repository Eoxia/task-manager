<?php
/**
 * Gestion des points
 *
 * @since 1.3.4
 * @version 1.5.0
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
	protected $comment_type = 'wpeo_point';

	/**
	 * La version pour la rest API
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La fonction appelée automatiquement après l'insertion de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\task_manager\update_post_order' );

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\task_manager\get_full_point' );

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
			'post_id'    => $task->id,
			'type'       => self::g()->get_type(),
			'meta_key'   => '_tm_order',
			'orderby'    => 'meta_value_num',
			'meta_query' => array(
				array(
					'key'     => '_tm_completed',
					'value'   => false,
					'compare' => '=',
				),
			),
		) );

		$point_schema = self::g()->get( array(
			'schema' => true,
		), true );

		$args = array(
			'task'               => $task,
			'task_id'            => $task_id,
			'comment_id'         => $comment_id,
			'point_id'           => $point_id,
			'points_uncompleted' => $points,
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
