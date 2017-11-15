<?php
/**
 * Gestion des tâches.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des tâches.
 */
class Task_Class extends \eoxia\Post_Class {

	/**
	 * Toutes les couleurs disponibles pour une t$ache
	 *
	 * @var array
	 */
	public $colors = array(
		'white',
		'red',
		'yellow',
		'green',
		'blue',
		'purple',
	);

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\Task_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type = 'wpeo-task';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_task';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'task';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = 'wpeo_tag';

	/**
	 * La fonction appelée automatiquement après la récupération de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_get_function = array( '\task_manager\get_full_task' );

	/**
	 * La fonction appelée automatiquement après la création de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_post_function = array( '\task_manager\get_full_task' );

	/**
	 * La fonction appelée automatiquement après la mise à jour de l'objet dans la base de donnée.
	 *
	 * @var array
	 */
	protected $after_put_function = array( '\task_manager\get_full_task' );

	/**
	 * Récupères les tâches.
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 *
	 * @param array $param {
	 *                      Les propriétés du tableau.
	 *
	 *                      @type integer $id(optional)              L'ID de la tâche.
	 *                      @type integer $offset(optional)          Sautes x tâches.
	 *                      @type integer $posts_per_page(optional)  Le nombre de tâche.
	 *                      @type array   $users_id(optional)        Un tableau contenant l'ID des utilisateurs.
	 *                      @type array   $categories_id(optional)   Un tableau contenant le TERM_ID des categories.
	 *                      @type string  $status(optional)          Le status des tâches.
	 *                      @type integer $post_parent(optional)     L'ID du post parent.
	 *                      @type string  $term(optional)            Le terme pour rechercher une tâche.
	 * }.
	 * @return array        La liste des tâches trouvées.
	 */
	public function get_tasks( $param ) {
		global $wpdb;

		$param['id'] = isset( $param['id'] ) ? (int) $param['id'] : 0;
		$param['offset'] = ! empty( $param['offset'] ) ? (int) $param['offset'] : 0;
		$param['posts_per_page'] = ! empty( $param['posts_per_page'] ) ? (int) $param['posts_per_page'] : -1;
		$param['users_id'] = ! empty( $param['users_id'] ) ? (array) $param['users_id'] : array();
		$param['categories_id'] = ! empty( $param['categories_id'] ) ? (array) $param['categories_id'] : array();
		$param['status'] = ! empty( $param['status'] ) ? sanitize_text_field( $param['status'] ) : 'any';
		$param['post_parent'] = ! empty( $param['post_parent'] ) ? (array) $param['post_parent'] : array( 0 );
		$param['term'] = ! empty( $param['term'] ) ? sanitize_text_field( $param['term'] ) : '';

		$tasks = array();
		$tasks_id = array();

		if ( ! empty( $param['status'] ) ) {
			if ( 'any' === $param['status'] ) {
				$param['status'] = '"publish","pending","draft","future","private","inherit"';
			} else {
				// Ajout des apostrophes.
				$param['status'] = '"' . $param['status'] . '"';

				// Entre chaque virgule.
				$param['status'] = str_replace( ',', '","', $param['status'] );
			}
		}

		$param = apply_filters( 'task_manager_get_tasks_args', $param );

		if ( ! empty( $param['id'] ) ) {
			$tasks = self::g()->get( array(
				'id' => (int) $param['id'],
			) );
		} else {

			$query = "SELECT DISTINCT TASK.ID FROM {$wpdb->posts} AS TASK
				LEFT JOIN {$wpdb->comments} AS POINT ON POINT.comment_post_ID=TASK.ID
				LEFT JOIN {$wpdb->comments} AS COMMENT ON COMMENT.comment_post_id=TASK.ID
				LEFT JOIN {$wpdb->postmeta} AS TASK_META ON TASK_META.post_id=TASK.ID AND TASK_META.meta_key='wpeo_task'
				LEFT JOIN {$wpdb->term_relationships} AS CAT ON CAT.object_id=TASK.ID
			WHERE TASK.post_type='wpeo-task'
				AND TASK.post_status IN (" . $param['status'] . ") AND
					( (
						TASK.ID LIKE '%" . $param['term'] . "%' OR TASK.post_title LIKE '%" . $param['term'] . "%'
					) OR (
						POINT.comment_ID LIKE '%" . $param['term'] . "%' OR POINT.comment_content LIKE '%" . $param['term'] . "%'
					) OR (
						COMMENT.comment_parent != 0 AND (COMMENT.comment_id LIKE '%" . $param['term'] . "%' OR COMMENT.comment_content LIKE '%" . $param['term'] . "%')
					) )";

			if ( isset( $param['post_parent'] ) ) {
				$query .= 'AND TASK.post_parent IN (' . implode( $param['post_parent'], ',' ) . ')';
			}

			if ( ! empty( $param['users_id'] ) ) {
				$query .= "AND (
					(
						TASK_META.meta_value REGEXP '{\"user_info\":{\"owner_id\":" . implode( $param['users_id'], '|' ) . ",'
					) OR (
						TASK_META.meta_value LIKE '%affected_id\":[" . implode( $param['users_id'], '|' ) . "]%'
					) OR (
						TASK_META.meta_value LIKE '%affected_id\":[" . implode( $param['users_id'], '|' ) . ",%'
					) OR (
						TASK_META.meta_value REGEXP 'affected_id\":\\[[0-9,]+" . implode( $param['users_id'], '|' ) . "\\]'
					) OR (
						TASK_META.meta_value REGEXP 'affected_id\":\\[[0-9,]+" . implode( $param['users_id'], '|' ) . "[0-9,]+\\]'
					)
				)";
			}

			if ( ! empty( $param['categories_id'] ) ) {
				$query .= "AND (";

				if ( ! empty( $param['categories_id'] ) ) {
					foreach ( $param['categories_id'] as $cat_id ) {
						$query .= '(CAT.term_taxonomy_id=' . $cat_id . ') OR';
					}
				}

				$query = substr( $query, 0, strlen( $query ) - 2 );
				$query .= ')';
			}

			$query .= " ORDER BY TASK.post_date DESC ";

			if ( -1 !== $param['posts_per_page'] ) {
				$query .= "LIMIT " . $param['offset'] . "," . $param['posts_per_page'];
			}

			$tasks_id = $wpdb->get_col( $query );

			if ( ! empty( $tasks_id ) ) {
				$tasks = self::g()->get( array(
					'include' => $tasks_id,
					'post_status' => $param['status'],
				) );
			} // End if().

		} // End if().

		return $tasks;
	}

	/**
	 * Charges les tâches, et fait le rendu.
	 *
	 * @param  array $param Les paramètres pour charger les tâches.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.5.0
	 *
	 * @todo: With_wrapper ?
	 */
	public function display_tasks( $tasks, $frontend = false ) {
		if ( $frontend ) {
			\eoxia\View_Util::exec( 'task-manager', 'task', 'frontend/tasks', array(
				'tasks' => $tasks,
				'with_wrapper' => false,
			) );
		} else {
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/tasks', array(
				'tasks' => $tasks,
				'with_wrapper' => false,
			) );
		}
	}

	/**
	 * Fait le rendu de la metabox
	 *
	 * @param  WP_Post $post les données du post.
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function callback_render_metabox( $post ) {
		$parent_id = $post->ID;
		$user_id = $post->post_author;

		$tasks = array();
		$task_ids_for_history = array();
		$total_time_elapsed = 0;
		$total_time_estimated = 0;

		// Affichage des tâches de l'élément sur lequel on se trouve.
		$tasks[ $post->ID ]['title'] = '';
		$tasks[ $post->ID ]['data'] = self::g()->get_tasks( array(
			'post_parent' => $post->ID,
		) );

		if ( ! empty( $tasks[ $post->ID ]['data'] ) ) {
			foreach ( $tasks[ $post->ID ]['data'] as $task ) {
				if ( empty( $tasks[ $post->ID ]['total_time_elapsed'] ) ) {
					$tasks[ $post->ID ]['total_time_elapsed'] = 0;
				}

				$tasks[ $post->ID ]['total_time_elapsed'] += $task->time_info['elapsed'];
				$total_time_elapsed += $task->time_info['elapsed'];
				$total_time_estimated += $task->last_history_time->estimated_time;

				$task_ids_for_history[] = $task->id;
			}
		}

		// Récupération des enfants de l'élément sur lequel on se trouve.
		$args = array(
			'post_parent' => $post->ID,
			'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
			'numberposts' => -1,
			'post_status' => 'any',
		);
		$children = get_posts( $args );

		if ( ! empty( $children ) ) {
			foreach ( $children as $child ) {
				$tasks[ $child->ID ]['title'] = sprintf( __( 'Task for %1$s', 'task-manager' ), $child->post_title );
				$tasks[ $child->ID ]['data'] = \task_manager\Task_Class::g()->get_tasks( array(
					'post_parent' => $child->ID,
				) );

				if ( empty( $tasks[ $child->ID ]['data'] ) ) {
					unset( $tasks[ $child->ID ] );
				}

				if ( ! empty( $tasks[ $child->ID ]['data'] ) ) {
					foreach ( $tasks[ $child->ID ]['data'] as $task ) {
						if ( empty( $tasks[ $child->ID ]['total_time_elapsed'] ) ) {
							$tasks[ $child->ID ]['total_time_elapsed'] = 0;
						}
						$tasks[ $child->ID ]['total_time_elapsed'] += $task->time_info['elapsed'];
						$total_time_elapsed += $task->time_info['elapsed'];
						$total_time_estimated += $task->last_history_time->estimated_time;

						$task_ids_for_history[] = $task->id;
					}
				}
			}
		}

		$total_time_elapsed = \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time_elapsed );
		$total_time_estimated = \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time_estimated );

		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/metabox-posts', array(
			'tasks'                 => $tasks,
			'task_ids_for_history'  => implode( ',', $task_ids_for_history ),
			'total_time_elapsed'    => $total_time_elapsed,
			'total_time_estimated'  => $total_time_estimated,
		) );
	}

}

Task_Class::g();
