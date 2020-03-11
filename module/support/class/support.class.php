<?php
/**
 * Classe gérant le support.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant le support.
 */
class Support_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	protected function construct() {}

	/**
	 * Renvoies le nombre de demande
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return integer Le nombre de demande
	 */
	public function get_number_ask() {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );

		$count = 0;

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points ) {
				if ( ! empty( $points ) ) {
					foreach ( $points as $point_id => $id ) {
						$count += count( $id );
					}
				}
			}
		}

		return $count;
	}

	public function display_projects( $recursive ) {

		global $wp;
		$current_url = home_url( $wp->request ) . '/?account_dashboard_part=support';

		$current_customer_account_to_show = $_COOKIE['wps_current_connected_customer'];

		$projects = Task_Class::g()->get( array(
			'post_parent' => $current_customer_account_to_show
		) );

		$final_array = array();

		if ( $recursive ) {
			if ( ! empty( $projects ) ) {
				foreach ( $projects as $project ) {
					if ( $project->data['last_history_time']->data['custom'] == 'recursive' ) {
						$project = $this->get_data( $project );

						$final_array[] = $project;
					}
				}
			}
		} else {
			if ( ! empty( $projects ) ) {
				foreach ( $projects as $project ) {
					if ( $project->data['last_history_time']->data['custom'] != 'recursive' ) {
						$project = $this->get_data( $project );

						$final_array[] = $project;
					}
				}
			}
		}

		\eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/projects', array(
			'projects'    => $final_array,
			'current_url' => $current_url,
		) );
	}

	public function get_data( $project ) {
		$project->uncompleted_tasks = $points = Point_Class::g()->get(
			array(
				'post_id'    => $project->data['id'],
				'type'       => Point_Class::g()->get_type(),
				'meta_key'   => '_tm_order',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
				'meta_query' => array(
					array(
						'key'     => '_tm_completed',
						'value'   => false,
						'compare' => '=',
					),
				),
			)
		);
		$project->completed_tasks = $points = Point_Class::g()->get(
			array(
				'post_id'    => $project->data['id'],
				'type'       => Point_Class::g()->get_type(),
				'meta_key'   => '_tm_order',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
				'meta_query' => array(
					array(
						'key'     => '_tm_completed',
						'value'   => true,
						'compare' => '=',
					),
				),
			)
		);

		$project->tags = array();
		if ( ! empty( $project->data['taxonomy'][ Tag_Class::g()->get_type() ] ) ) {
			$project->tags = Tag_Class::g()->get(
				array(
					'include' => $project->data['taxonomy'][ Tag_Class::g()->get_type() ],
				)
			);
		}

		$project->readable_tag = '';

		if ( ! empty( $project->tags ) ) {
			foreach ( $project->tags as $tags ) {
				$project->readable_tag .= $tags->data['name'] . ', ';
			}
		}

		$project->readable_tag = substr( $project->readable_tag, 0, strlen( $project->readable_tag ) - 2 );

//		$pages_ids = get_option( 'wps_page_ids', \wpshop\Pages::g()->default_options );
//		$project->account_page = $pages_ids['my_account_id'];

		return $project;
	}
}

new Support_Class();
