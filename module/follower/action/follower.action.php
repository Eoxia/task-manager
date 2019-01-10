<?php
/**
 * Fichier de gestion des "actions" pour les followers
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
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

		add_action( 'wp_ajax_deleteplan', array( $this, 'callback_deleteplan' ) );

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
				'administrator',
			),
		) );
		$task_id   = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		// Récupères les followers supplémentaires qui ne sont plus "administrateur". Afin de pouvoir les afficher dans l'interface.
		$followers_only_id         = array();
		$followers_no_role_only_id = array();
		if ( ! empty( $followers ) ) {
			foreach ( $followers as $follower ) {
				$followers_only_id[] = $follower->data['id'];
			}
		}

		if ( ! empty( $task->data['user_info']['affected_id'] ) ) {
			foreach ( $task->data['user_info']['affected_id'] as $key => $affected_id ) {
				if ( ! in_array( $affected_id, $followers_only_id, true ) ) {
					$followers_no_role_only_id[] = $affected_id;
					break;
				}
			}
		}

		$followers_no_role = array();

		if ( ! empty( $followers_no_role_only_id ) ) {
			$followers_no_role = Follower_Class::g()->get( array(
				'include' => $followers_no_role_only_id,
			) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/main-edit', array(
			'followers'         => $followers,
			'followers_no_role' => $followers_no_role,
			'task'              => $task,
		) );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'follower',
			'callback_success' => 'loadedFollowersSuccess',
			'view'             => ob_get_clean(),
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

		if ( ! empty( $task->data['user_info']['affected_id'] ) ) {
			$followers = Follower_Class::g()->get( array(
				'include' => $task->data['user_info']['affected_id'],
			) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/main', array(
			'followers' => $followers,
			'task'      => $task,
		) );
		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'follower',
			'callback_success' => 'closedFollowersEditMode',
			'view'             => ob_get_clean(),
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

		$task->data['user_info']['affected_id'][] = $user_id;

		Task_Class::g()->update( $task->data );

		wp_send_json_success( array(
			'module'           => 'follower',
			'callback_success' => 'affectedFollowerSuccess',
			'nonce'            => wp_create_nonce( 'follower_unaffectation' ),
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

		$key = array_search( $user_id, $task->data['user_info']['affected_id'], true );

		if ( -1 < $key ) {
			array_splice( $task->data['user_info']['affected_id'], $key, 1 );
		}

		Task_Class::g()->update( $task->data );

		wp_send_json_success( array(
			'module'           => 'follower',
			'callback_success' => 'unaffectedFollowerSuccess',
			'nonce'            => wp_create_nonce( 'follower_affectation' ),
		) );
	}

	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @param  WP_User $user L'objet contenant la définition complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user  ) {
		$user = Follower_Class::g()->get( array(
			'id' => $user->ID,
		), true );

		$data_planning = array();
		$datebefore    = '';

		$data_planning_array = get_user_meta( $user->data[ 'id' ], '_tm_planning_users', true );
		if( ! empty( $data_planning_array )  ){

			$data_planning = $data_planning_array[ 0 ];

			foreach ($data_planning_array as $key => $value) {
				if( $datebefore != '' ){
					$data_planning_array[ $key ][ 'lastdate' ] = $datebefore;
				}
				$datebefore = $value[ 'date_en' ];
			}

			//$data_planning_array[0] = array_reverse( $data_planning_array[0] ); // pour afficher du plus récent au plus ancien
		}

		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/user-profile', array(
			'user' => $user
		) );



		$archive_user_array = Follower_Class::g()->get_User_Archive( $user['data']['id'] );

		$current_time    = current_time( 'd/m/Y' );
		$current_time_en = current_time( 'Y-m-d' );



		if( empty( $data_planning ) ){
			$data_planning['minutary_duration'] = array(
				'Monday'    => '0',
				'Tuesday'   => '0',
				'Wednesday' => '0',
				'Thursday'  => '0',
				'Friday'    => '0',
				'Saturday'  => '0',
				'Sunday'    => '0'
			);

			$data_planning['date'] = '';
		}

		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/user-profile-planning', array(
			'data'          => $data_planning['minutary_duration'],
			'last_update'   => $data_planning['date'],
			'data_planning' => $data_planning_array,
			'time'          => $current_time,
			'time_en'       => $current_time_en,
			'id'						=> $user['data']['id'],
			'list_archive'  => $archive_user_array
		) );
	}

	/**
	 * Enregistre les informations spécifiques de Note de Frais
	 *
	 * @param  integer $user_id L'identifiant de l'utilisateur pour qui il faut sauvegarder les informations.
	 */
	public function callback_user_profile_edit( $user_id ) {

		$user = Follower_Class::g()->get( array(
			'id' => $user_id,
		), true );

		check_admin_referer( 'update-user_' . $user_id );
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$user                          = array( 'id' => $user_id );
		$user['_tm_auto_elapsed_time'] = isset( $_POST['_tm_auto_elapsed_time'] ) && boolval( $_POST['_tm_auto_elapsed_time'] ) ? true : false;
		$user['_tm_advanced_display']  = isset( $_POST['_tm_advanced_display'] ) && boolval( $_POST['_tm_advanced_display'] ) ? true : false;
		$user['_tm_quick_point']       = isset( $_POST['_tm_quick_point'] ) && boolval( $_POST['_tm_quick_point'] ) ? true : false;
		$user['_tm_display_indicator'] = isset( $_POST['_tm_display_indicator'] ) && boolval( $_POST['_tm_display_indicator'] ) ? true : false;

		$planning = array();
		$planning[ 'mon' ] = ! empty( $_POST['_tm_planning_monday'] ) ? (int) $_POST['_tm_planning_monday'] : 0;
		$planning[ 'tue' ] = ! empty( $_POST['_tm_planning_tuesday'] ) ? (int) $_POST['_tm_planning_tuesday'] : 0;
		$planning[ 'wed' ] = ! empty( $_POST['_tm_planning_wednesday'] ) ? (int) $_POST['_tm_planning_wednesday'] : 0;
		$planning[ 'thu' ] = ! empty( $_POST['_tm_planning_thursday'] ) ? (int) $_POST['_tm_planning_thursday'] : 0;
		$planning[ 'fri' ] = ! empty( $_POST['_tm_planning_friday'] ) ? (int) $_POST['_tm_planning_friday'] : 0;
		$planning[ 'sat' ] = ! empty( $_POST['_tm_planning_saturday'] ) ? (int) $_POST['_tm_planning_saturday'] : 0;
		$planning[ 'sun' ] = ! empty( $_POST['_tm_planning_sunday'] ) ? (int) $_POST['_tm_planning_sunday'] : 0;

		$date = ! empty( $_POST['_tm_planning_date'] ) ? $_POST['_tm_planning_date'] : current_time( 'Y-m-d' );

		if( strtotime( $date ) > strtotime( "now" ) ){
			$date = current_time( 'Y-m-d' );
		}

		$planning_update = Follower_Class::g()->update_planning( $user, $planning, $date );
		$user_update = Follower_Class::g()->update( $user );
	}

	public function callback_deleteplan(){
		check_ajax_referer( 'deleteplan' );

		$posarray = ! empty( $_POST['posarray'] ) ? (int) $_POST['posarray'] : -1;
		$id       = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : -1;

		if( $posarray == -1 || $id == -1 ){
			wp_send_json_error();
			return;
		}else{
			$posarray -= 1;
		}

		$data_planning_old = get_user_meta( $id, '_tm_planning_users', true );
		$data_planning = $data_planning_old;

		$planning_lignedelete = $data_planning[ $posarray ];

		array_splice( $data_planning, $posarray, 1 );

		$planning_lignedelete[ 'day_delete_en' ] = current_time( 'Y-m-d' );
		$planning_lignedelete[ 'day_delete' ] = current_time( 'd/m/Y' );


		update_user_meta( $id, '_tm_planning_users', $data_planning );

		$planning_archive = [];

		if( get_user_meta( $id, '_tm_planning_archives' ) != null && get_user_meta( $id, '_tm_planning_archives' ) != '' ){
			$planning_archive = get_user_meta( $id, '_tm_planning_archives', true );
		}

		array_push( $planning_archive, $planning_lignedelete);

		update_user_meta( $id, '_tm_planning_archives', $planning_archive );

		Follower_Class::g()->deleteAlldbPlanning( $id, $data_planning_old );
		Follower_Class::g()->createAlldbPlanning( $id, $data_planning );

		$archive_user_array = Follower_Class::g()->get_User_Archive( $id );

		ob_start();

		$current_time    = current_time( 'd/m/Y' );
		$current_time_en = current_time( 'Y-m-d' );

		\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/user-profile-planning', array(
			'data'          => $data_planning[0]['minutary_duration'],
			'last_update'   => $data_planning[0]['date'],
			'data_planning' => $data_planning,
			'time'          => $current_time,
			'time_en'       => $current_time_en,
			'id'						=> $id,
			'list_archive'  => $archive_user_array
		) );

		$ob_get_clean = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'follower',
			'callback_success' => 'reloadPlanningUser',
			'view'             => $ob_get_clean
		) );
	}
}

new Follower_Action();
