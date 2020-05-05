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

	public $user_columns_def;
	public $default_user_columns_def;

	protected function construct() {
		// Création du preset des colonnes par défaut pour tous les utilisateurs.
		$this->default_user_columns_def = array(
			'empty' => array(
				'displayed' => true,
				'order'     => 0,
			),
			'state' => array(
				'displayed' => true,
				'order'     => 2,
			),
			'archive' => array(
				'displayed' => true,
				'order'     => 7,
			),
			'name' => array(
				'displayed' => true,
				'order'     => 3,
			),
			'id' => array(
				'displayed' => true,
				'order'     => 1,
			),
			'last_update' => array(
				'displayed' => true,
				'order'     => 8,
			),
			'time' => array(
				'displayed' => true,
				'order'     => 9,
			),
			'created_date' => array(
				'displayed' => true,
				'order'     => 10,
			),
			'ended_date' => array(
				'displayed' => true,
				'order'     => 11,
			),
			'affiliated_with' => array(
				'displayed' => true,
				'order'     => 4,
			),
			'categories' => array(
				'displayed' => true,
				'order'     => 6,
			),
			'attachments' => array(
				'displayed' => true,
				'order'     => 12,
			),
			'number_comments' => array(
				'displayed' => true,
				'order'     => 13,
			),
			'author' => array(
				'displayed' => true,
				'order'     => 5,
			),
			'associated_users' => array(
				'displayed' => true,
				'order'     => 14,
			),
			'participants' => array(
				'displayed' => true,
				'order'     => 15,
			),
			'waiting_for' => array(
				'displayed' => true,
				'order'     => 16,
			),
			'empty_add' => array(
				'displayed' => true,
				'order'     => 17,
			),
		);

		// Récupère le préset des colonnes pour l'Utilisateur courrant.
		$this->user_columns_def = get_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->follower->user_columns_key, true );

		// Création des colonnes pour un Nouveau Utilisateur.
		//Si Nouvelle colonne ajouté ( Merge le préset default dans le préset user ).
		$this->user_columns_def = wp_parse_args( $this->user_columns_def, $this->default_user_columns_def );
	}

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
	public function display() {
		$default_tab = ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'tm-profile';

		$user = $this->get( array( 'id' => get_current_user_id() ), true );

		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/main-custom-menu',
			array(
				'default_tab' => $default_tab,
				'user'        => $user,

			)
		);
	}

	public function display_user() {
		$user = $this->get( array( 'id' => get_current_user_id(), ),true );

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
			'backend/user-profile-custom-menu',
			array(
				'user' => $user,
			)
		);
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

	public function create_contract_info( $title = "", $start_date = 0, $end_date_type = "actual", $end_date = "", $id = 0, $duration = 0, $planning ){

		$contract = array(
			'id'            => $id,
			'title'         => $title,
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'end_date_type' => $end_date_type,
			'duration_week' => $duration,
			'status'        => 'publish',
			'planning'      => $planning

		);
		return $contract;
	}

	public function update_contract_info( $title = "", $start_date = 0, $end_date_type = "actual", $end_date = "", $duration = 0, $contract_old, $planning ){
		$contract = array(
			'id'            => $contract_old[ 'id' ],
			'title'         => $title,
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'end_date_type' => $end_date_type,
			'duration_week' => $duration,
			'status'        => $contract_old[ 'status' ],
			'planning'      => $planning
		);

		return $contract;
	}

	public function checkIfDateIsValid( $user_id, $end_date_type, $start_date, $end_date, $contracts, $key_actual = -1 ){
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
			if( $contract[ 'status' ] == "delete" || $key == $key_actual ){
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

		update_user_meta( $user_id, '_tm_planning_users_contract', $contracts );
		$return_data[ 'success' ] = true;

		return $return_data;
	}

	public function addNumberOfDayBetweenStartAndEnd( $contracts ){
		if( empty( $contracts ) ){
			return array();
		}

		foreach( $contracts as $key => $contract ){
			if( $contract[ 'end_date_type' ] == "actual" ){
				$days = strtotime( 'now' ) - $contract[ 'start_date' ];
			}else{
				$days = $contract[ 'end_date' ] - $contract[ 'start_date' ];
			}
			$contracts[ $key ][ 'duration' ] = round( $days /60 /60 /24, 1 ) + 1;
		}

		return $contracts;
	}

	public function durationPerDayPlanning( $planning ){
		$duration_week = 0;

		foreach( $planning as $key_d => $day ){
			$duration = 0;
			foreach( $day as $key_p => $period ){
				$from = $period[ 'work_from' ] != "" ? Activity_Class::g()->explode_format_hour_to_minute( $period[ 'work_from' ] ) : 0;
				$to = $period[ 'work_to' ] != "" ? Activity_Class::g()->explode_format_hour_to_minute( $period[ 'work_to' ] ) : 0;
				if( $from > $to ){
					$planning[ $key_d ][ $key_p ][ 'work_to' ] = $from;
				}else{
					$duration += $to - $from;
				}
			}
			$planning[ $key_d ][ 'duration' ] = $duration;
			$duration_week += $duration;
		}
		return array( 'planning' => $planning, 'duration_week' => round( $duration_week, 2 ) );
	}

	public function calculHourPerDayInPlanning( $planning, $duration_week ){
		$days = array(
			'period'    => $this->defineAllDaysPlanning( esc_html( 'Period', 'task-manager' ), $duration_week ),
			'monday'    => $this->defineAllDaysPlanning( esc_html( 'Monday', 'task-manager' ), $planning[ 'monday' ][ 'duration' ] ),
			'tuesday'   => $this->defineAllDaysPlanning( esc_html( 'Tuesday', 'task-manager' ), $planning[ 'tuesday' ][ 'duration' ] ),
			'wednesday' => $this->defineAllDaysPlanning( esc_html( 'Wednesday', 'task-manager' ), $planning[ 'wednesday' ][ 'duration' ] ),
			'thursday'  => $this->defineAllDaysPlanning( esc_html( 'Thursday', 'task-manager' ), $planning[ 'thursday' ][ 'duration' ] ),
			'friday'    => $this->defineAllDaysPlanning( esc_html( 'Friday', 'task-manager' ), $planning[ 'friday' ][ 'duration' ] ),
			'saturday'  => $this->defineAllDaysPlanning( esc_html( 'Saturday', 'task-manager' ), $planning[ 'saturday' ][ 'duration' ] ),
			'sunday'    => $this->defineAllDaysPlanning( esc_html( 'Sunday', 'task-manager' ), $planning[ 'sunday' ][ 'duration' ] )
		);

		return $days;
	}

	public function defineAllDaysPlanning( $day_name, $duration ){
		$data = array(
			'day_name' => $day_name,
			'duration' => $duration,
			'readable' => Task_Class::g()->change_minute_time_to_readabledate( $duration )
		);
		return $data;
	}

	public function oneContractIsValid( $contracts ){
		if( empty( $contracts ) ){
			return false;
		}

		foreach( $contracts as $contract ){
			if( $contract[ 'status' ] != "delete" ){
				return true;
			}
		}
		return false;
	}

	public function numberContractValid( $contracts ){
		$nbr = 0;
		foreach( $contracts as $contract ){
			if( $contract[ 'status' ] != "delete" ){
				$nbr ++;
			}
		}

		return $nbr;
	}
}

Follower_Class::g();
