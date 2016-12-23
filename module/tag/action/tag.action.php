<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Tag_Action {

	public function __construct() {
		add_action( 'wp_ajax_wpeo-view-all-tag', array( &$this, 'ajax_view_all_tag' ) );
		add_action( 'wp_ajax_create-tag', array( &$this, 'ajax_create_tag' ) );
	}

	/**
	 * Charge tous les tags et les affiches
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['object_id'] L'id de la tâche
	 * @return JSON Object { 'success': true|false, 'data': { template: '' } }
	 */
	public function ajax_view_all_tag() {
		$object_id = $_POST['object_id'];
		if ( ! is_int( $object_id ) ) {
			$object_id = intval( $object_id );
		}
		check_ajax_referer( 'wpeo_nonce_load_tag_' . $object_id );

		$list_tag = Tag_Class::g()->get();

		ob_start();
		View_Util::exec( 'tag', 'backend/display-tag', array( 'list_tag' => $list_tag ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function ajax_create_tag() {
		$response = array();

		$term = wp_create_term( $_POST['tag_name'], $tag_controller->get_taxonomy() );
		$response = Tag_Class::g()->show( $term['term_id'] );

		wp_send_json_success( $response );
	}

}

new Tag_Action();
