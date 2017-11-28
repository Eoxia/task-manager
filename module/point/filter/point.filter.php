<?php
/**
 * Gestion des filtres relatives aux points
 *
 * @since 1.3.4.0
 * @version 1.3.6.0
 * @package Task-Manager\point
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Gestion des filtres relatives aux points
 */
class Point_Filter {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_filter( 'task_points_mail', array( $this, 'callback_task_points_mail' ), 10, 2 );
	}

	/**
	 * Ajoutes les points dans le mail pour notifier les utilisateurs.
	 *
	 * @param string     $string Le contenu du mail.
	 * @param Task_Model $task   Les données de la tâche.
	 *
	 * @return string 					 Le contenu du mail modifié.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_task_points_mail( $string, $task ) {
		if ( ! empty( $task->task_info['order_point_id'] ) ) {
			$list_point = Point_Class::g()->get( array(
				'post_id' => $task->id,
				'comment__in' => $task->task_info['order_point_id'],
				'status' => -34070,
			) );

			$list_point_completed = array_filter( $list_point, function( $point ) {
				if ( empty( $point->id ) ) {
					return false;
				}
				return true === $point->point_info['completed'];
			} );

			$list_point_uncompleted = array_filter( $list_point, function( $point ) {
				if ( empty( $point->id ) ) {
					return false;
				}
				
				return false === $point->point_info['completed'];
			} );
		}

		$string .= '<h3>' . __( 'Incompleted', 'task-manager' ) . '</h3>';
		if ( ! empty( $list_point_uncompleted ) ) :
			$string .= '<ul>';
			foreach ( $list_point_uncompleted as $element ) :
				$string .= '<li>#' . $element->id . ' - ' . $element->content . '</li>';
			endforeach;
			$string .= '</ul>';
		endif;

		return $string;
	}
}

new Point_Filter();
