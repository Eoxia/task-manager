<?php

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'taskmanager_action_01' ) ) {
	class taskmanager_action_01 {

		public function __construct() {
      add_action( 'wp_ajax_search', array( $this, 'ajax_search' ) );
		}

    public function ajax_search() {
      global $wpdb;

      $search = like_escape($_REQUEST['term']);

      $post_type = !empty( $_REQUEST['post_type'] ) ? ' AND post_type="' . $_REQUEST['post_type'] . '" ' : '';

      $query = 'SELECT ID, post_title FROM ' . $wpdb->posts . ' as P
          WHERE ID LIKE \'%' . $search . '%\' OR
		  post_title LIKE \'%' . $search . '%\' OR
          post_name LIKE \'%' . $search . '%\' ' . $post_type . '
          ORDER BY post_title ASC LIMIT 0,5';

      $return = array();

      foreach ($wpdb->get_results($query) as $row) {
        $return[] = array(
          'label' => $row->post_title,
          'value' => $row->post_title,
          'id'    => $row->ID,
        );
      }
      wp_die( wp_json_encode( $return ) );
    }
  }

	global $taskmanager_action;
	$taskmanager_action = new taskmanager_action_01();
}
?>
