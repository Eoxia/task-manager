<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'task_wpshop_controller_01' ) ) {
	class task_wpshop_controller_01 {
		public function __construct() {
      add_filter( 'wps_my_account_extra_part_menu', array( $this, 'callback_my_account_menu' ) );
      add_filter( 'wps_my_account_extra_panel_content', array( $this, 'callback_my_account_content' ), 10, 3 );

			add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 12, 2 );
		}

    public function callback_my_account_menu() {
      require_once( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'frontend', 'menu' ) );
    }

    public function callback_my_account_content( $output, $dashboard_part, $backend = false ) {
			global $task_controller;

      if( 'my-task' === $dashboard_part ) {
				$user_id = !empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : get_current_user_id();
				ob_start();
				$list_task = $this->get_all_task_customer( $user_id );
				if ( empty( $user_id ) ) {
					add_filter( 'task_footer', function( $string, $task ) { return ''; }, 12, 2 );
		      add_filter( 'task_header_button', function( $string, $task ) { return ''; }, 12, 2 );
		      add_filter( 'task_header_disabled', function( $string ) { return 'disabled'; }, 12, 2 );
					add_filter( 'point_action_before', function( $string, $point ) { return ''; }, 10, 2 );
					add_filter( 'point_action_after', function( $string, $point ) { return ''; }, 10, 2 );
					add_filter( 'point_list_add', function( $string, $object_id ) { return ''; }, 10, 2 );
					add_filter( 'point_disabled', function( $string ) { return 'disabled'; } );
				}
				$class = ' task-wpshop ';

				if( !$backend ) {
					require( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'frontend', 'content' ) );
				}

				require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
        $output = ob_get_clean();

      }
      return $output;
    }

		public function callback_task_manager_dashboard_search( $string ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'backend', 'search' ) );
			$string .= ob_get_clean();

			return $string;
		}

		public function get_all_task_customer( $user_id ) {
			$customer_id = wps_customer_ctr::get_customer_id_by_author_id( $user_id );

			$list_post_type = get_post_types();
			global $task_controller;
			$list_task = get_posts( array( 'post_type' => $list_post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'author' => $user_id ) );
			$list_task = array_merge( $list_task, get_posts( array( 'post_type' => $list_post_type, 'post_status' => 'publish', 'posts_per_page' => -1, 'post_parent' => $customer_id ) ) );

			if ( !empty( $list_task ) ) {
				foreach( $list_task as $key => $task ) {
					if( $task->post_type == $task_controller->get_post_type() ) {
						$list_task[$key] = $task_controller->show( $task->ID );
					}
					else {
						$list_task['#' . $task->ID . ' ' . $task->post_title] = $task_controller->index( array( 'post_parent' => $task->ID, 'post_status' => 'publish' ) );
						unset( $list_task[$key] );
					}
				}
			}


			return $list_task;
		}
	}

	global $task_wpshop_controller;
	$task_wpshop_controller = new task_wpshop_controller_01();
}
?>
