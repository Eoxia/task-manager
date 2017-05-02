<?php
/**
 * Gestion des tâches
 *
 * @package Task Manager
 * @subpackage Module/task
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des tâches.
 */
class Task_Class extends Post_Class {

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
	protected $model_name 	= '\task_manager\Task_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $post_type	= 'wpeo-task';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key		= 'wpeo_task';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'task-manager/task';

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
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.0.0.0
	 */
	protected function construct() {
		parent::construct();
	}

	public function get_tasks( $param ) {
		global $wpdb;

		$param['offset'] = ! empty( $param['offset'] ) ? (int) $param['offset'] : 0;
		$param['posts_per_page'] = ! empty( $param['posts_per_page'] ) ? (int) $param['posts_per_page'] : -1;
		$param['users_id'] = ! empty( $param['users_id'] ) ? (array) $param['users_id'] : array();
		$param['categories_id'] = ! empty( $param['categories_id'] ) ? (array) $param['categories_id'] : array();
		$param['status'] = ! empty( $param['status'] ) ? sanitize_text_field( $param['status'] ) : 'publish';
		$param['post_parent'] = ! empty( $param['post_parent'] ) ? (int) $param['post_parent'] : 0;

		$tasks = array();
		$tasks_id = array();

		$query = "SELECT DISTINCT TASK.ID FROM {$wpdb->posts} AS TASK
			LEFT JOIN {$wpdb->comments} AS POINT ON POINT.comment_post_ID=TASK.ID
			LEFT JOIN {$wpdb->comments} AS COMMENT ON COMMENT.comment_post_id=TASK.ID
			LEFT JOIN {$wpdb->postmeta} AS TASK_META ON TASK_META.post_id=TASK.ID AND TASK_META.meta_key='wpeo_task'
			LEFT JOIN {$wpdb->term_relationships} AS CAT ON CAT.object_id=TASK.ID
		WHERE TASK.post_type='wpeo-task'
			AND TASK.post_status='" . $param['status'] . "' AND
				( (
					TASK.ID LIKE '%" . $param['term'] . "%' OR TASK.post_title LIKE '%" . $param['term'] . "%'
				) OR (
					POINT.comment_ID LIKE '%" . $param['term'] . "%' OR POINT.comment_content LIKE '%" . $param['term'] . "%'
				) OR (
					COMMENT.comment_parent != 0 AND (COMMENT.comment_id LIKE '%" . $param['term'] . "%' OR COMMENT.comment_content LIKE '%" . $param['term'] . "%')
				) )";

		if ( isset( $param['post_parent'] ) ) {
			$query .= 'AND TASK.post_parent="' . $param['post_parent'] . '"';
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
			$tasks = Task_Class::g()->get( array(
				'include' => $tasks_id,
				'post_status' => $param['status'],
			) );
		}

		return $tasks;
	}

	/**
	 * Charges les tâches, et fait le rendu.
	 *
	 * @param  array $param Les paramètres pour charger les tâches.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function display_tasks( $tasks ) {
		View_Util::exec( 'task', 'backend/tasks', array(
			'tasks' => $tasks,
		) );
	}
}

Task_Class::g();
