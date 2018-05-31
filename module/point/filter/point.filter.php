<?php
/**
 * Gestion des filtres relatives aux points
 *
 * @since 1.3.4
 * @version 1.6.0
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Gestion des filtres relatives aux points
 */
class Point_Filter {

	/**
	 * Le constructeur
	 */
	public function __construct() {
		add_filter( 'task_points_mail', array( $this, 'callback_task_points_mail' ), 10, 2 );

		$current_type = Point_Class::g()->get_type();
		// add_filter( "eo_model_{$current_type}_after_put", array( $this, 'add_point_to_index' ), 10, 2 );
		// add_filter( "eo_model_{$current_type}_after_post", array( $this, 'add_point_to_index' ), 10, 2 );
	}

	/**
	 * Ajoutes les points dans le mail pour notifier les utilisateurs.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @param string     $string Le contenu du mail.
	 * @param Task_Model $task   Les données de la tâche.
	 *
	 * @return string            Le contenu du mail modifié.
	 */
	public function callback_task_points_mail( $string, $task ) {
		$get_args = array(
			'post_id'  => $task->data['id'],
			'orderby'  => 'meta_value_num',
			'order'    => 'ASC',
			'meta_key' => '_tm_order',
			'status'   => 1,
		);

		$list_point = Point_Class::g()->get( $get_args );

		$list_point_completed = array_filter( $list_point, function( $point ) {
			if ( empty( $point->data['id'] ) ) {
				return false;
			}
			return true === $point->data['completed'];
		} );

		$list_point_uncompleted = array_filter( $list_point, function( $point ) {
			if ( empty( $point->data['id'] ) ) {
				return false;
			}

			return false === $point->data['completed'];
		} );

		$string .= '<h3>' . __( 'Incompleted', 'task-manager' ) . '</h3>';
		if ( ! empty( $list_point_uncompleted ) ) :
			$string .= '<ul>';
			foreach ( $list_point_uncompleted as $element ) :
				$string .= '<li>#' . $element->data['id'] . ' - ' . $element->data['content'] . '</li>';
			endforeach;
			$string .= '</ul>';
		endif;

		return $string;
	}

	public function add_point_to_index( $object, $args ) {
	
	}
}

new Point_Filter();
