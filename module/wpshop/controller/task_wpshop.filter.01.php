<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'task_wpshop_filter_01' ) ) {
	class task_wpshop_filter_01 extends post_ctr_01 {

		public function __construct() {
			parent::__construct();

			add_filter( 'task_manager_dashboard_content', array( $this, 'callback_dashboard_content' ), 11, 2 );
		}

		public function callback_dashboard_content( $string, $post_id ) {
			if ( WPSHOP_NEWTYPE_IDENTIFIER_CUSTOMERS === get_post_type( $post_id ) ) {
				global $task_controller, $wpdb;
				$customer_orders = $wpdb->get_var( $wpdb->prepare(
					"SELECT GROUP_CONCAT( ID )
					FROM {$wpdb->posts}
					WHERE post_type = %s
						AND post_author = (SELECT post_author FROM {$wpdb->posts} WHERE post_type = %s AND ID = %d )",
					WPSHOP_NEWTYPE_IDENTIFIER_ORDER, WPSHOP_NEWTYPE_IDENTIFIER_CUSTOMERS, $post_id
				) );

				if ( !empty( $customer_orders ) ) {
					$list_task = $task_controller->index( array( 'post_parent__in' => explode( ',', $customer_orders ) ) );
					ob_start();
					require( wpeo_template_01::get_template_part( WPEO_TASK_WPSHOP_DIR, WPEO_TASK_WPSHOP_TEMPLATES_MAIN_DIR, 'backend', 'children', 'tasks' ) );
					$string .= ob_get_clean();
				}
			}

			return $string;
		}
	}

	new task_wpshop_filter_01();
}
