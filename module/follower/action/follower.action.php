<?php
/**
 * Fichier de gestion des "actions" pour les followers
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des "actions" pour les followers
 */
class Follower_Action {

	/**
	 * Instanciation des crochets pour les "actions" utilisées par les tags
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_followers', array( $this, 'ajax_load_followers' ) );
		add_action( 'wp_ajax_close_followers_edit_mode', array( $this, 'ajax_close_followers_edit_mode' ) );

		add_action( 'wp_ajax_follower_affectation', array( $this, 'ajax_follower_affectation' ) );
		add_action( 'wp_ajax_follower_unaffectation', array( $this, 'ajax_follower_unaffectation' ) );
	}

	/**
	 * Récupère les followers existants dans la base et les retournent pour affichage
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_followers() {
		check_ajax_referer( 'load_followers' );

		$followers = User_Class::g()->get( array(
			'role' => array(
				'administrator'
			),
		) );
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/main-edit', array(
			'followers' => $followers,
			'task' => $task,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'follower',
			'callback_success' => 'loadedFollowersSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Repasses en mode "vue" des followers
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_close_followers_edit_mode() {
		check_ajax_referer( 'close_followers_edit_mode' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$followers = array();

		if ( ! empty( $task->user_info['affected_id'] ) ) {
			$followers = User_Class::g()->get( array(
				'include' => $task->user_info['affected_id'],
			) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/main', array(
			'followers' => $followers,
			'task' => $task,
		) );
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'follower',
			'callback_success' => 'closedFollowersEditMode',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Affectes un utilisateur à la tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_follower_affectation() {
		check_ajax_referer( 'follower_affectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$task->user_info['affected_id'][] = $user_id;

		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'module' => 'follower',
			'callback_success' => 'affectedFollowerSuccess',
			'nonce' => wp_create_nonce( 'follower_unaffectation' ),
		) );
	}

	/**
	 * Désaffecte un utilisateur d'une tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_follower_unaffectation() {
		check_ajax_referer( 'follower_unaffectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$key = array_search( $user_id, $task->user_info['affected_id'], true );

		if ( -1 < $key ) {
			unset( $task->user_info['affected_id'][ $key ] );
		}

		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'module' => 'follower',
			'callback_success' => 'unaffectedFollowerSuccess',
			'nonce' => wp_create_nonce( 'follower_affectation' ),
		) );
	}
}

new Follower_Action();
