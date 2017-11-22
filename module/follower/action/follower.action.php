<?php
/**
 * Fichier de gestion des "actions" pour les followers
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0
 * @version 1.5.0
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

		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'personal_options_update', array( $this, 'callback_user_profile_edit' ) );
		add_action( 'edit_user_profile_update', array( $this, 'callback_user_profile_edit' ) );
	}

	/**
	 * Récupère les followers existants dans la base et les retournent pour affichage
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function ajax_load_followers() {
		check_ajax_referer( 'load_followers' );

		$followers = Follower_Class::g()->get( array(
			'role' => array(
				'administrator'
			),
		) );
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
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
			'id' => $task_id,
		), true );

		$followers = array();

		if ( ! empty( $task->user_info['affected_id'] ) ) {
			$followers = Follower_Class::g()->get( array(
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
			'id' => $task_id,
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
			'id' => $task_id,
		), true );

		$key = array_search( $user_id, $task->user_info['affected_id'], true );

		if ( -1 < $key ) {
			array_splice( $task->user_info['affected_id'], $key, 1 );
		}

		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'module' => 'follower',
			'callback_success' => 'unaffectedFollowerSuccess',
			'nonce' => wp_create_nonce( 'follower_affectation' ),
		) );
	}

	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @param  WP_User $user L'objet contenant la définition complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user  ) {
		$user = Follower_Class::g()->get( array(
			'include' => array( $user->ID ),
		), true );

		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/user-profile', array(
			'user' => $user,
		) );
	}

	/**
	 * Enregistre les informations spécifiques de Note de Frais
	 *
	 * @param  integer $user_id L'identifiant de l'utilisateur pour qui il faut sauvegarder les informations.
	 */
	public function callback_user_profile_edit( $user_id ) {
		check_admin_referer( 'update-user_' . $user_id );
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$user = array( 'id' => $user_id );
		$user['_tm_auto_elapsed_time'] = ! empty( $_POST ) && ! empty( $_POST['_tm_auto_elapsed_time'] ) ? sanitize_text_field( $_POST['_tm_auto_elapsed_time'] ) : '';

		$user_update = Follower_Class::g()->update( $user );
	}

}

new Follower_Action();
