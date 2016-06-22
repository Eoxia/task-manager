<?php

if ( !defined( 'ABSPATH' ) ) exit;

class tag_action_01 {

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
		global $tag_controller;

		$object_id = !empty( $_POST['object_id'] ) ? (int) $_POST['object_id'] : 0;
		$list_tag_id = !empty( $_POST['list_tag_id'] ) ? (array) $_POST['list_tag_id'] : array();

		if ( $object_id == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for view all tag', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_view_all_tag_' . $object_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for view all tag: invalid nonce', 'task-manager' ) ) );
		}

		if ( !empty( $list_tag_id ) ) {
		  foreach ( $list_tag_id as $key => $element ) {
				$list_tag_id[$key] = (int) $element;
			}
		}

		ob_start();
		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'display', 'tag' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function ajax_create_tag() {
		global $tag_controller;
		$tag_name = !empty( $_POST['tag_name'] ) ? sanitize_text_field( $_POST['tag_name'] ) : '';
		$response = array();

		if ( empty( $tag_name ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create tag', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_create_tag', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create tag: invalid nonce', 'task-manager' ) ) );
		}

		$term = wp_create_term( $tag_name, $tag_controller->get_taxonomy() );
		$response = $tag_controller->show( $term['term_id'] );

		wp_send_json_success( $response );
	}

}

new tag_action_01();
