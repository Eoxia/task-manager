<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Tag_Action {

	public function __construct() {
		ini_set("display_errors", true);
		error_reporting(E_ALL);
		add_action( 'wp_ajax_load_tags', array( $this, 'load_tags' ) );
		add_action( 'wp_ajax_load_archived_task', array( $this, 'load_archived_task' ) );

		add_action( 'wp_ajax_create-tag', array( &$this, 'ajax_create_tag' ) );
	}

	public function load_tags() {
		check_ajax_referer( 'load_tags' );

		$list_tag = Tag_Class::g()->get();

		ob_start();
		View_Util::exec( 'tag', 'backend/display-tag', array( 'list_tag' => $list_tag ) );

		wp_send_json_success( array( 'module' => 'tag', 'callback_success' => 'load_tag_success', 'view' => ob_get_clean() ) );
	}

	public function load_archived_task() {
		check_ajax_referer( 'load_archived_task' );

		$list_tag = Tag_Class::g()->get();

		ob_start();
		View_Util::exec( 'tag', 'backend/display-tag', array( 'list_tag' => $list_tag ) );

		wp_send_json_success( array( 'module' => 'tag', 'callback_success' => 'load_archived_task', 'view' => ob_get_clean() ) );
	}

	public function ajax_create_tag() {
		$response = array();

		$term = wp_create_term( $_POST['tag_name'], $tag_controller->get_taxonomy() );
		$response = Tag_Class::g()->show( $term['term_id'] );

		wp_send_json_success( $response );
	}

}

new Tag_Action();
