<?php
/**
 * Classe gérant les utilisateurs
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.3.6
 * @version 1.5.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les utilisateurs
 */
class Follower_Class extends \eoxia\User_Class {
	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\Follower_Model';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'follower';

	/**
	 *
	 * Modifie les shortscuts de chaque utilisateur
	 *
	 * @return void
	 *
	 * @since ? => Before 1.9.0 - BETA
	 */
	public function init_default_data() {
		$users = get_users(
			array(
				'roles' => 'administrator',
			)
		);

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$shortcuts = get_user_meta( $user->ID, '_tm_shortcuts', true );

				if ( empty( $shortcuts ) ) {
					$shortcuts = array(
						'wpeomtm-dashboard' => array(
							array(
								'label' => __( 'My tasks', 'task-manager' ),
								'page'  => 'admin.php',
								'link'  => '?page=wpeomtm-dashboard&user_id=' . $user->ID(),
							),
						),
					);

					update_user_meta( $user->ID, '_tm_shortcuts', $shortcuts );
				}
			}
		}
	}

	public function create_planning_user_indicator_period( $day = 'monday', $type = 'morning' ){
		return array(
			'work_from'          => '',
			'work_to'            => '',
		);
 	}

	public function create_planning_user_indicator_day( $day = 'monday' ){
		return array(
			'morning'  => $this->create_planning_user_indicator_period( $day, 'morning' ),
			'afternoon' => $this->create_planning_user_indicator_period( $day, 'afternoon' )
		);
	}

	public function create_planning_user_indicator(){
		return array(
			'monday' => $this->create_planning_user_indicator_day( 'monday' ),
			'tuesday' => $this->create_planning_user_indicator_day( 'tuesday' ),
			'wednesday' => $this->create_planning_user_indicator_day( 'wednesday' ),
			'thursday' => $this->create_planning_user_indicator_day( 'thursday' ),
			'friday' => $this->create_planning_user_indicator_day( 'friday' ),
			'saturday' => $this->create_planning_user_indicator_day( 'saturday' ),
			'sunday' => $this->create_planning_user_indicator_day( 'sunday' )
		);
	}

	public function update_planning_user( $planning ){
		/*$user_id = get_current_user_id();
		$planning_user = get_user_meta( $user_id, '_tm_planning_users_indicator', true );

		if( empty( $planning_user ) ){
			$planning_user = $this->create_planning_user_indicator( $user_id );
		}

		if( $planning_user[ $planning[ 'day_name' ] ][ $planning[ 'period' ] ][ 'status' ] == "publish" ){ // on sauvegarde
			$planning_user = $this->delete_row_planning_user( $planning[ 'day_name' ], $planning[ 'period' ], "archive", $planning );
		}

		$data_day = wp_parse_args( $planning, $planning_user[ $planning[ 'day_name' ] ][ $planning[ 'period' ] ] );
		$planning_user[ $planning[ 'day_name' ] ][ $planning[ 'period' ] ] = $data_day;

		update_user_meta( $user_id, '_tm_planning_users_indicator', $planning_user );

		return $planning_user;*/
	}

	public function display_indicator_table( $planning ){
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/user-profile-planning-only',
			array(
				'planning' => $planning
			)
		);

		return ob_get_clean();
	}

	public function delete_row_planning_user( $day, $period, $type = "delete", $new_planning = array() ){
		/*$user_id = get_current_user_id();
		$planning = get_user_meta( $user_id, '_tm_planning_users_indicator', true );
		$planning[ $day ][ $period ][ 'day_delete' ] = strtotime( 'now' );

		$archive_planning = get_user_meta( $user_id, '_tm_planning_users_indicator_archive', true );
		$archive_planning = ! empty( $archive_planning ) ? $archive_planning : array();
		if( empty( $archive_planning[ $day ][ $period ] ) ){
			$archive_planning[ $day ][ $period ] = array();
		}
		$planning[ $day ][ $period ][ 'status' ] = $type;
		array_push( $archive_planning[ $day ][ $period ], $planning[ $day ][ $period ] );
		update_user_meta( $user_id, '_tm_planning_users_indicator_archive', $archive_planning );

		if( empty( $new_planning ) ){
			$planning[ $day ][ $period ] = $this->create_planning_user_indicator_period( $day, $period ); // on vide le tableau
		}else{
			$planning[ $day ][ $period ] = $new_planning;
		}
		update_user_meta( $user_id, '_tm_planning_users_indicator', $planning );

		return $planning;*/
	}

	public function filter_planning_to_rerun( $planning, $period ){
		/*$planning_valid_to_rerun = array();
		$valid_day = false;
		foreach( $planning as $day_n => $day ){
			$data = array(
				'day'    => $day_n,
				'period' => $period,
				'valid'  => false
			);
			if( $day[ $period ][ 'status' ] != 'publish' && $day_n != "saturday" && $day_n != "sunday" ){
				$data[ 'valid' ] = true;
				$valid_day = true;
			}
			$planning_valid_to_rerun[] = $data;
		}

		return array( 'planning' => $planning_valid_to_rerun, 'valid_day' => $valid_day );*/
	}

	public function display_indicator_indicator_to_rerun( $planning_valid, $planning ){
	/*	ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/indicator-table/information-run-for-another-days',
			array(
				'planning_valid' => $planning_valid,
				'planning'       => $planning
			)
		);
		return ob_get_clean();*/
	}

	/**
	 * Order by des tableaux de deux dimensions
	 *
	 * @param  [type] $array [L'objet].
	 * @param  [type] $on    [L'element qui doit etre trié].
	 * @param  [type] $order [SORT_ASC ou SORT_DESC].
	 *
	 * @return [type]        [Tableau trié].
	 *
	 * @since 1.9.0 - BETA
	 */
	function array_sort( $array, $on, $order = SORT_ASC ) {
		$new_array      = array();
		$sortable_array = array();

		if ( count( $array ) > 0 ) {
			foreach ( $array as $k => $v ) {
				if ( is_array( $v ) ) {
					foreach ( $v as $k2 => $v2 ) {
						if ( $k2 == $on ) {
							$sortable_array[ $k ] = $v2;
						}
					}
				} else {
					$sortable_array[ $k ] = $v;
				}
			}

			switch ( $order ) {
				case SORT_ASC:
					asort( $sortable_array );
					break;
				case SORT_DESC:
					arsort( $sortable_array );
					break;
			}

				$i = 0;
			foreach ( $sortable_array as $k => $v ) {
				$new_array[ $i ] = $array[ $k ];
				$i ++;
			}
		}

		return $new_array;
	}

	public function tradThisDay( $day = "monday" ){
		$day_translate = '';
		switch( $day ){
			case 'tuesday':
				$day_translate = __( 'Tuesday', 'task-manager' );
				break;
			case 'wednesday':
				$day_translate = __( 'Wednesday', 'task-manager' );
			break;
			case 'thursday':
				$day_translate = __( 'Thursday', 'task-manager' );
			break;
			case 'friday':
				$day_translate = __( 'Friday', 'task-manager' );
				break;
			case 'saturday':
				$day_translate = __( 'Saturday', 'task-manager' );
				break;
			case 'sunday':
				$day_translate = __( 'Sunday', 'task-manager' );
				break;
			default:
				$day_translate = __( 'Monday', 'task-manager' );
				break;
		}
		return $day_translate;
	}

	public function tradThisPeriod( $period = "monday" ){
		$period_translate = __( 'Afternoon', 'task-manager' );
		if( $period == "morning" ){
			$period_translate = __( 'Morning', 'task-manager' );
		}
		return $period_translate;
	}

	public function define_schema_new_contract_planning(){
		$planning = $this->create_planning_user_indicator();

		foreach( $planning as $key_d => $day ){
			foreach( $day as $key_p => $period ){
				if( $key_d == "sunday" || $key_d == "saturday" ){
					continue;
				}
				if( $key_p == "morning" ){
					$planning[ $key_d ][ $key_p ][ 'work_from' ] = "09:00";
					$planning[ $key_d ][ $key_p ][ 'work_to' ] = "12:00";
				}else{
					$planning[ $key_d ][ $key_p ][ 'work_from' ] = "14:00";
					$planning[ $key_d ][ $key_p ][ 'work_to' ] = "18:00";
				}
			}
		}
		return $planning;
	}

	public function define_schema_new_contract( $nbr_contracts ){
		$contract = array(
			'id'            => -1,
			'title'         => sprintf( __( 'New contract (%1$s)', 'task-manager' ), $nbr_contracts + 1 ),
			'start_date'    => strtotime( "-1 year" ),
			'end_date'      => strtotime( "now" ),
			'end_date_type' => 'actual',
			'status'        => 'publish'
		);
		return $contract;
	}

	public function create_contract_info( $title = "", $start_date = 0, $end_date_type = "actual", $end_date = "", $id = 0 ){

		$contract = array(
			'id'            => $id,
			'title'         => $title,
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'end_date_type' => $end_date_type,
			'status'        => 'publish'
		);

		return $contract;
	}

	public function create_contract_planning( $contract, $planning, $id_contract ){
		$user_id = get_current_user_id();
		$planning_db = get_user_meta( $user_id, '_tm_planning_users_indicator', true );
		$planning_db = ! empty( $planning_db ) ? $planning_db : array();

		$info = array(
			'id' => $id_contract
	 	);

		$full_planning = array(
			'info' => $info,
			'planning' => $planning
		);

		array_push( $planning_db, $full_planning );
		update_user_meta( $user_id, '_tm_planning_users_indicator', $planning_db );
	}

	public function checkIfDateIsValid( $end_date_type, $start_date, $end_date, $contracts ){
		$return_data = array(
			'success'       => false,
			'error'         => '',
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'end_date_type' => $end_date_type
		);

		if( $end_date_type == "sql" ){
			if( $start_date > $end_date ){
				$return_data[ 'error' ] = esc_html( '1. Start Date > End Date', 'task-manager' );
				return $return_data;
			}
		}else{
			if( $start_date > strtotime( 'now' ) ){
				$return_data[ 'error' ] = esc_html( '2. Start Date must be before actual time', 'task-manager' );
				return $return_data;
			}
		}

		foreach( $contracts as $key => $contract ){
			if( $contract[ 'status' ] == "delete" ){
				continue;
			}

			$return_data[ 'contract_conflict' ] = $contract;
			if( $end_date_type == "sql" ){
				if( $start_date > $contract[ 'end_date' ] ){
					continue;
				}else if( $end_date < $contract[ 'start_date' ] ){
					continue;
				}else{
					$return_data[ 'error' ] = sprintf( __( '3. Conflict with date of another plugin : (%1$s)', 'task-manager' ), $contract[ 'title' ] );
					return $return_data;
				}
			}else{
				if( $contract[ 'end_date_type' ] == "actual" ){
					if( $start_date > $contract[ 'start_date' ] ){
						$contracts[ $key ][ 'end_date_type' ] = "sql";
						$contracts[ $key ][ 'end_date' ] = $start_date - 86400;
						continue;
					}else{
						$return_data[ 'error' ] = sprintf( __( '4. Conflict with date of another plugin : (%1$s)', 'task-manager' ), $contract[ 'title' ] );
						return $return_data;
					}
				}else if( $contract[ 'end_date_type' ] == "sql" && $start_date > $contract[ 'end_date' ] ){
					continue;
				}else if( $contract[ 'end_date_type' ] == "sql" && $end_date < $contract[ 'start_date' ] ){
					continue;
				}else{
					$return_data[ 'error' ] = sprintf( __( '5. Conflict with date of another plugin : (%1$s)', 'task-manager' ), $contract[ 'title' ] );
					return $return_data;
				}
			}
		}
		$user_id = get_current_user_id();

		update_user_meta( $user_id, '_tm_planning_users_contract', $contracts );
		$return_data[ 'success' ] = true;

		return $return_data;
	}
}

Follower_Class::g();
