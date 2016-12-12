<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'task_wpshop_controller_01' ) ) {
	class task_wpshop_controller_01 {
		public function __construct() {
			add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 11, 2 );
    		add_filter( 'wps_my_account_extra_part_menu', array( $this, 'callback_my_account_menu' ) );
    		add_filter( 'wps_my_account_extra_panel_content', array( $this, 'callback_my_account_content' ), 10, 2 );
			add_filter( 'ticket_query_shortcode', array( $this, 'callback_ticket_query_shortcode' ), 11, 1 );
		}

		public function callback_task_manager_dashboard_search( $string ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'backend', 'search' ) );
			return $string . ob_get_clean();
		}

	    public function callback_my_account_menu() {
			if ( class_exists( 'ticket_controller_01' ) && taskmanager\util\wpeo_util::is_plugin_active( 'task-manager-ticket/task-manager-ticket.php' ) ) {
				require_once( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'frontend', 'menu', 'ticket' ) );
			} else {
				require_once( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'frontend', 'menu' ) );
			}
	    }

		public function callback_my_account_content( $output, $dashboard_part ) {
			if ( class_exists( 'ticket_controller_01' ) && taskmanager\util\wpeo_util::is_plugin_active( 'task-manager-ticket/task-manager-ticket.php' ) ) {
				$output = $this->my_account_content_ticket( $output, $dashboard_part );
			} else {
				$output = $this->my_account_content( $output, $dashboard_part );
			}
			return $output;
		}

		public function callback_ticket_query_shortcode( $query ) {
			return $this->get_query_all_task_customer( get_current_user_id() );
		}

		public function get_query_all_task_customer( $user_id ) {
			$order_query = new WP_Query( array(
					'post_type'			=> WPSHOP_NEWTYPE_IDENTIFIER_ORDER,
					'post_status' 		=> 'publish',
					'posts_per_page'	=> -1,
					'meta_query' 		=> array(
						'relation' 			=> 'AND',
						array(
							'key' 			=> '_order_postmeta',
							'value' 		=> serialize( 'customer_id' ) . serialize( (int) $user_id ),
							'compare' 		=> 'LIKE',
						),
					),
			) );
			$list_items = array();
			foreach( array_merge( $order_query->posts, array( get_post( wps_customer_ctr::get_customer_id_by_author_id( $user_id ) ) ) ) as $item ) {
				$list_items[] = $item->ID;
			}
			$query = array( 'post_parent__in' => $list_items, 'post_status' => 'publish' );
			return $query;
		}

		public function get_all_task_customer( $user_id ) {
			global $task_controller;
			return $task_controller->index( $this->get_query_all_task_customer( $user_id ) );
		}

	    public function my_account_content_ticket( $output, $dashboard_part ) {
			if( 'my-task' === $dashboard_part ) {
				$output = do_shortcode( '[ticket]' );
			} elseif( 'my-task-comments' === $dashboard_part ) {
				$task_id = (int) $_GET['task_id'];
				$point_id = (int) $_GET['point_id'];
				$output = do_shortcode( '[ticket_comment task_id="' . $task_id . '" point_id="' . $point_id . '" ]' );
			}
			return $output;
	    }

		public function my_account_content( $output, $dashboard_part ) {
			if( $dashboard_part == 'my-task' ) {
				global $task_controller;

				$backend = !empty( $_POST['backend'] ) ? true : false;

				ob_start();
				$list_task = $this->get_all_task_customer( !empty( $_POST['user_id'] ) ? $_POST['user_id'] : get_current_user_id() );
				if ( empty( $_POST['user_id'] ) ) {
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
	}

	global $task_wpshop_controller;
	$task_wpshop_controller = new task_wpshop_controller_01();
}
?>
