<?php if ( ! defined( 'ABSPATH' ) ) exit;

class transfert_controller_01 {
	public function __construct() {
		$taskmanager_version = get_option( '_taskmanager_version' );

		if( $taskmanager_version < 1310 ) {
			add_action( 'init', array( &$this, 'callback_admin_init' ), 11 );
		}
	}

	public function callback_admin_init() {
		global $task_controller;
		global $tag_controller;

		$term = get_term_by( 'slug', 'archive', $tag_controller->get_taxonomy() );
		if( !empty( $term ) ) {

			$list_task = $task_controller->index( array( 'status' => 'publish' ) );
			if( !empty( $list_task ) ) {
				foreach( $list_task as $task ) {
					if( in_array( $term->term_id, $task->taxonomy[$tag_controller->get_taxonomy()]) ) {
						$task->status = 'archive';
						$task_controller->update( $task );
					}
				}
			}
		}

		update_option( '_taskmanager_version', 1310 );
	}
}

new transfert_controller_01();
