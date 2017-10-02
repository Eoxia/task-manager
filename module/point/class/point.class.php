<?php
/**
 * Gestion des points
 *
 * @since 1.3.4.0
 * @version 1.3.4.0
 * @package Task-Manager\point
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Gestion des points
 */
class Point_Class extends \eoxia\Comment_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= 'task_manager\Point_Model';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key		= 'wpeo_point';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'point';

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
	 * Constructeur qui inclus le modèle des points et également des les scripts
	 * JS et CSS nécessaire pour le fonctionnement des points
	 *
	 * @return void
	 */
	protected function construct() {
		parent::construct();
	}

	/**
	 * Affiches les points d'une tâche.
	 *
	 * @param  integer $task_id L'ID de la tâche.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.4.0
	 */
	public function display( $task_id, $frontend = false ) {
		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		$points_completed = array();
		$points_uncompleted = array();
		if ( ! empty( $task->task_info['order_point_id'] ) ) {
			$points = self::g()->get( array(
				'post_id' => $task->id,
				'orderby' => 'comment__in',
				'comment__in' => $task->task_info['order_point_id'],
				'status' => -34070,
			) );

			$points_completed = array_filter( $points, function( $point ) {
				return true === $point->point_info['completed'];
			} );

			$points_uncompleted = array_filter( $points, function( $point ) {
				return false === $point->point_info['completed'];
			} );
		}

		$point_schema = self::g()->get( array(
			'schema' => true,
		), true );

		$args = array(
			'task_id' => $task_id,
			'points_completed' => $points_completed,
			'points_uncompleted' => $points_uncompleted,
			'point_schema' => $point_schema,
		);

		if ( $frontend ) {
			\eoxia\View_Util::exec( 'task-manager', 'point', 'frontend/main', $args );
		} else {
			\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/main', $args );
		}
	}
}

Point_Class::g();
