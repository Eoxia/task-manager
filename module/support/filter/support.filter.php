<?php
/**
 * Fichier de gestion des filtres du support
 *
 * @since 1.0.0
 * @version 1.3.0
 *
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des filtres
 */
class Support_Filter {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_filter( 'wps_my_account_extra_part_menu', array( $this, 'callback_my_account_menu' ) );
		add_filter( 'wps_my_account_extra_panel_content', array( $this, 'callback_my_account_content' ), 10, 2 );
		add_filter( 'wp_redirect', array( $this, 'callback_wp_redirect' ), 10, 2 );
		add_filter( 'tm_activity_filter_input', array( $this, 'callback_tm_activity_filter_input' ), 10, 1 );

	}

	/**
	 * [callback_my_account_menu description]
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function callback_my_account_menu() {
		\eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/menu' );
	}

	/**
	 * [callback_my_account_content description]
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @param string $output         Output content.
	 * @param string $dashboard_part slug du menu.
	 *
	 * @return string
	 */
	public function callback_my_account_content( $output, $dashboard_part ) {
		if ( 'support' === $dashboard_part && isset( $_COOKIE['wps_current_connected_customer'] ) ) {
			$current_customer_account_to_show = $_COOKIE['wps_current_connected_customer'];

			$tasks_id = array();

			$total_time_elapsed           = 0;
			$total_time_estimated         = 0;
			$last_modification_date       = '';
			$last_modification_date_mysql = '';

			$tasks = \task_manager\Task_Class::g()->get_tasks( array(
				'post_parent' => $current_customer_account_to_show,
			) );

			$args = array(
				'post_parent' => $current_customer_account_to_show,
				'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
				'numberposts' => -1,
				'post_status' => 'any',
			);

			$children = get_posts( $args );

			if ( ! empty( $children ) ) {
				foreach ( $children as $child ) {
					$tasks = array_merge( $tasks, \task_manager\Task_Class::g()->get_tasks( array(
						'post_parent' => $child->ID,
					) ) );
				}
			}

			if ( ! empty( $tasks ) ) {
				foreach ( $tasks as $task ) {
					$tasks_id[]            = $task->data['id'];
					$total_time_elapsed   += $task->data['time_info']['elapsed'];
					$total_time_estimated += $task->data['last_history_time']->data['estimated_time'];

					if ( empty( $last_modification_date ) || ( $last_modification_date_mysql < $task->data['date_modified']['raw'] ) ) {
						$last_modification_date_mysql = $task->data['date_modified']['raw'];
						$last_modification_date       = $task->data['date_modified']['rendered']['date_time'];
					}
				}
			}

			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/main', array(
				'last_modification_date' => $last_modification_date,
				'tasks'                  => $tasks,
				'tasks_id'               => implode( ',', $tasks_id ),
				'parent_id'              => $current_customer_account_to_show,
				'customer_id'            => $current_customer_account_to_show,
				'user_id'                => get_current_user_id(),
				'total_time_elapsed'     => $total_time_elapsed,
				'total_time_estimated'   => $total_time_estimated,
			) );
			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 * Gestion de la redirection après l'authentification par le lien 'token'.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  string  $location L'URL.
	 * @param  integer $status  Le status de la requête.
	 * @return string
	 */
	public function callback_wp_redirect( $location, $status ) {
		if ( 'wp-login.php?tokeninvalid=true' === $location ) {
			$location = get_option( 'tl_login_redirect_url' );
		}
		return $location;
	}
	
	public function callback_tm_activity_filter_input( $output ) {
		if ( ! is_admin() ) {
			$output .= '<input type="hidden" name="frontend" value="true" />';
		}
		return $output;
	}
}

new Support_Filter();