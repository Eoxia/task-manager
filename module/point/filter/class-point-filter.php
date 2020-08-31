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

	public function tm_projects_wpeo_point_def( $output, $point ) {
		$output['classes'] = 'table-type-task';

		if ( $point->data['completed'] ) {
			$output['classes'] .= ' task-completed';
		}

		$output['attrs'][] = 'data-id="' . $point->data['id'] . '"';
		$output['attrs'][] = 'data-post-id="' . $point->data['post_id'] . '"';
		$output['attrs'][] = 'data-nonce="' . wp_create_nonce( 'edit_point' ) . '"';

		$output['element_id'] = $point->data['id'];
		$output['project_id'] = $point->data['post_id'];

		$output['type'] = 'point';

		return $output;
	}

	public function fill_value_empty_value( $output, $point ) {
		$output['classes'] .= ' cell-toggle task-toggle-comment';
		$output['attrs'] = array(
			'data-id="' . $point->data['id'] . '"',
			'data-nonce="' . wp_create_nonce( 'load_comments' ) . '"',
		);

		$output['count_comments'] = $point->data['count_comments'];

		return $output;
	}

	public function fill_value_state_value( $output, $point) {
		$output['classes'] .= ' task-complete-point';
		$output['point']    = $point;
		return $output;
	}

	public function fill_value_archive_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_id_value( $output, $point ) {
		$output['classes'] .= ' cell-readonly';
		$output['value'] = $point->data['id'];

		return $output;
	}

	public function fill_value_last_update_value( $output, $point ) {
		$output['classes']            .= ' task-last-update cell-readonly';
//		$output['date_modified_mysql'] = Point_Class::g()->get_point_last_update( $point->data['post_id'] );
//		$output['date_modified_date']  = $point->data['date']['rendered']['date'];
		return $output;
	}

	public function fill_value_name_value( $output, $point ) {
		$output['classes'] .= ' cell-content';
		$output['value']    = $point->data['content'];

		return $output;
	}

	public function fill_value_time_value( $output, $point ) {
		$output['classes'] .= ' cell-readonly';
		$output['value'] = $point->data['time_info']['elapsed'];
		$time = $point->data['time_info']['elapsed'] * 60;
		if ( $time > 0 ) {
			$output['human_readable_elapsed'] = Task_Class::g()->time_elapsed( $time  );
		} else {
			$output['human_readable_elapsed'] = 0;
		}

		return $output;
	}

	public function fill_value_created_date_value( $output, $point ) {
		$output['classes'] .= ' task-created-date';
		$output['raw']      = $point->data['date']['raw'];
		$output['value']    = $point->data['date']['rendered']['date_time'];

		return $output;
	}

	public function fill_value_ended_date_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_indicators_value( $output, $point ) {
		$output['classes'] .= ' cell-readonly';

		return $output;
	}

	public function fill_value_affiliated_with_value( $output, $point ) {
		return $output;
	}

	public function fill_value_categories_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_attachments_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_number_comments_value( $output, $point ) {
		$output['classes'] .= ' cell-readonly';
		$output['value']    = $point->data['count_comments'];

		return $output;
	}

	public function fill_value_author_value( $output, $point ) {
		$output['classes'] .= ' cell-readonly';
		$output['value']    = $point->data['author_id'];

		return $output;
	}

	public function fill_value_associated_users_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_participants_value( $output, $point ) {
		$output['classes'] .= ' cell-disabled';

		return $output;
	}

	public function fill_value_waiting_for_value( $output, $point ) {
		$output['id'] = $point->data['id'];

		return $output;
	}

	public function fill_value_empty_add_value( $output, $point ) {
		$output['classes']  .= ' cell-sticky task-add';
		$output['task_id']   = $point->data['post_id'];
		$output['point_id']  = $point->data['id'];
		$output['point']    = $point;
		return $output;
	}

}

new Point_Filter();
