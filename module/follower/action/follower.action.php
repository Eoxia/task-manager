<?php
/**
 * Fichier de gestion des "actions" pour les followers
 *
 * @since   1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \eoxia\Custom_Menu_Handler as CMH;

/**
 * Classe de gestion des "actions" pour les followers
 */
class Follower_Action {


	/**
	 * Instanciation des crochets pour les "actions" utilisées par les tags
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 40 );

		add_action( 'wp_ajax_load_followers', array( $this, 'ajax_load_followers' ) );
		add_action( 'wp_ajax_close_followers_edit_mode', array( $this, 'ajax_close_followers_edit_mode' ) );

		add_action( 'wp_ajax_follower_affectation', array( $this, 'ajax_follower_affectation' ) );
		add_action( 'wp_ajax_follower_unaffectation', array( $this, 'ajax_follower_unaffectation' ) );

		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'personal_options_update', array( $this, 'callback_user_profile_edit' ) );
		add_action( 'edit_user_profile_update', array( $this, 'callback_user_profile_edit' ) );

		add_action( 'wp_ajax_deleteplan', array( $this, 'callback_delete_plan' ) );

		add_action( 'wp_ajax_add_element_to_planning_user', array( $this, 'callback_add_element_to_planning_user' ) );

		add_action( 'wp_ajax_delete_element_from_planning_user', array( $this, 'callback_delete_element_from_planning_user' ) );

		add_action( 'wp_ajax_applicate_same_planning_for_another_days', array( $this, 'callback_applicate_same_planning_for_another_days' ) );

		add_action( 'wp_ajax_display_contract_planning', array( $this, 'callback_display_contract_planning' ) );

		add_action( 'wp_ajax_create_new_contract', array( $this, 'callback_create_new_contract' ) );

		add_action( 'wp_ajax_display_new_contract_view', array( $this, 'callback_display_new_contract_view' ) );

		add_action( 'wp_ajax_delete_this_contract', array( $this, 'callback_delete_this_contract' ) );


		add_action( 'wp_ajax_load_waiting_for', array( $this, 'ajax_load_waiting_for' ) );
		add_action( 'wp_ajax_close_waiting_for_edit_mode', array( $this, 'ajax_close_waiting_for_edit_mode' ) );

		add_action( 'wp_ajax_waiting_for_affectation', array( $this, 'ajax_waiting_for_affectation' ) );
		add_action( 'wp_ajax_waiting_for_unaffectation', array( $this, 'ajax_waiting_for_unaffectation' ) );

		add_action( 'admin_init', array( $this, 'callback_reset_user_preset_columms' ) );

		add_action( 'wp_ajax_save_profil_task_manager', array( $this, 'callback_save_profil_task_manager' ) );

	}

	/**
	 * Ajoutes la page 'Utilisateurs' dans le sous menu de Task Manager.
	 *
	 * @since 3.0.1
	 */
	public function callback_reset_user_preset_columms() {
		$user_preference = get_option( 'tm_users_columns_version', true );

		if ( $user_preference != \eoxia\Config_Util::$init['task-manager']->version ) {
			$users = get_users( array( 'fields' => array( 'ID' ) ) );
			foreach ( $users as $user_id ) {
				update_user_meta( $user_id->ID, \eoxia\Config_Util::$init['task-manager']->follower->user_columns_key, Follower_Class::g()->default_user_columns_def );
			}
		}

		update_option( 'tm_users_columns_version', \eoxia\Config_Util::$init['task-manager']->version );
	}

	/**
	 * Ajoutes la page 'Utilisateurs' dans le sous menu de Task Manager.
	 *
	 * @since 3.0.1
	 */
	public function callback_admin_menu() {
		CMH::register_menu( 'wpeomtm-dashboard', __( 'Users', 'task-manager' ), __( 'Users', 'task-manager' ), 'manage_task_manager', 'users-page', array( Follower_Class::g(), 'display' ), 'fa fa-user' );
	}

	/**
	 * Récupère les followers existants dans la base et les retournent pour affichage
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 */
	public function ajax_load_followers() {
		check_ajax_referer( 'load_followers' );

		$followers = Follower_Class::g()->get(
			array(
				'role' => array(
					'administrator',
				),
			)
		);
		$task_id   = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

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
				if ( ! in_array( $affected_id, $followers_only_id ) ) {
					$followers_no_role_only_id[] = $affected_id;
					break;
				}
			}
		}

		$followers_no_role = array();

		if ( ! empty( $followers_no_role_only_id ) ) {
			$followers_no_role = Follower_Class::g()->get(
				array(
					'include' => $followers_no_role_only_id,
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/main-edit',
			array(
				'followers'         => $followers,
				'followers_no_role' => $followers_no_role,
				'task'              => $task,
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'loadedFollowersSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Repasses en mode "vue" des followers
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_close_followers_edit_mode() {
		check_ajax_referer( 'close_followers_edit_mode' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$followers = array();

		if ( ! empty( $task->data['user_info']['affected_id'] ) ) {
			$followers = Follower_Class::g()->get(
				array(
					'include' => $task->data['user_info']['affected_id'],
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/main',
			array(
				'followers' => $followers,
				'task'      => $task,
			)
		);
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'closedFollowersEditMode',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Affectes un utilisateur à la tâche.
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_follower_affectation() {
		check_ajax_referer( 'follower_affectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['user_info']['affected_id'][] = $user_id;

		Task_Class::g()->update( $task->data );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'affectedFollowerSuccess',
				'nonce'            => wp_create_nonce( 'follower_unaffectation' ),
			)
		);
	}

	/**
	 * Désaffecte un utilisateur d'une tâche.
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_follower_unaffectation() {
		check_ajax_referer( 'follower_unaffectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$key = array_search( $user_id, $task->data['user_info']['affected_id'] );

		if ( -1 < $key ) {
			array_splice( $task->data['user_info']['affected_id'], $key, 1 );
		}

		Task_Class::g()->update( $task->data );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'unaffectedFollowerSuccess',
				'nonce'            => wp_create_nonce( 'follower_affectation' ),
			)
		);
	}

	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @param WP_User $user L'objet contenant la définition complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user ) {
		$user = Follower_Class::g()->get(
			array(
				'id' => $user->ID,
			),
			true
		);

		$data_planning = array();
		$datebefore    = '';

		$data_planning_array = get_user_meta( $user->data['id'], '_tm_planning_users', true );
		if ( ! empty( $data_planning_array ) ) {

			$data_planning = $data_planning_array[0];

			foreach ( $data_planning_array as $key => $value ) {
				if ( '' != $datebefore ) {
					$data_planning_array[ $key ]['lastdate'] = $datebefore;
				}
				$datebefore = $value['date_en'];
			}

			// $data_planning_array[0] = array_reverse( $data_planning_array[0] ); // pour afficher du plus récent au plus ancien
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/user-profile',
			array(
				'user' => $user,
			)
		);

		$contracts = get_user_meta( $user->data['id'], '_tm_planning_users_contract', true );

		$contracts = ! empty( $contracts ) ? Follower_Class::g()->array_sort( $contracts, 'start_date', SORT_DESC ) : array();

		$contracts = Follower_Class::g()->addNumberOfDayBetweenStartAndEnd( $contracts );
		$one_contract_is_valid = Follower_Class::g()->oneContractIsValid( $contracts );

		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/user-profile-planning',
			array(
				'contracts'             => $contracts,
				'one_contract_is_valid' => $one_contract_is_valid,
				'user_id'               => $user->data[ 'id' ]
			)
		);
	}

	/**
	 * Enregistre les informations spécifiques de Note de Frais
	 *
	 * @param integer $user_id L'identifiant de l'utilisateur pour qui il faut sauvegarder les informations.
	 *
	 * @return null
	 *
	 * @since 1.9.0 - BETA
	 */
	public function callback_user_profile_edit( $user_id ) {
		check_admin_referer( 'update-user_' . $user_id );
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$user                          = array( 'id' => $user_id );
		$user['_tm_task_per_page']     = isset( $_POST['_tm_task_per_page'] ) ? (int) $_POST['_tm_task_per_page'] : 0;
		$user['_tm_project_state']     = isset( $_POST['_tm_project_state'] ) && boolval( $_POST['_tm_project_state'] ) ? true : false;
		$user['_tm_auto_elapsed_time'] = isset( $_POST['_tm_auto_elapsed_time'] ) && boolval( $_POST['_tm_auto_elapsed_time'] ) ? true : false;
		$user['_tm_advanced_display']  = isset( $_POST['_tm_advanced_display'] ) && boolval( $_POST['_tm_advanced_display'] ) ? true : false;
		$user['_tm_quick_point']       = isset( $_POST['_tm_quick_point'] ) && boolval( $_POST['_tm_quick_point'] ) ? true : false;
		$user['_tm_display_indicator'] = isset( $_POST['_tm_display_indicator'] ) && boolval( $_POST['_tm_display_indicator'] ) ? true : false;

		update_user_meta( $user_id, '_tm_task_per_page', $user['_tm_task_per_page'] );
		update_user_meta( $user_id, '_tm_project_state', $user['_tm_project_state'] );
		update_user_meta( $user_id, '_tm_auto_elapsed_time', $user['_tm_auto_elapsed_time'] );
		update_user_meta( $user_id, '_tm_advanced_display', $user['_tm_advanced_display'] );
		update_user_meta( $user_id, '_tm_quick_point', $user['_tm_quick_point'] );
		update_user_meta( $user_id, '_tm_display_indicator', $user['_tm_display_indicator'] );
	}

	public function callback_display_contract_planning(){
		check_ajax_referer( 'display_contract_planning' );

		$id = ! empty( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : -1;
		$user_id = ! empty( $_POST[ 'userid' ] ) ? (int) $_POST[ 'userid' ] : -1;
		$edit = true;

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		$contracts = ! empty( $contracts ) ? $contracts : array();

		if( $id < 0 ){
			$number_valid_contract = Follower_Class::g()->numberContractValid( $contracts );
			$contract = Follower_Class::g()->define_schema_new_contract( $number_valid_contract );
			$planning = Follower_Class::g()->define_schema_new_contract_planning();

		}else{
			$key = $id - 1;
			if( isset( $contracts[ $key ] ) ){
				$contract = $contracts[ $key ];
				$planning = $contracts[ $key ][ 'planning' ];
			}else{
				wp_send_json_error( 'Error db' );
			}
		}

		$periods = array( 'morning', 'afternoon' );
		$return_planning = Follower_Class::g()->durationPerDayPlanning( $planning );
		$planning = $return_planning[ 'planning' ];

		$days =  Follower_Class::g()->calculHourPerDayInPlanning( $planning, $return_planning[ 'duration_week' ] );

		$view = "user-add-contract";
		$view_edit = "";
		if( $id > 0 ){
			$view = "user-edit-contract";
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'follower',
				'backend/indicator-table/user-edit-contract-planning',
				array(
					'planning' => $planning,
					'periods'  => $periods,
					'edit'     => $edit,
					'days'     => $days,
					'userid'   => $user_id
				)
			);
			$view_edit = ob_get_clean();
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/' . $view,
			array(
				'contract' => $contract,
				'planning' => $planning,
				'periods'  => $periods,
				'edit'     => $edit,
				'days'     => $days,
				'userid'   => $user_id
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadViewProfileContract',
				'id'               => $id,
				'view'             => ob_get_clean(),
				'view_edit'        => $view_edit
			)
		);

	}

	public function callback_create_new_contract(){
		check_ajax_referer( 'create_new_contract' );
		$title         = ! empty( $_POST[ 'title' ] ) ? sanitize_text_field( $_POST[ 'title' ] ) : '';
		$start_date    = ! empty( $_POST[ 'start_date' ] ) ? strtotime( $_POST[ 'start_date' ] ) : strtotime( '-1 year' );
		$end_date_type = ! empty( $_POST[ 'date_end_type' ] ) ? sanitize_text_field( $_POST[ 'date_end_type' ] ) : 'actual';
		$end_date      = ! empty( $_POST[ 'end_date' ] ) ? strtotime( $_POST[ 'end_date' ] ) : strtotime( 'now' );
		$planning      = ! empty( $_POST[ 'planning' ] ) ? (array) $_POST[ 'planning' ] : array();
		$id            = ! empty( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : -1;
		$user_id        = ! empty( $_POST[ 'userid' ] ) ? (int) $_POST[ 'userid' ] : -1;

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		$return_planning = Follower_Class::g()->durationPerDayPlanning( $planning );
		$planning = $return_planning[ 'planning' ];
		$duration_week = round( $return_planning[ 'duration_week' ] / 60, 2 ); // Minute To hour
		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		$contracts = ! empty( $contracts ) ? $contracts : array();
		$return_request_ajax = array(	'success' => true );

		$key = $id - 1;
		if( isset( $contracts[ $key ] ) ){
			$return_data = Follower_Class::g()->checkIfDateIsValid( $user_id, $end_date_type, $start_date, $end_date, $contracts, $key );
			if( $return_data[ 'success' ] ){
				$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
				$contract = Follower_Class::g()->update_contract_info( $title, $start_date, $end_date_type, $end_date, $duration_week, $contracts[ $key ], $planning );
				$contracts[ $key ] = $contract;
				// Follower_Class::g()->update_contract_planning( $planning, $contract[ 'id' ], $key );
				// $days_duration = Follower_Class::g()->durationPerDayPlanning( $planning );
			}else{
				$return_request_ajax[ 'data' ] = $return_data;
				$return_request_ajax[ 'success' ] = false;
			}
		}else{
			$return_data = Follower_Class::g()->checkIfDateIsValid( $user_id, $end_date_type, $start_date, $end_date, $contracts );
			if( $return_data[ 'success' ] ){
				$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
				$contract = Follower_Class::g()->create_contract_info( $title, $start_date, $end_date_type, $end_date, count( $contracts ) + 1, $duration_week, $planning );
				array_push( $contracts, $contract );
			}else{
				$return_request_ajax[ 'data' ] = $return_data;
				$return_request_ajax[ 'success' ] = false;
			}
		}

		if( $return_request_ajax[ 'success' ] ){
			update_user_meta( $user_id, '_tm_planning_users_contract', $contracts );
			$contracts = ! empty( $contracts ) ? Follower_Class::g()->array_sort( $contracts, 'start_date', SORT_DESC ) : array();
			$contracts = Follower_Class::g()->addNumberOfDayBetweenStartAndEnd( $contracts );
			$one_contract_is_valid = Follower_Class::g()->oneContractIsValid( $contracts );

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'follower',
				'backend/indicator-table/user-profile-planning',
				array(
					'contracts'             => $contracts,
					'one_contract_is_valid' => $one_contract_is_valid,
					'user_id'               => $user_id
				)
			);

			$return_request_ajax[ 'js' ] = 'reloadViewProfilePlanning';
			$return_request_ajax[ 'view' ] = ob_get_clean();
		}else{
			$return_request_ajax[ 'js' ] = 'reloadViewProfilePlanningError';
		}


		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => $return_request_ajax[ 'js' ],
				'info'             => $return_request_ajax
			)
		);
	}

	public function callback_delete_this_contract(){
		check_ajax_referer( 'delete_this_contract' );
		$id = ! empty( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : -1;
		$user_id = ! empty( $_POST[ 'userid' ] ) ? (int) $_POST[ 'userid' ] : -1;

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		if( $id < 0 ){
			wp_send_json_error( 'ID UNDEFINED' );
		}

		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		$contracts[ $id - 1 ][ 'status' ] = 'delete';
		update_user_meta( $user_id, '_tm_planning_users_contract', $contracts );
		$contracts = Follower_Class::g()->addNumberOfDayBetweenStartAndEnd( $contracts );
		$one_contract_is_valid = Follower_Class::g()->oneContractIsValid( $contracts );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/user-profile-planning',
			array(
				'contracts'             => $contracts,
				'one_contract_is_valid' => $one_contract_is_valid,
				'user_id'               => $user_id
			)
		);


		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadViewProfilePlanning',
				'info'             => array( 'view' => ob_get_clean() )
			)
		);
	}


	/**
	 * Récupère les followers existants dans la base et les retournent pour affichage
	 *
	 * @since   1.0.0
	 * @version 1.5.0
	 */
	public function ajax_load_waiting_for() {
		check_ajax_referer( 'waiting_for' );

		$followers = Follower_Class::g()->get(
			array(
				'role' => array(
					'administrator',
				),
			)
		);
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Point_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		// Récupères les followers supplémentaires qui ne sont plus "administrateur". Afin de pouvoir les afficher dans l'interface.
		$followers_only_id         = array();
		$followers_no_role_only_id = array();
		if ( ! empty( $followers ) ) {
			foreach ( $followers as $follower ) {
				$followers_only_id[] = $follower->data['id'];
			}
		}

		if ( ! empty( $task->data['waiting_for'] ) ) {
			foreach ( $task->data['waiting_for'] as $key => $affected_id ) {
				if ( ! in_array( $affected_id, $followers_only_id ) ) {
					$followers_no_role_only_id[] = $affected_id;
					break;
				}
			}
		}

		$followers_no_role = array();

		if ( ! empty( $followers_no_role_only_id ) ) {
			$followers_no_role = Follower_Class::g()->get(
				array(
					'include' => $followers_no_role_only_id,
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/waiting/main-edit',
			array(
				'followers'         => $followers,
				'followers_no_role' => $followers_no_role,
				'task'              => $task,
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'loadedFollowersSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Repasses en mode "vue" des followers
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_close_waiting_for_edit_mode() {
		check_ajax_referer( 'close_waiting_for_edit_mode' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Point_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$followers = array();

		if ( ! empty( $task->data['waiting_for'] ) ) {
			$followers = Follower_Class::g()->get(
				array(
					'include' => $task->data['waiting_for'],
				)
			);
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/waiting/main',
			array(
				'followers' => $followers,
				'task'      => $task,
			)
		);
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'closedFollowersEditMode',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Affectes un utilisateur à la tâche.
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_waiting_for_affectation() {
		check_ajax_referer( 'waiting_for_affectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Point_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['waiting_for'][] = $user_id;

		Point_Class::g()->update( $task->data );

		// Add notification hihi.
		Notify_Class::g()->add_notification( $user_id, get_current_user_id(), array( $user_id ), $task_id, 'point', TM_NOTIFY_ACTION_WAITING_FOR );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'affectedFollowerSuccess',
				'nonce'            => wp_create_nonce( 'waiting_for_unaffectation' ),
			)
		);
	}

	/**
	 * Désaffecte un utilisateur d'une tâche.
	 *
	 * @return void
	 *
	 * @since   1.0.0.0
	 * @version 1.3.6.0
	 */
	public function ajax_waiting_for_unaffectation() {
		check_ajax_referer( 'waiting_for_unaffectation' );

		$user_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		if ( empty( $user_id ) || empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Point_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$key = array_search( $user_id, $task->data['waiting_for'] );

		if ( -1 < $key ) {
			array_splice( $task->data['waiting_for'], $key, 1 );
		}

		Point_Class::g()->update( $task->data );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'unaffectedFollowerSuccess',
				'nonce'            => wp_create_nonce( 'waiting_for_affectation' ),
			)
		);
	}

	/**
	 * Sauvegardes les données du menu Users onglet Profil et Update également les données dans Utilisateur de WordPress.
	 *
	 * @since   3.0.1
	 * @version 3.0.1
	 */
	public function callback_save_profil_task_manager() {
		check_ajax_referer( 'save_profil_task_manager' );

		$task_per_page     = ! empty( $_POST['_tm_task_per_page'] ) ? sanitize_text_field( $_POST['_tm_task_per_page'] ) : '';
		$project_state     = ( isset( $_POST['_tm_project_state'] ) && 'true' == $_POST['_tm_project_state'] ) ? true : false;
		$auto_elapsed_time = ( isset( $_POST['_tm_auto_elapsed_time'] ) && 'true' == $_POST['_tm_auto_elapsed_time'] ) ? true : false;
		$advanced_display  = ( isset( $_POST['_tm_advanced_display'] ) && 'true' == $_POST['_tm_advanced_display'] ) ? true : false;
		$quick_point       = ( isset( $_POST['_tm_quick_point'] ) && 'true' == $_POST['_tm_quick_point'] ) ? true : false;
		$display_indicator = ( isset( $_POST['_tm_display_indicator'] ) && 'true' == $_POST['_tm_display_indicator'] ) ? true : false;

		update_user_meta( get_current_user_id(), '_tm_task_per_page', $task_per_page );
		update_user_meta( get_current_user_id(), '_tm_project_state', $project_state );
		update_user_meta( get_current_user_id(), '_tm_auto_elapsed_time', $auto_elapsed_time );
		update_user_meta( get_current_user_id(), '_tm_advanced_display', $advanced_display );
		update_user_meta( get_current_user_id(), '_tm_quick_point', $quick_point );
		update_user_meta( get_current_user_id(), '_tm_display_indicator', $display_indicator );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'savedUsersProfile',
				'url'              => admin_url( 'admin.php?page=users-page' ),
			)
		);
	}
}

new Follower_Action();
