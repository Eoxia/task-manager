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

		add_action( 'wp_ajax_deleteplan', array( $this, 'callback_delete_plan' ) );

		add_action( 'wp_ajax_add_element_to_planning_user', array( $this, 'callback_add_element_to_planning_user' ) );

		add_action( 'wp_ajax_delete_element_from_planning_user', array( $this, 'callback_delete_element_from_planning_user' ) );

		add_action( 'wp_ajax_applicate_same_planning_for_another_days', array( $this, 'callback_applicate_same_planning_for_another_days' ) );

		add_action( 'wp_ajax_display_contract_planning', array( $this, 'callback_display_contract_planning' ) );

		add_action( 'wp_ajax_create_new_contract', array( $this, 'callback_create_new_contract' ) );

		add_action( 'wp_ajax_display_new_contract_view', array( $this, 'callback_display_new_contract_view' ) );

		add_action( 'wp_ajax_delete_this_contract', array( $this, 'callback_delete_this_contract' ) );


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
				if ( ! in_array( $affected_id, $followers_only_id, true ) ) {
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

		$key = array_search( $user_id, $task->data['user_info']['affected_id'], true );

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
				'contracts' => $contracts,
				'one_contract_is_valid' => $one_contract_is_valid
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
		$user['_tm_auto_elapsed_time'] = isset( $_POST['_tm_auto_elapsed_time'] ) && boolval( $_POST['_tm_auto_elapsed_time'] ) ? true : false;
		$user['_tm_advanced_display']  = isset( $_POST['_tm_advanced_display'] ) && boolval( $_POST['_tm_advanced_display'] ) ? true : false;
		$user['_tm_quick_point']       = isset( $_POST['_tm_quick_point'] ) && boolval( $_POST['_tm_quick_point'] ) ? true : false;
		$user['_tm_display_indicator'] = isset( $_POST['_tm_display_indicator'] ) && boolval( $_POST['_tm_display_indicator'] ) ? true : false;


		update_user_meta( $user_id, '_tm_auto_elapsed_time', $user['_tm_auto_elapsed_time'] );
		update_user_meta( $user_id, '_tm_advanced_display', $user['_tm_advanced_display'] );
		update_user_meta( $user_id, '_tm_quick_point', $user['_tm_quick_point'] );
		update_user_meta( $user_id, '_tm_display_indicator', $user['_tm_display_indicator'] );
	}

	public function callback_add_element_to_planning_user(){
		/*check_ajax_referer( 'add_element_to_planning_user' );

		$name      = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$day       = ! empty( $_POST['day'] ) ? sanitize_text_field( $_POST['day'] ) : '';
		$period    = ! empty( $_POST['period'] ) ? sanitize_text_field( $_POST['period'] ) : '';
		$work_from = ! empty( $_POST['work_from'] ) ? sanitize_text_field( $_POST['work_from'] ) : '';
		$work_to   = ! empty( $_POST['work_to'] ) ? sanitize_text_field( $_POST['work_to'] ) : '';
		$day_start = ! empty( $_POST['day_start'] ) ? strtotime( $_POST['day_start'] ) : strtotime( 'now' );

		if( ! $name || ! $day || ! $period || ! $work_from || ! $work_to ){
			wp_send_json_error();
		}

		$planning = array(
			'name'      => $name,
			'day_name'  => $day,
			'period'    => $period,
			'work_from' => $work_from,
			'work_to'   => $work_to,
			'day_start' => $day_start,
			'status'    => 'publish'
		);*/

		$day_trad = Follower_Class::g()->tradThisDay( $day );
		$period_trad = Follower_Class::g()->tradThisPeriod( $period );
		$status = $name . ' : ' . $day_trad . ' ' . $period_trad . ' => ' . $work_from . '-' . $work_to;

		$planning_user = Follower_Class::g()->update_planning_user( $planning ); // Update les valeurs dans la db
		$view = Follower_Class::g()->display_indicator_table( $planning_user ); // Return la vue du tableau

		$return = Follower_Class::g()->filter_planning_to_rerun( $planning_user, $period );
		$view_rerun = '';

		if( $return[ 'valid_day' ] ){
			$planning_valid_to_rerun = $return[ 'planning' ];
			$view_rerun = Follower_Class::g()->display_indicator_indicator_to_rerun( $planning_valid_to_rerun, $planning );
		}

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadPlanningUserIndicator',
				'view'             => $view,
				'view_rerun'       => $view_rerun,
				// 'action_status'    => $status,
				'action_type'      => 'add',
				'action_text'      => $status
				// 'action_text'      => __( 'Element add !', 'task-manager')
			)
		);
	}


	public function callback_delete_element_from_planning_user(){
		/*check_ajax_referer( 'delete_element_from_planning_user' );
		$period    = ! empty( $_POST['period'] ) ? sanitize_text_field( $_POST['period'] ) : '';
		$day       = ! empty( $_POST['day'] ) ? sanitize_text_field( $_POST['day'] ) : '';

		$planning = Follower_Class::g()->delete_row_planning_user( $day, $period );
		$view     = Follower_Class::g()->display_indicator_table( $planning );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadPlanningUserIndicator',
				'view'             => $view,
				'action_type'      => 'delete',
				'action_text'      => __( 'Element delete !', 'task-manager'),
			)
		);*/
	}

	public function callback_applicate_same_planning_for_another_days(){
		/*check_ajax_referer( 'applicate_same_planning_for_another_days' );
		$name      = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$period    = ! empty( $_POST['period'] ) ? sanitize_text_field( $_POST['period'] ) : '';
		$work_from = ! empty( $_POST['work_from'] ) ? sanitize_text_field( $_POST['work_from'] ) : '';
		$work_to   = ! empty( $_POST['work_to'] ) ? sanitize_text_field( $_POST['work_to'] ) : '';
		$day_start = ! empty( $_POST['day_start'] ) ? (int) $_POST['day_start'] : strtotime( 'now' );
		$days = ! empty( $_POST['day'] ) ? (array) $_POST['day'] : array();

		if( ! $name || empty( $days ) || ! $period || ! $work_from || ! $work_to  ){
			wp_send_json_error();
		}

		$list_day = "";
		foreach( $days as $key => $day ){
			if( isset( $days[ $key + 1 ] ) ){
				$list_day .= Follower_Class::g()->tradThisDay( $day ) . ', ';
			}else{
				$list_day .= Follower_Class::g()->tradThisDay( $day );
			}
			$planning = array(
				'name'      => $name,
				'day_name'  => $day,
				'period'    => $period,
				'work_from' => $work_from,
				'work_to'   => $work_to,
				'day_start' => $day_start,
				'status'    => 'publish'
			);

			$planning_user = Follower_Class::g()->update_planning_user( $planning ); // Update les valeurs dans la db
		}

		$user_id = get_current_user_id();
		$planning_user = get_user_meta( $user_id, '_tm_planning_users_indicator', true );
		$view = Follower_Class::g()->display_indicator_table( $planning_user ); // Return la vue du tableau

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadPlanningUserIndicator',
				'view'             => $view,
				'view_rerun'       => '',
				'action_type'      => 'add',
				'action_text'      => __( 'These elements were adjusted', 'task-manager') . ' : ' . $list_day
			)
		);*/
	}

	public function callback_display_contract_planning(){
		check_ajax_referer( 'display_contract_planning' );
		$user_id = get_current_user_id();

		$id = ! empty( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : -1;
		//$edit = ! empty( $_POST[ 'edit' ] ) ? true : false;
		$edit = true;

		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		$contracts = ! empty( $contracts ) ? $contracts : array();

		if( $id < 0 ){
			$contract = Follower_Class::g()->define_schema_new_contract( count( $contracts ) );
			$planning = Follower_Class::g()->define_schema_new_contract_planning();

			// $edit = true;
		}else{
			$key = $id - 1;
			if( isset( $contracts[ $key ] ) ){
				$planning_db = get_user_meta( $user_id, '_tm_planning_users_indicator', true );

				$contract = $contracts[ $key ];
				$planning = $planning_db[ $key ][ 'planning' ];
			}else{
				wp_send_json_error( 'Error db' );
			}
		}

		$periods = array( 'morning', 'afternoon' );

		$return_planning = Follower_Class::g()->durationPerDayPlanning( $planning );
		$planning = $return_planning[ 'planning' ];

		$days =  Follower_Class::g()->calculHourPerDayInPlanning( $planning, $return_planning[ 'duration_week' ] );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/user-add-contract',
			array(
				'contract' => $contract,
				'planning' => $planning,
				'periods'  => $periods,
				'edit'     => $edit,
				'days'     => $days
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'follower',
				'callback_success' => 'reloadViewProfileContract',
				'view'             => ob_get_clean()
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

		$return_planning = Follower_Class::g()->durationPerDayPlanning( $planning );
		$planning = $return_planning[ 'planning' ];
		$duration_week = round( $return_planning[ 'duration_week' ] / 60, 2 ); // Minute To hour
		$user_id = get_current_user_id();
		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		$contracts = ! empty( $contracts ) ? $contracts : array();
		$return_request_ajax = array(	'success' => true );

		$key = $id - 1;
		if( isset( $contracts[ $key ] ) ){
			$return_data = Follower_Class::g()->checkIfDateIsValid( $end_date_type, $start_date, $end_date, $contracts, $key );
			if( $return_data[ 'success' ] ){
				$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
				$contract = Follower_Class::g()->update_contract_info( $title, $start_date, $end_date_type, $end_date, $duration_week, $contracts[ $key ] );
				$contracts[ $key ] = $contract;
				Follower_Class::g()->update_contract_planning( $planning, $contract[ 'id' ], $key );
				// $days_duration = Follower_Class::g()->durationPerDayPlanning( $planning );
			}else{
				$return_request_ajax[ 'data' ] = $return_data;
				$return_request_ajax[ 'success' ] = false;
			}
		}else{
			$return_data = Follower_Class::g()->checkIfDateIsValid( $end_date_type, $start_date, $end_date, $contracts );
			if( $return_data[ 'success' ] ){
				$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
				$contract = Follower_Class::g()->create_contract_info( $title, $start_date, $end_date_type, $end_date, count( $contracts ) + 1, $duration_week );
				array_push( $contracts, $contract );
				Follower_Class::g()->create_contract_planning( $planning, $contract[ 'id' ] );
				// $days_duration = Follower_Class::g()->durationPerDayPlanning( $planning );
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
					'contracts' => $contracts,
					'one_contract_is_valid' => $one_contract_is_valid
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

		if( $id < 0 ){
			wp_send_json_error( 'ID UNDEFINED' );
		}

		$user_id = get_current_user_id();
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
				'contracts' => $contracts,
				'one_contract_is_valid' => $one_contract_is_valid
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
}

new Follower_Action();
