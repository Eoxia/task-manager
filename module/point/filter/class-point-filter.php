<?php
/**
 * Gestion des filtres relatives aux points
 *
 * @since 1.3.4
 * @version 1.8.0
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

		add_filter( 'tm_task_header', array( $this, 'callback_display_points_type_buttons' ), 9, 2 );
		add_filter( 'tm_point_summary', array( $this, 'callback_tm_point_summary' ), 10, 2 );

		if ( ! empty( Task_Class::g()->contents['headers'] ) ) {
			foreach ( Task_Class::g()->contents['headers'] as $key => $header ) {
				if ( method_exists ( $this, 'tm_projects_wpeo_point_def') ) {
					add_filter( 'tm_projects_wpeo_point_def', array( $this, 'tm_projects_wpeo_point_def' ), 10, 2 );
				}

				if ( method_exists ( $this, 'fill_value_' . $key . '_value') ) {
					add_filter( 'tm_projects_content_wpeo_point_' . $key . '_def', array( $this, 'fill_value_' . $key . '_value' ), 10, 2 );
				}
			}
		}
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

		$list_point_completed = array_filter(
			$list_point,
			function( $point ) {
				if ( empty( $point->data['id'] ) ) {
					return false;
				}
				return true === $point->data['completed'];
			}
		);

		$list_point_uncompleted = array_filter(
			$list_point,
			function( $point ) {
				if ( empty( $point->data['id'] ) ) {
					return false;
				}

				return false === $point->data['completed'];
			}
		);

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

	/**
	 * Filtre permettant d'afficher les boutons de choix des types de points (complets/incomplets) à afficher dans une tâche.
	 *
	 * @param  string     $current_content Le contenu actuel du filtre.
	 * @param  Task_Model $task            La définition complète de la tâche.
	 *
	 * @return string                      La chaine a afficher.
	 */
	public function callback_display_points_type_buttons( $current_content, $task ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'point',
			'backend/task-header',
			array(
				'task' => $task,
			)
		);
		$current_content .= ob_get_clean();

		return $current_content;
	}

	/**
	 * Résumé du point
	 *
	 * @param  [type] $output [vue].
	 * @param  [type] $point  [point].
	 * @return [type] $output [vue]
	 */
	public function callback_tm_point_summary( $output, $point ) {
		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( $user->data['_tm_advanced_display'] ) {
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'point',
				'backend/point-summary',
				array(
					'point' => $point,
				)
			);
			$output .= ob_get_clean();
		}

		return $output;
	}

	public function fill_value_empty_value( $output, $point ) {
		$output['classes'] .= ' task-toggle-comment';
		$output['attrs'] = array(
			'data-id="' . $point->data['id'] . '"',
			'data-nonce="' . wp_create_nonce( 'load_comments' ) . '"',
		);

		return $output;
	}

	public function fill_value_id_value( $output, $point ) {
		$output['value'] = $point->data['id'];

		return $output;
	}
	public function fill_value_name_value( $output, $point ) {
		$output['value'] = $point->data['content'];

		return $output;
	}

}

new Point_Filter();
