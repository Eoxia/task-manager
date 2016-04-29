<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'frontend_controller_01' ) ) {
	class frontend_controller_01 {
		public function __construct() {
      add_shortcode( 'task', array( $this, 'callback_shortcode' ) );

			add_filter( 'task_list_title', array( $this, 'callback_task_list_title' ), 10, 2 );
		}

    public function callback_shortcode( $args ) {
      if( empty( $args['id'] ) )
        return null;

      $task_id = (int) $args['id'];

      if( 0 == $task_id )
        return null;

      global $task_controller;

      /** On récupère la tâche */

      ob_start();
      add_filter( 'task_footer', function( $string, $task ) { return ''; }, 12, 2 );
      add_filter( 'task_header_button', function( $string, $task ) { return ''; }, 12, 2 );
      add_filter( 'task_header_disabled', function( $string ) { return 'readonly'; }, 12, 2 );
			add_filter( 'point_action_before', function( $string, $point ) { return ''; }, 10, 2 );
			add_filter( 'point_action_after', function( $string, $point ) { return ''; }, 10, 2 );
			add_filter( 'point_list_add', function( $string, $object_id ) { return ''; }, 10, 2 );
			add_filter( 'point_disabled', function( $string ) { return 'readonly'; } );
			$list_task = $task_controller->index( array( 'post__in' => array( $task_id ) ) );
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
      return ob_get_clean();
    }

		public function callback_task_list_title( $string, $name ) {
			if ( !empty( $name ) )
				$string .= '<h3 class="list-task-title">' . $name . '</h3>';

			return $string;
		}
	}

	global $frontend_controller;
	$frontend_controller = new frontend_controller_01();
}
?>
