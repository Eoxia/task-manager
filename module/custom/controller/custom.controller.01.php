<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'custom_controller_01' ) ) {
	class custom_controller_01 {
		public function __construct() {
      add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );
		}

    public function callback_add_meta_boxes( $post_type, $post ) {
      add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ), array( $this, 'callback_render_metabox' ), $post_type, 'normal', 'default' );
    }

    public function callback_render_metabox( $post ) {
      require_once( wpeo_template_01::get_template_part( WPEO_CUSTOM_DIR, WPEO_CUSTOM_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
    }
	}

	global $custom_controller;
	$custom_controller = new custom_controller_01();
}
?>
