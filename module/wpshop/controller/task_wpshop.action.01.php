<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'task_wpshop_action_01' ) ) {
	class task_wpshop_action_01 {
		public function __construct() {
      add_action( 'wp_ajax_search_customer', array( $this, 'ajax_search_customer' ) );
      add_action( 'wp_ajax_load_task_wpshop', array( $this, 'ajax_load_task_wpshop' ) );
			add_action( 'wp_ajax_ask_task', array( $this, 'ajax_ask_task' ) );
		}

    public function ajax_search_customer() {
      global $wpdb;

      $search = like_escape($_REQUEST['term']);

      $query = 'SELECT U.ID as UID,P.ID,P.post_title FROM ' . $wpdb->posts . ' as P
        JOIN ' . $wpdb->users . ' as U ON P.post_author=U.ID
          WHERE (P.post_title LIKE \'%' . $search . '%\' OR
          U.user_login LIKE \'%' . $search . '%\' OR
          U.user_email LIKE \'%' . $search . '%\' OR
          U.display_name LIKE \'%' . $search . '%\' OR
          U.user_url LIKE \'%' . $search . '%\' )
          AND P.post_type = \'wpshop_customers\'
          ORDER BY P.post_title ASC LIMIT 0,5';

      $return = array();

      foreach ($wpdb->get_results($query) as $row) {
        $return[] = array(
          'label' => $row->post_title,
          'value' => $row->post_title,
          'id'    => $row->UID,
        );
      }
      wp_die( wp_json_encode( $return ) );
    }

    public function ajax_load_task_wpshop() {
      global $task_wpshop_controller;
			$_POST['backend'] = true;
      $template = $task_wpshop_controller->callback_my_account_content( '', 'my-task' );

      wp_send_json_success( array( 'template' => $template ) );
    }

		public function ajax_ask_task() {
			global $task_controller;
			global $point_controller;

			$edit = false;

			/** On vérifie si la tâche ask-task-[client_id] existe */
			global $wpdb;

			$query = "SELECT ID FROM {$wpdb->posts} WHERE post_name=%s";
			$list_task = $wpdb->get_results( $wpdb->prepare( $query, array( 'ask-task-' . get_current_user_id() ) ) );

			/** On crée la tâche */
			if ( count( $list_task ) == 0 ) {
				$task = $task_controller->create(
					array(
						'title' => __( 'Ask', 'task-manager' ),
						'slug' => 'ask-task-' . get_current_user_id(),
						'parent_id' => wps_customer_ctr::get_customer_id_by_author_id( get_current_user_id() ),
					)
				);

				$task_id = $task->id;
			}
			else {
				$edit = true;
				$task_id = $list_task[0]->ID;
			}

			$task = $task_controller->show( $task_id );

			$_POST['point']['author_id'] = get_current_user_id();
			$_POST['point']['status'] = '-34070';
			$_POST['point']['date'] = current_time( 'mysql' );
			$_POST['point']['post_id'] = $task_id;

			$point = $point_controller->create( $_POST['point'] );

			$task->option['task_info']['order_point_id'][] = (int) $point->id;
			$task_controller->update( $task );

			ob_start();
			$task_controller->render_task( $task );
			wp_send_json_success( array( 'task_id' => $task_id, 'edit' => $edit, 'template' => ob_get_clean() ) );
		}
	}

	global $task_wpshop_action;
	$task_wpshop_action = new task_wpshop_action_01();
}
?>
