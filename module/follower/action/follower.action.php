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
		add_action( 'wp_ajax_follower_affectation', array( $this, 'ajax_follower_affectation' ) );
	}

	/**
	 * Récupère les followers existants dans la base et les retournent pour affichage
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_load_followers() {
		check_ajax_referer( 'load_followers' );

		$users = User_Class::g()->get();
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		ob_start();
		View_Util::exec( 'follower', 'backend/list-follower-edit', array(
			'users' => $users,
			'task' => Task_Class::g()->get( array(
				'id' => $task_id,
			), true ),
		) );

		wp_send_json_success( array(
			'module' => 'follower',
			'callback_success' => 'loadedFollowerSuccess',
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

		wp_send_json_success();
	}
}

new Follower_Action();
