<?php
/**
 * Gestion des outils.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des outils.
 */
class Tools_Class extends \eoxia\Singleton_Util {
	/**
	 * La limite des points a afficher par page
	 *
	 * @var integer
	 */
	public $limit = 1000;

	/**
	 * Constructeur obligatoire pour Singleton_Util.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Affichage
	 *
	 * @return void
	 */
	public function display() {
		$current_page = ! empty( $_GET['current_page'] ) ? (int) $_GET['current_page'] : 1;

		// $args_where = array(
		// 'status' => 1,
		// 'parent' => 0,
		// 'number' => $this->limit,
		// 'offset' => ( $current_page - 1 ) * $this->limit,
		// );
		//
		// $points = Point_Class::g()->get( $args_where );
		//
		// unset( $args_where['offset'] );
		// unset( $args_where['number'] );
		// $args_where['fields'] = array( 'ID' );
		// $args_where['count'] = true;
		//
		// $count_point = get_comments( $args_where );
		// $number_page = ceil( $count_point / $this->limit );
		//
		// if ( ! empty( $points ) ) {
		// foreach ( $points as &$point ) {
		// $point->post_parent = null;
		//
		// if ( ! empty( $point->post_id ) ) {
		// $point->post_parent = Task_Class::g()->get( array(
		// 'id' => $point->post_id,
		// ), true );
		// }
		// }
		// }
		$tasks = Task_Class::g()->get(
			array(
				'posts_per_page' => $this->limit,
			)
		);

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as &$task ) {
				if ( ! empty( $task->task_info['order_point_id'] ) ) {
					$task->points = Point_Class::g()->get(
						array(
							'post__not_in' => array( $task->id ),
							'orderby'      => 'comment__in',
							'comment__in'  => $task->task_info['order_point_id'],
							'status'       => 1,
						)
					);

					if ( ! empty( $task->points ) ) {
						foreach ( $task->points as $key => &$point ) {
							if ( 0 == $point->id ) {
								array_splice( $task->points, $key, $key + 1 );
							} else {
								$point->comments = Task_Comment_Class::g()->get(
									array(
										'parent' => $point->id,
										'status' => 1,
									)
								);
							}
						}
					}
				}
			}
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'tools',
			'backend/main',
			array(
				'tasks'        => $tasks,
				'number_page'  => 0,
				'current_page' => 0,
				'count_point'  => 0,
			)
		);
	}
}

Tools_Class::g();
