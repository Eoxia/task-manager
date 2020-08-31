<?php
/**
 * Gestion des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
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
	 * @var column_def
	 *
	 * Définition des colonnes du tableau.
	 */
	public $contents;

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
	protected $type = 'wpeo-task';

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

	protected function construct() {
		$this->contents = array(
			'headers' => array(
				'empty' => array(
					'icon'          => null,
					'title'         => null,
					'classes'       => 'table-75 no-hidden no-order',
					'can_be_hidden' => false,
				),
				'state' => array(
					'icon'          => 'fas fa-check-square',
					'title'         => __( 'State', 'task-manager' ),
					'classes'       => 'table-150',
					'can_be_hidden' => true,
				),
				'archive' => array(
					'icon'          => 'fas fa-archive',
					'title'         => __( 'Status', 'task-manager' ),
					'classes'       => 'table-75',
					'can_be_hidden' => true,
				),
				'name' => array(
					'icon'          => 'fas fa-thumbtack',
					'title'         => __( 'Content', 'task-manager' ),
					'classes'       => 'table-500',
					'can_be_hidden' => true,
				),
				'id' => array(
					'icon'          => 'fas fa-hashtag',
					'title'         => __( 'ID', 'task-manager' ),
					'classes'       => 'table-50',
					'can_be_hidden' => true,
				),
				'last_update' => array(
					'icon'          => null,
					'title'         => __( 'Last Update', 'task-manager' ),
					'classes'       => 'table-150',
					'can_be_hidden' => true,
				),
				'time' => array(
					'icon'          => 'far fa-clock',
					'title'         => __( 'Time (min)', 'task-manager' ),
					'classes'       => 'table-100',
					'can_be_hidden' => true,
				),
				'created_date' => array(
					'icon'          => 'far fa-calendar-alt',
					'title'         => __( 'Created Date', 'task-manager' ),
					'classes'       => 'table-150',
					'can_be_hidden' => true,
				),
				'ended_date' => array(
					'icon'          => 'far fa-calendar-alt',
					'title'         => __( 'Ended Date', 'task-manager' ),
					'classes'       => 'table-150',
					'can_be_hidden' => true,
				),
				'indicators'  => array(
					'icon'          => 'fas fa-check',
					'title'         => __( 'Indicators', 'task-manager' ),
					'classes'       => 'table-100',
					'can_be_hidden' => true,
				),
				'affiliated_with' => array(
					'icon'          => 'fas fa-link',
					'title'         => __( 'Parent', 'task-manager' ),
					'classes'       => 'table-350',
					'can_be_hidden' => true,
				),
				'categories' => array(
					'icon'          => 'fas fa-tags',
					'title'         => __( 'Categories', 'task-manager' ),
					'classes'       => 'table-250',
					'can_be_hidden' => true,
				),
				'attachments' => array(
					'icon'          => 'fas fa-paperclip',
					'title'         => __( 'Attachments', 'task-manager' ),
					'classes'       => 'table-125',
					'can_be_hidden' => true,
				),
				'number_comments' => array(
					'icon'          => 'far fa-comment-dots',
					'title'         => __( 'Com nb', 'task-manager' ),
					'classes'       => 'table-100',
					'can_be_hidden' => true,
				),
				'author' => array(
					'icon'          => 'fas fa-user',
					'title'         => __( 'Author', 'task-manager' ),
					'classes'       => 'table-75',
					'can_be_hidden' => true,
				),
				'associated_users' => array(
					'icon'          => 'fas fa-users',
					'title'         => __( 'Associated Users', 'task-manager' ),
					'classes'       => 'table-200',
					'can_be_hidden' => true,
				),
				'participants' => array(
					'icon'          => 'fas fa-users',
					'title'         => __( 'Participants', 'task-manager' ),
					'classes'       => 'table-200',
					'can_be_hidden' => true,
				),
				'waiting_for' => array(
					'icon'          => 'fas fa-user',
					'title'         => __( 'Waiting For', 'task-manager' ),
					'classes'       => 'table-125',
					'can_be_hidden' => true,
				),
				'empty_add' => array(
					'icon'          => null,
					'title'         => null,
					'classes'       => 'table-100 no-hidden no-order',
					'can_be_hidden' => false,
				),
			),
			'bodies' => array(

			),
		);

		$this->contents = apply_filters( 'tm_tasks_contents', $this->contents );


	}

	/**
	 * Permet d'ajouter le post_status 'archive'.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param array   $args   Les paramètres à appliquer pour la récupération @see https://codex.wordpress.org/Function_Reference/WP_Query.
	 * @param boolean $single Si on veut récupérer un tableau, ou qu'une seule entrée.
	 *
	 * @return Object
	 */
	public function get( $args = array(), $single = false ) {
		$array_posts = array();

		// Définition des arguments par défaut pour la récupération des "posts".
		$default_args = array(
			'post_status'    => array(
				'any',
				'archive',
			),
			'post_type'      => $this->get_type(),
			'posts_per_page' => -1,
		);

		$final_args = wp_parse_args( $args, $default_args );

		return parent::get( $final_args, $single );
	}

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
	public function get_tasks( $param, $count = false ) {
		global $wpdb;

		$param['id']              = isset( $param['id'] ) ? (int) $param['id'] : 0;
		$param['task_id']         = isset( $param['task_id'] ) ? (int) $param['task_id'] : 0;
		$param['point_id']        = isset( $param['point_id'] ) ? (int) $param['point_id'] : 0;
		$param['offset']          = ! empty( $param['offset'] ) ? (int) $param['offset'] : 0;
		$param['posts_per_page']  = ! empty( $param['posts_per_page'] ) ? (int) $param['posts_per_page'] : -1;
		$param['users_id']        = ! empty( $param['users_id'] ) ? (array) $param['users_id'] : array();
		$param['categories_id']   = ! empty( $param['categories_id'] ) ? (array) $param['categories_id'] : array();
		$param['status']          = ! empty( $param['status'] ) ? sanitize_text_field( $param['status'] ) : 'any';
		$param['post_parent']     = ! empty( $param['post_parent'] ) ? (array) $param['post_parent'] : 0;
		$param['term']            = ! empty( $param['term'] ) ? sanitize_text_field( $param['term'] ) : '';
		$param['not_parent_type'] = ! empty( $param['not_parent_type'] ) ? (array) $param['not_parent_type'] : null;

		$tasks_id = array();
		$tasks = array();

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

		$point_type = Point_Class::g()->get_type();

		$comment_type = Task_Comment_Class::g()->get_type();

		$query = "SELECT DISTINCT TASK.ID FROM {$wpdb->posts} AS TASK
			LEFT JOIN {$wpdb->posts} AS PARENT ON PARENT.ID=TASK.post_parent
			LEFT JOIN {$wpdb->comments} AS POINT ON POINT.comment_post_id=TASK.ID AND POINT.comment_approved = 1 AND POINT.comment_type = '{$point_type}'
			LEFT JOIN {$wpdb->comments} AS COMMENT ON COMMENT.comment_parent=POINT.comment_id AND COMMENT.comment_approved = 1 AND POINT.comment_approved = 1 AND COMMENT.comment_type = '{$comment_type}'
			LEFT JOIN {$wpdb->postmeta} AS TASK_META ON TASK_META.post_id=TASK.ID AND TASK_META.meta_key='wpeo_task'
			LEFT JOIN {$wpdb->term_relationships} AS CAT ON CAT.object_id=TASK.ID
		WHERE TASK.post_type='wpeo-task'";


		if ( ! empty( $param['not_parent_type'] ) ) {
			$query .= ' AND (PARENT.ID IS NULL OR PARENT.post_type NOT IN ("' . implode( $param['not_parent_type'], ',' ) . '" ) )';
		}

		$query .= 'AND TASK.post_status IN (' . $param['status'] . ')';

		if ( ! empty( $param['post_parent'] ) && ! is_null( $param['post_parent'] ) ) {
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
			$sub_query = '   ';
			foreach ( $param['categories_id'] as $cat_id ) {
				$sub_query .= '(CAT.term_taxonomy_id=' . $cat_id . ') OR';
			}

			$sub_query = substr( $sub_query, 0, -3 );
			if ( ! empty( $sub_query ) ) {
				$query .= "AND ({$sub_query})";
			}
		}

		$sub_where = '';

		if ( ! empty( $param['term'] ) ) {
			$sub_where = "
				(
					TASK.ID LIKE '%" . $param['term'] . "%' OR TASK.post_title LIKE '%" . $param['term'] . "%'
				) OR (
					POINT.comment_id LIKE '%" . $param['term'] . "%' OR POINT.comment_content LIKE '%" . $param['term'] . "%'
				) OR (
					COMMENT.comment_parent != 0 AND (COMMENT.comment_id LIKE '%" . $param['term'] . "%' OR COMMENT.comment_content LIKE '%" . $param['term'] . "%')
				)";
		}

		if ( $param['task_id'] ) {
			if ( ! empty( $sub_where ) ) {
				$sub_where .= ' OR (TASK.ID = ' . $param['task_id'] . ')';
			} else {
				$sub_where .= ' (TASK.ID = ' . $param['task_id'] . ')';
			}
		}

		if ( $param['point_id'] ) {
			if ( ! empty( $sub_where ) ) {
				$sub_where .= ' OR (POINT.comment_id = ' . $param['point_id'] . ')';
			} else {
				$sub_where .= ' (POINT.comment_id = ' . $param['point_id'] . ')';
			}
		}

		if ( ! empty( $sub_where ) ) {
			$query .= ' AND (' . $sub_where . ')';
		}


		$query .= ' ORDER BY TASK.post_date DESC ';

		if ( -1 !== $param['posts_per_page'] && ! $count ) {
			$query .= 'LIMIT ' . $param['offset'] . ',' . $param['posts_per_page'];
		}

		$tasks_id = $wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( ! empty( $tasks_id ) && ! $count ) {
			$tasks = self::g()->get(
				array(
					'post__in'    => $tasks_id,
					'post_status' => $param['status'],
				)
			);
		} // End if().

		return $count ? count ( $tasks_id ) : $tasks;
	}

	public function display_headers( $elements ) {
		$user_def = Follower_Class::g()->user_columns_def;

		$new_orders_headers = array();

		if ( ! empty( $user_def ) ) {
			foreach( $user_def as $key => $def ) {
				// On donne la clé dans l'index clé de clé.
				$this->contents['headers'][ $key ]['key'] = $key;
				$new_orders_headers[ $def['order'] ]      = $this->contents['headers'][ $key ];
			}
		}

		uksort( $new_orders_headers, function( $a, $b ) {
			return ( $a > $b ) ? 1 : -1;
		} );

		$this->contents['headers'] = $new_orders_headers;

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'New/headers',
			array(
				'headers'      => $this->contents['headers'],
				'contents'     => $elements,
				'with_wrapper' => false,
			)
		);
	}

	public function display( $elements ) {
		$this->display_headers( $elements );
	}

	public function display_bodies( $elements, $parent = null ) {
		$user_def = Follower_Class::g()->user_columns_def;

		if ( ! empty( $elements ) ) {
			foreach ( $elements as $element ) {
				$data_def = array(
					'classes' => '',
					'attrs'   => array(),
					'values'  => array()
				);

				$data_def = apply_filters( 'tm_projects_' . $element->data['type'] . '_def', $data_def, $element );

				foreach ( $user_def as $key => $def ) {
					$header                              = $this->contents['headers'][ $key ];
					$data_def['values'][ $def['order'] ] = array(
						'value'   => '',
						'classes' => $header['classes'],
						'attrs'   => array(),
						'type'    => $element->data['type'],
						'key'     => $key,
					);

					$data_def['values'][ $def['order'] ] = apply_filters( 'tm_projects_content_' . $element->data['type'] . '_' . $key . '_def', $data_def['values'][ $def['order'] ], $element );
				}

				uksort( $data_def['values'], function( $a, $b ) {
					return ( $a > $b ) ? 1 : -1;
				} );

				$this->contents['bodies'][] = $data_def;
			}
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'New/bodies',
			array(
				'contents'     => $this->contents,
				'parent'       => $parent,
				'with_wrapper' => false,
			)
		);
	}

	/**
	 * Charges les tâches, et fait le rendu.
	 *
	 * @param array $tasks    La liste des tâches qu'il faut afficher.
	 * @param bool  $frontend L'affichage aura t il lieu dans le front ou le back.
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.6.0
	 *
	 * @todo: With_wrapper ?
	 */
	public function display_tasks( $elements, $frontend = false ) {

	}

	/**
	 * Fait le rendu de la metabox
	 *
	 * @param  WP_Post $post les données du post.
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_render_metabox( $post, $array = array(), $args_parameter = array(), $post_id = 0 ) {

		if( ! empty( $post ) ){
			$post_id = $post->ID;
		}

		$parent_id = $post_id;
		// $user_id   = $post->post_author;

		$tasks                = array();
		$task_ids_for_history = array();
		$total_time_elapsed   = 0;
		$total_time_estimated = 0;


		$posts_per_page_task = self::g()->get_task_per_page_for_this_user( get_current_user_id() ); // Définis le nombre de tache / page (client)

		// Affichage des tâches de l'élément sur lequel on se trouve.
		$tasks[ $post_id ]['title'] = '';

		$args = array(
			'post_parent' => $post_id,
			'post_status' => 'publish,pending,draft,future,private,inherit'
		);

		if( empty( $args_parameter )  ){ // Par défaut on affiche 5 élements / page
			$args_parameter = array(
				'offset' => 0,
				'post_status' => 'publish,pending,draft,future,private,inherit'
			);
		}

		$args_parameter[ 'posts_per_page' ] = $posts_per_page_task;

		// $tasks[ $post_id ]['data'] = self::g()->get_tasks( wp_parse_args( $args_parameter, $args ) ); // 27/06/2019
		$tasks[ $post_id ]['data'] = Task_Class::g()->get( wp_parse_args( $args_parameter, $args ) );

		$temp_array = wp_parse_args( $args_parameter, $args );
		$temp_array[ 'offset' ] = 0;
		$temp_array[ 'posts_per_page' ] = -1;

		$number_task = count( Task_Class::g()->get( $temp_array ) );

		$count_pages = 0;
		if( $number_task > 0 ){
			$count_pages = intval( $number_task / $posts_per_page_task );
			if( intval( $number_task % $posts_per_page_task ) > 0 ){
				$count_pages++;
			}
		}

		$offset = 1;
		if( isset( $args_parameter[ 'offset' ] ) && ! empty( $args_parameter[ 'offset' ] ) ){
			$offset = intval( $args_parameter[ 'offset' ] / $posts_per_page_task ) +1;
		}

		if ( ! empty( $tasks[ $post_id ]['data'] ) ) {
			foreach ( $tasks[ $post_id ]['data'] as $task ) {
				if ( empty( $tasks[ $post_id ]['total_time_elapsed'] ) ) {
					$tasks[ $post_id ]['total_time_elapsed'] = 0;
				}

				$tasks[ $post_id ]['total_time_elapsed'] += $task->data['time_info']['elapsed'];
				$total_time_elapsed                       += $task->data['time_info']['elapsed'];
				$total_time_estimated                     += $task->data['last_history_time']->data['estimated_time'];

				$task_ids_for_history[] = $task->data['id'];
			}
		}

		// Récupération des enfants de l'élément sur lequel on se trouve.
		$args     = array(
			'post_parent' => $post_id,
			'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
			'numberposts' => -1,
			'post_status' => 'any',
		);
		$children = get_posts( $args );

		if ( ! empty( $children ) ) {
			foreach ( $children as $child ) {
				/* Translators: Titre du post sur lequel on veut afficher les tâches. */
				$tasks[ $child->ID ]['title'] = sprintf( __( 'Task for %1$s', 'task-manager' ), $child->post_title );
				$tasks[ $child->ID ]['data']  = self::g()->get_tasks(
					array(
						'post_parent' => $child->ID,
						'status'      => 'publish,pending,future,private,inherit,archive',
					)
				);

				if ( empty( $tasks[ $child->ID ]['data'] ) ) {
					unset( $tasks[ $child->ID ] );
				}

				if ( ! empty( $tasks[ $child->ID ]['data'] ) ) {
					foreach ( $tasks[ $child->ID ]['data'] as $task ) {
						if ( empty( $tasks[ $child->ID ]['total_time_elapsed'] ) ) {
							$tasks[ $child->ID ]['total_time_elapsed'] = 0;
						}
						$tasks[ $child->ID ]['total_time_elapsed'] += $task->data['time_info']['elapsed'];
						$total_time_elapsed                        += $task->data['time_info']['elapsed'];
						$total_time_estimated                      += $task->data['last_history_time']->data['estimated_time'];

						$task_ids_for_history[] = $task->data['id'];
					}
				}
			}
		}

		$total_time_elapsed   = \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time_elapsed );
		$total_time_estimated = \eoxia\Date_Util::g()->convert_to_custom_hours( $total_time_estimated );

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/metabox-posts',
			array(
				'post_id'              => $post_id,
				'tasks'                => $tasks,
				'task_ids_for_history' => implode( ',', $task_ids_for_history ),
				'total_time_elapsed'   => $total_time_elapsed,
				'total_time_estimated' => $total_time_estimated,
				'offset'               => $offset,
				'count_tasks'          => $count_pages
			)
		);
	}

	/**
	 * Historique de la metabox
	 *
	 * @param  [type] $post [description].
	 * @return void
	 */
	public function callback_render_history_metabox( $post ) {
		$tasks_id = array();

		$tasks = self::g()->get_tasks(
			array(
				'post_parent' => $post->ID,
				'status'      => 'publish,pending,draft,future,private,inherit,archive',
			)
		);

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $task ) {
				$tasks_id[] = $task->data['id'];
			}
		}

		$args     = array(
			'post_parent' => $post->ID,
			'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
			'numberposts' => -1,
			'post_status' => 'any',
		);
		$children = get_posts( $args );

		if ( ! empty( $children ) ) {
			foreach ( $children as $child ) {
				$tasks[ $child->ID ]['data'] = self::g()->get_tasks(
					array(
						'post_parent' => $child->ID,
						'status'      => 'publish,pending,draft,future,private,inherit,archive',
					)
				);

				if ( empty( $tasks[ $child->ID ]['data'] ) ) {
					unset( $tasks[ $child->ID ] );
				}

				if ( ! empty( $tasks[ $post->ID ] ) ) {
					foreach ( $tasks[ $post->ID ]['data'] as $task ) {
						$tasks_id[] = $task->data['id'];
					}
				}
			}
		}

		$date_end   = current_time( 'Y-m-d' );
		$date_start = date( 'Y-m-d', strtotime( '-1 month', strtotime( $date_end ) ) );

		if ( ! empty( $tasks_id ) ) {
			$datas = Activity_Class::g()->get_activity( $tasks_id, 0, $date_start, $date_end );
		}

		if ( ! empty( $tasks_id ) ) {
			\eoxia\View_Util::exec(
				'task-manager',
				'activity',
				'backend/post-last-activity',
				array(
					'datas'      => $datas,
					'date_start' => $date_start,
					'date_end'   => $date_end,
					'tasks_id'   => implode( ',', $tasks_id ),
				)
			);
		}
	}

	public function all_month_between_two_dates( $date_start, $date_end, $delete_first_month = false ){ // premiers mois EXLCUS et denier mois INCLUS
		$dates   = array();
		$current = $date_start;
		$last    = $date_end;

		$temp_month = '';
		$all_month_in_year = array();

		while ( $current <= $last ) {
			if ( date( 'm', $current ) != $temp_month ) {

				$temp_month = date( 'm', $current );

				$all_month_in_year[ count( $all_month_in_year ) ] = array(
					'month' => date( 'm', $current ),
					'year'  => date( 'Y', $current ),
					'name_month' => date_i18n("F", $current ),
					'str_month_start' => strtotime( date( 'd-m-Y', $current ) ),
					'str_month_end' => strtotime( date( 't-m-Y', $current ) ) + 86340,
					'total_time_elapsed' => 0,
					'total_time_estimated' => 0,
					'total_time_deadline' => 0,
					'total_time_percent' => 0,
					'total_time_elapsed_readable' => 0,
					'total_time_estimated_readable' => 0,
					'total_time_deadline_readable' => 0,
					'month_is_valid'  => 0,
					'task_list' => array()
				);
			}

			// on recupère le dernier mois en ENTIER
			//$all_month_in_year[ count( $all_month_in_year ) - 1 ][ 'str_month'] = strtotime( date( 't-m-Y', $all_month_in_year[ count( $all_month_in_year ) - 1 ][ 'str_month' ] ) );

			$dates[] = date( 'd/m/Y', $current );
			$current = strtotime( '+1 day', $current );

		}
		if( $delete_first_month ){
			$all_month_in_year = array_slice( $all_month_in_year, 1 );
		}

		return $all_month_in_year;
	}

	public function update_client_indicator( $postid, $postauthor = 0, $year = 0 ){
		if( ! $year || $year > date("Y") ){
				$year = date("Y");
		}

		return $this->callback_render_indicator( array(), $postid, $postauthor, $year );
	}

/**
 * Cette fonction permet de créer un tableau (pour chaque tache)
 * @param  [type] $task_title      [titre de la tachee].
 * @param  [type] $categorie_title [titre de la catégorie].
 * @param  [type] $categorie_id    [id de la catégorie].
 * @return [array] $data         [data].
 */
	public function return_array_indicator_tasklist( ){
		$data = array(
			'time_elapsed'           => 0, // Temps passé (cumul des commentaires de temps des commentaires)
			'time_estimated'         => 0, // Temps estimé (cumul des temps estimé pour chaque taches )
			'time_estimated_monthly' => 0, // Temps estimé total (Pour une tache monthly, on cumul le temps estimé avec le mois précédent)
			'time_deadline'          => 0, // Temps passé, cumulé des mois précédents
			'time_percent'           => 0, // Utilisé seulement pour l'affichage
			'time_previous_months'   => 0, // Concerne le UNIQUEMENT le premiers mois et le type DEADLINE, cette variable récupère les temps des mois précédents
			'month_is_valid'         => 0 // Cette variable permet de définir si le mois ciblé est valide => Se trouve entre le début de création du mois et la fin de la deadline
		);

		return $data;
	}

	public function generate_data_indicator_client( $tasks, $allmonth, $post_id )
	{
		// $str_start = $allmonth[ 0 ][ 'str_month_start' ]; Ces deux dates permettaient de trier/ enlever
		// $str_end = $allmonth[ count( $allmonth ) - 1 ][ 'str_month_end' ]; les taches qui n'ont pas était modifié dans l'année

		$categories_indicator = array();
		$categories_indicator_info = array();

		if ( empty( $tasks ) )
		{
			return array();
		}

		foreach ( $tasks as $key => $task ) { // Pour chaque tache
			$type = '';
			$args = array( 'post_id' => $task->data[ 'id' ]	);

			// On definie le type pour créer un tableau avec deux élements
			if( $task->data['last_history_time']->data['custom'] == 'recursive' ){ // Si recursif => $categories_indicator_recursive
				$type = 'recursive';
			}else if( $task->data['last_history_time']->data['custom'] == 'due_date' ){ // Si Deadline => $categories_indicator_deadline
				$type = 'deadline';
			}else{ // Si aucun type => RIEN
				continue;
			}

			/*if( ! $str_start < strtotime( $task->data['date_modified'][ 'rendered' ][ 'mysql' ] ) && ! $str_end > strtotime( $task->data['date_modified'][ 'rendered' ][ 'mysql' ] ) ){ // On vérifie que la tache est était modifiée dans l'année
				continue;
			}*/

			if( empty( $task->data['taxonomy'][ 'wpeo_tag' ] ) ){
				$task->data[ 'taxonomy' ][ 'wpeo_tag' ][] = 0;
			}

			foreach ( $task->data['taxonomy'][ 'wpeo_tag' ] as $id_category ) { // Si la tache a plusieurs catégories
				$category_info = array();
				if( $id_category == 0 ){
					$name_categories = __( 'No Category', 'task-manager' );
				}else{
					$categories = get_term_by( 'id', $id_category, 'wpeo_tag' );
					$name_categories = $categories->name;
				}
				if( empty( $categories_indicator[ $type ][ $id_category ] ) ) { // On créait la catégorie
					$categories_indicator[ $type ][ $id_category ] = $allmonth; // tous les mois de l'année
					$category_info = array(
						'name'                    => $name_categories, // Info
						'id'                      => $id_category, // de base
						'type'                    => $type, // De la catégorie
						'time_elapsed'            => 0,
						'time_estimated'          => 0,
						'time_elapsed_readable'   => '',
						'time_estimated_readable' => '',
						'time_percent'            => 0,
					);
				}

				$deadline_task = $task->data[ 'last_history_time' ]->data[ 'due_date' ][ 'rendered' ][ 'mysql' ];
				$history_task = $task->data[ 'last_history_time' ]->data;
				if( $type === 'recursive' ){ // Si la tache est récursive, on ajoute du temps chaque mois
					foreach( $categories_indicator[ $type ][ $id_category ] as $key_categorie => $month ){ // Pour chaque tache, Chaque mois de l'année
						$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ] = $this->return_array_indicator_tasklist();

						if( strtotime( $task->data['date'][ 'rendered' ][ 'mysql' ] ) < $month[ 'str_month_end' ] && strtotime( 'now' ) >= $month[ 'str_month_start' ] ){
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] += $history_task[ 'estimated_time' ];
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] = 1;
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'month_is_valid' ] = 1;

							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] += $history_task[ 'estimated_time' ];

							if( isset( $categories_indicator[ $type ][ $id_category ][ $key_categorie - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] ) ){
								$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] += $categories_indicator[ $type ][ $id_category ][ $key_categorie - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ];
							}
						}
					}

				}else if( $type === 'deadline' && ( isset( $deadline_task ) || strtotime( $deadline_task ) > 0 ) ){ // Task deadline
					foreach( $categories_indicator[ $type ][ $id_category ] as $key_categorie => $month ){ // Pour chaque tache, Chaque mois de l'année
						$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ] = $this->return_array_indicator_tasklist();

						if( strtotime( $task->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) < $month[ 'str_month_end' ] && strtotime( $deadline_task ) > $month[ 'str_month_start' ] ){ // Mois creer avant la fin du mois et Deadline aprés le debut du mois => Mois valide
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated'] = $history_task['estimated_time'];
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] = 1;
							$categories_indicator[ $type ][ $id_category ][ $key_categorie ][ 'month_is_valid' ] = 1;
						}
					}

				}else{
					// Normalement cette condition ne doit pas etre accessible
					echo '<pre>'; print_r( 'Bug 1dz654q1d651dq : Task.class' ); echo '</pre>';
					return array();
				}

				if( ! empty( $category_info ) ){
					$categories_indicator_info[ $type ][ $id_category ][ 'info' ] = $category_info;
				}

				$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'id' ] = $task->data[ 'id' ];
				$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'title' ] = $task->data[ 'title' ];
				$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] = 0;
				$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] = 0;
				// On recupere les commentaires et on les ajoute dans leurs mois respectifs
				$comments = Task_Comment_Class::g()->get_comments( 0, $args );
				if( empty ( $comments ) ){
					continue;
				}

				$first_month = true;
				foreach( $categories_indicator[ $type ][ $id_category ] as $key_month_ => $month ){ // Pour chaque mois de l'année, on va check les commentaires
					//if( $month[ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ){ // On verifie que le mois soit valide
						if( $key_month_ == 0 && $type === 'deadline' ){
							// Si le premier mois est valide, on récupère le temps des commentaires des mois précédents
							foreach ( $comments as $key => $value_com ) {
								if( $month[ 'str_month_start' ] > strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ){
									$categories_indicator[ $type ][ $id_category ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
									$categories_indicator[ $type ][ $id_category ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
								}
							}
						}

					//	if( isset( $categories_indicator[ $type ][ $id_category ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ) && $categories_indicator[ $type ][ $id_category ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ){
					if( isset( $categories_indicator[ $type ][ $id_category ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ) ){
							// On ajoute le temps du commentaire actuel à celui du mois précédent
							$categories_indicator[ $type ][ $id_category ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] = $categories_indicator[ $type ][ $id_category ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ];
						}
						//}

						foreach ( $comments as $key => $value_com ) { // Pour chaque commentaire

							if( $month[ 'str_month_start' ] < strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) && $month[ 'str_month_end' ] > strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ) // Si le commentaire a était fait dans le mois
							{
								$categories_indicator[ $type ][ $id_category ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
								$categories_indicator[ $type ][ $id_category ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];


								$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] = 0;
								$categories_indicator_info[ $type ][ $id_category ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] = 0;
							}
						}
					//}
				}
				// $categories_indicator[ $value_task ] = $this->update_property_this_array( $categories_indicator[ $value_task ] );
			}
		}

		// Cette function permet de récupérer tout le temps passé sur chaque mois pour chaquee tache, pour l'ajouter aux temps totals du mois
		if( ! empty( $categories_indicator ) ){
			foreach( $categories_indicator as $key => $value ){
				$return = $this->update_indicator_array_tasklist( $categories_indicator[ $key ], $categories_indicator_info[ $key ] );
				$categories_indicator[ $key ]      = $return[ 'categories' ];
				$categories_indicator_info[ $key ] = $return[ 'info' ];
			}
		}

		return array( 'data' => $categories_indicator, 'info' => $categories_indicator_info );
	}

	public function callback_render_indicator( $post = array(), $post_id = 0, $post_author = 0, $year = 0 ) {

		if( ! empty ( $post ) ){
			$post_id = $post->ID;
			$post_author = $post->post_author;
		}

		if( ! $year ){
			$indicator_date_start = strtotime( "-1 year" );
			$indicator_date_end = strtotime( "now" ) + 3600;
		}else{
			$indicator_date_start = strtotime( '01-01-' . $year ) - 3600;
			$indicator_date_end  = strtotime( '31-12-' . $year );
		}

		$parent_id = $post_id;
		$user_id   = $post_author;

		$tasks = array();

		$tasks  = self::g()->get_tasks(
			array(
				'post_parent' => $post_id,
				'status'      => 'publish,pending,draft,future,private,inherit,archive'
			)
		);

		$tasks_indicator = array(); // trie toutes les taches
		$categories_indicator = array(); // tries toutes les taches selon les catégories

		$allmonth_betweendates = $this->all_month_between_two_dates( $indicator_date_start, $indicator_date_end, true );

		$return = $this->generate_data_indicator_client( $tasks, $allmonth_betweendates, $post_id );
		$categories_indicator = isset( $return[ 'data' ] ) ? $return[ 'data' ] : array(); // Data principal
		$categories_info = isset( $return[ 'info' ] ) ? $return[ 'info' ] : array(); // Info

		$return = $this->update_data_indicator_humanreadable(  $categories_indicator, $categories_info );
		$categories_indicator = $return[ 'data' ]; // Data principal
		$categories_info = $return[ 'info' ]; // Info

		if( $year ){
			$data_return = array(
				'year' => $year,
				'type' => $categories_indicator,
				'info' => $categories_info,
				'everymonth' => $allmonth_betweendates
			);
			return $data_return;
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/metabox-indicators',
			array(
				'type' => $categories_indicator,
				'info' => $categories_info,
				'everymonth' => $allmonth_betweendates
			)
		);
	}

// Inutilisé depuis 28/03/2019
	public function return_color_from_pourcent( $pourcent ){
		if( ! isset( $pourcent ) ){
			return '#F1F8E9';
		}
		$data = Setting_Class::g()->get_user_settings_indicator_client();

		$value_find = false;
		$color = '#F1F8E9';

		if( ! empty( $data ) ){
			foreach ($data as $key => $value) {

				if( $value[ 'topnumber' ] ){
					if( $value[ 'from_number' ] <= $pourcent ){
						$color = $value[ 'value_color' ];
						$value_find = true;
						continue;
					}
				}

				if( $value[ 'from_number' ] <= $pourcent && $value[ 'to_number' ] > $pourcent ){
					$color = $value[ 'value_color' ];
					$value_find = true;
					continue;
				}
			}
		}

		if( ! $value_find ){ // On prends les valeurs par défault
			switch ( $pourcent ){
				case $pourcent <= 0:
					$color = "#F1F8E9";break;

				case $pourcent <= 50:
					$color = "#CCFF90";break;

				case $pourcent <= 75:
					$color = "#B2FF59";break;

				case $pourcent <= 100;
				$color = "#64DD17"; break;

				case $pourcent <= 150:
					$color = "#FF5722";break;

				default :
					$color = "#DD2C00";
				break;
			}
		}

		return $color;
	}

	public function is_time_excedeed( $pourcent ) {
		if ( empty( $pourcent ) ) {
			return false;
		}

		if ( 100 < $pourcent ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function change_minute_time_to_readabledate( $minute_format ){

		$d = floor ( $minute_format / 1440 );
		$h = floor ( ( $minute_format - $d * 1440 ) / 60 );
		$m = $minute_format - ( $d * 1440 ) - ( $h * 60 );

		return $d . 'j ' . $h . 'h ' . $m . 'm';
	}

	public function update_indicator_array_tasklist( $categories, $info ){
		foreach( $categories as $key_categ => $category ){
			$time_elapsed_categorie = 0;
			$time_estimated_categorie = 0;

			foreach( $category as $key_month => $month ){
				$time_elapsed_month = 0;
				$time_estimated_month = 0;
				$time_deadline_month = 0;

				foreach( $month[ 'task_list' ] as $key_task => $task ){
					$task_elapsed = 0;
					$task_estimated = 0;

					//if( $task[ 'month_is_valid' ] ){
						$time_estimated_month += $task[ 'time_estimated' ];
						$time_deadline_month  += $task[ 'time_deadline' ];
						$time_elapsed_month   += $task[ 'time_elapsed' ];

						if( empty( $info[ $key_categ ][ 'task_list' ] ) ){
							continue;
						}

						if( $info[ $key_categ ][ 'info' ][ 'type' ] == "deadline" ){
							if( $time_estimated_month != 0 ){
								$time_estimated_categorie = $time_estimated_month;
							}

							$info[ $key_categ ][ 'task_list' ][ $key_task ][ 'time_elapsed' ] += $task[ 'time_elapsed' ];
							$info[ $key_categ ][ 'task_list' ][ $key_task ][ 'time_estimated' ] = $task[ 'time_estimated' ];
							$time_elapsed_categorie = $time_deadline_month;
						}else{

							$time_estimated_categorie += $time_estimated_month;

							$info[ $key_categ ][ 'task_list' ][ $key_task ][ 'time_elapsed' ] += $task[ 'time_elapsed' ];
							$info[ $key_categ ][ 'task_list' ][ $key_task ][ 'time_estimated' ] += $task[ 'time_estimated' ];
							$time_elapsed_categorie += $time_elapsed_month;
						}
					//}
				}

				$categories[ $key_categ ][ $key_month ][ 'total_time_elapsed' ]   = $time_elapsed_month;
				$categories[ $key_categ ][ $key_month ][ 'total_time_deadline' ]  = $time_deadline_month;
				$categories[ $key_categ ][ $key_month ][ 'total_time_estimated' ] = $time_estimated_month;
			}

			$info[ $key_categ ][ 'info' ][ 'time_elapsed' ] = $time_elapsed_categorie;
			$info[ $key_categ ][ 'info' ][ 'time_estimated' ] = $time_estimated_categorie;

		}

		$return = array(
			'categories' => $categories,
			'info' => $info
		);

		return $return;
	}

	public function update_data_indicator_humanreadable( $categories, $info ){
		foreach( $categories as $key_type => $value_type ){ // Deadline / recusive
			foreach( $value_type as $key_cat=> $value_cat ){ // All categories
				foreach( $value_cat as $key_month => $value_month ){
					$temp_month = array(
						'total_time_elapsed_readable'   => $this->change_minute_time_to_readabledate( $value_month[ 'total_time_elapsed' ] ),
						'total_time_estimated_readable' => $this->change_minute_time_to_readabledate( $value_month[ 'total_time_estimated' ] ),
						'total_time_deadline_readable'  => $this->change_minute_time_to_readabledate( $value_month[ 'total_time_deadline' ] ),
						'total_time_percent'            => $this->percent_indicator_client( $value_month[ 'total_time_elapsed' ], $value_month[ 'total_time_estimated' ], $value_month[ 'total_time_deadline' ], $key_type )
					);

					$categories[ $key_type ][ $key_cat ][ $key_month ] = array_merge( $value_month, $temp_month);

					foreach( $value_month[ 'task_list' ] as $key_task => $value_task ){
						$temp_task = array(
							'time_elapsed_readable'   => $this->change_minute_time_to_readabledate( $value_task[ 'time_elapsed' ] ),
							'time_estimated_readable' => $this->change_minute_time_to_readabledate( $value_task[ 'time_estimated' ] ),
							'time_deadline_readable'  => $this->change_minute_time_to_readabledate( $value_task[ 'time_deadline' ] ),
							'time_percent'            => $this->percent_indicator_client( $value_task[ 'time_elapsed' ], $value_task[ 'time_estimated' ], $value_task[ 'time_deadline' ], $key_type )
						);

						$categories[ $key_type ][ $key_cat ][ $key_month ][ 'task_list' ][ $key_task ] = array_merge( $value_task, $temp_task);

						$info_task = $info[ $key_type ][ $key_cat ][ 'task_list' ][ $key_task ];
						$temp_task_info = array(
							'time_elapsed_readable'   => $this->change_minute_time_to_readabledate( $info_task[ 'time_elapsed' ] ),
							'time_estimated_readable' => $this->change_minute_time_to_readabledate( $info_task[ 'time_estimated' ] ),
							'time_percent'            => $this->percent_indicator_client( $info_task[ 'time_elapsed' ], $info_task[ 'time_estimated' ], 0, 'recursive' )
						);

						$info[ $key_type ][ $key_cat ][ 'task_list' ][ $key_task ] = array_merge( $info_task, $temp_task_info);
					}
				}

				$temp_info = array(
					'time_elapsed_readable'   => $this->change_minute_time_to_readabledate( $info[ $key_type ][ $key_cat ][ 'info' ][ 'time_elapsed' ] ),
					'time_estimated_readable' => $this->change_minute_time_to_readabledate( $info[ $key_type ][ $key_cat ][ 'info' ][ 'time_estimated' ] ),
					'time_percent'            => $this->percent_indicator_client( $info[ $key_type ][ $key_cat ][ 'info' ][ 'time_elapsed' ], $info[ $key_type ][ $key_cat ][ 'info' ][ 'time_estimated' ], 0, 'recursive' )
				);

				$info[ $key_type ][ $key_cat ][ 'info' ] = array_merge( $info[ $key_type ][ $key_cat ][ 'info' ], $temp_info );

			}
		}

		return array( 'data' => $categories, 'info' => $info );
	}

	public function percent_indicator_client( $elapsed, $estimated, $deadline, $type ){

		if( ( intval( $elapsed ) <= 0 && intval( $deadline ) <= 0 ) || intval( $estimated ) <= 0 ){
			return 0;
		}

		if( $type == 'deadline' ){
			if( $deadline > 0 && $estimated > 0){
				return intval( $deadline / $estimated * 100 );
			}
		}else if( $type == 'recursive' ){
			if( $elapsed > 0 && $estimated > 0){
				return intval( $elapsed / $estimated * 100 );
			}
		}else{

		}
		return 0;
	}

	public function recompile_task( $id ){
		$recompiled_elements = array(
			'task' => array(),
			'points' => array(),
		);

		$task = Task_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);

		// Recompiles le nombre de point complété et incomplété.
		// Recompiles le temps.
		$elapsed_task      = 0;
		$elapsed_point     = 0;
		$count_uncompleted = 0;
		$count_completed   = 0;

		$points = Point_Class::g()->get(
			array(
				'post_id' => $task->data['id'],
				'type'    => Point_Class::g()->get_type(),
				'status'  => 1,
			)
		);

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				$elapsed_point = 0;
				$comments      = Task_Comment_Class::g()->get(
					array(
						'post_id' => $task->data['id'],
						'parent'  => $point->data['id'],
						'type'    => Task_Comment_Class::g()->get_type(),
						'status'  => 1,
					)
				);

				if ( ! empty( $comments ) ) {
					foreach ( $comments as $comment ) {
						$elapsed_point += $comment->data['time_info']['elapsed'];
					}
				}

				if ( $point->data['completed'] ) {
					$count_completed++;
				} else {
					$count_uncompleted++;
				}

				$point->data['time_info']['elapsed'] = (int) $elapsed_point;
				$elapsed_task                       += (int) $elapsed_point;
				Point_Class::g()->update( $point->data, true );

				$recompiled_elements['points'][] = array(
					'id' => $point->data['id'],
					'time' => $point->data['time_info']['elapsed'],
				);
			}
		}
		$task->data['time_info']['elapsed']     = $elapsed_task;
		$task->data['count_completed_points']   = $count_completed;
		$task->data['count_uncompleted_points'] = $count_uncompleted;

		$task = Task_Class::g()->update( $task->data );

		$recompiled_elements['task'] = array(
			'id'   => $task->data['id'],
			'time' => $task->data['time_info']['elapsed'],
		);

		return $recompiled_elements;
	}

	// 25/04/2019 OPTI PAS OPTI
	public function get_task_with_history_time( $type = "recursive" ){
		global $wpdb;

		$results = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT(TASK.ID) FROM {$wpdb->posts} AS TASK
			INNER JOIN {$wpdb->comments} AS HISTORYTIME ON HISTORYTIME.comment_ID=(SELECT comment_id FROM {$wpdb->comments} WHERE comment_post_ID=TASK.ID AND comment_type='history_time' ORDER BY comment_id DESC LIMIT 0,1 )
			LEFT JOIN {$wpdb->commentmeta} AS HISTORYMETA ON HISTORYMETA.comment_id=HISTORYTIME.comment_ID AND HISTORYMETA.meta_key='_tm_custom' AND HISTORYMETA.meta_value=%s
			WHERE TASK.post_type='wpeo-task' AND TASK.post_status IN('publish', 'inherit')", $type ) );

		return $results;
	}

	public function get_task_per_page_for_this_user( $id ){
		$data_plan = get_user_meta( $id, '_tm_task_per_page', true );
		$default_value = \eoxia\Config_Util::$init['task-manager']->task->posts_per_page_client;

		if( ! empty( $data_plan ) && $data_plan[ 'value' ] >= 2 ){
			$default_value = $data_plan[ 'value' ];
		}

		return $default_value;
	}

	function time_elapsed( $secs ){
		$time = array(
			' année'   => $secs / 31556926 % 12,
			' semaine' => $secs / 604800 % 52,
			' jour'    => $secs / 86400 % 7,
			' heure'   => $secs / 3600 % 24,
			' minute'  => $secs / 60 % 60,
			' seconde' => $secs % 60,
		);

		foreach( $time as $key => $value ){
			if( $value > 1 ) $ret[] = $value . $key . 's';
			if( $value == 1 ) $ret[] = $value . $key;
		}
		array_splice( $ret, count( $ret )-1, 0, '' );
		return join( ' ', $ret );
	}

	public function get_task_last_update( $task_id ) {

		$now = strtotime( 'now' );
		$task = Task_Class::g()->get( array( 'id' => $task_id ), true );

		/*if ( action ) {
			$last_update = $task->data['last_update']['rendered']['mysql'];
		}else {*/
			$last_update = $task->data['date']['rendered']['mysql'];
		//}
		$time = strtotime( 'now + 1 hour' ) - strtotime( $last_update );
		if ( $time > 0 ) {
			$last_update = $this->time_elapsed( $time );
		}
		return $last_update;
	}
}

Task_Class::g();
