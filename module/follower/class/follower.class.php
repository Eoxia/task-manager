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

	/**
	 * [update_planning description]
	 *
	 * @param  [type]   $user     [contient l'id du user : $user['id']].
	 * @param  [object] $planning [planning de jour].
	 * @param  [type]   $date_en [date au format Anglais].
	 *
	 * @return void
	 *
	 * @since 1.9.0 - BETA
	 */
	public function update_planning( $user, $planning, $date_en ) {

		$date = date( 'd/m/Y', strtotime( $date_en ) );

		foreach ( $planning as $key => $value ) { // @info On verifie que les journées soient OK
			if ( 0 > $planning[ $key ] || 1440 < $planning[ $key ] ) { // @info Journée => 0-1440 minutes
				$planning[ $key ] = 0;
			}
		}

		$minuraty_duration = array(
			'Monday'    => $planning['mon'],
			'Tuesday'   => $planning['tue'],
			'Wednesday' => $planning['wed'],
			'Thursday'  => $planning['thu'],
			'Friday'    => $planning['fri'],
			'Saturday'  => $planning['sat'],
			'Sunday'    => $planning['sun'],
		);

		$data_plan = get_user_meta( $user['id'], '_tm_planning_users', true );

		if ( empty( $data_plan[0] ) ) {
			$minuteupdate = false;
			foreach ( $minuraty_duration as $key => $value ) {
				if ( 0 != $value ) {
					$minuteupdate = true;
				}
			}

			if ( false == $minuteupdate ) {
				return;
			}

			$this->callback_update_db_planning( $user['id'], 0, strtotime( $date_en ), 0, $minuraty_duration );

			$data_plan = array(
				array(
					'date'              => $date,
					'date_en'           => $date_en,
					'minutary_duration' => $minuraty_duration,
				),
			);

			$data_withnewdate = $data_plan;

		} else {
			$temp = count( $data_plan );
			if ( $data_plan[0]['minutary_duration'] === $minuraty_duration ) { // @info on verifie si le changement actuel est différent du dernier
				return;
			}

			$data_plan = $this->array_sort( $data_plan, 'date_en', SORT_DESC ); // @info Objet trié par 'date'

			$date_update      = 0;
			$data_withnewdate = [];
			$date_pluspetite  = false;

			$date_debut_str = 0; // @info Date de début -> Choisis par l'utilisateur
			$date_duree_str = 0; // @info Date durée -> Date butoir - date de début = le nombre de jour à ajouté dans la db

			$lastdate = '';

			foreach ( $data_plan as $key => $value ) {

				if ( strtotime( $value['date_en'] ) < strtotime( $date_en ) ) {

					if ( '' == $lastdate ) { // @info Creation sur 5 ans
						$date_duree_str = 0;
					} else { // @info date butoir trouvé
						$date_duree_str = strtotime( $lastdate ) - strtotime( $date_en );
					}

					$date_pluspetite = true;
					$date_update     = $key;

					break;
				}

				$date_duree_str = strtotime( $value['date_en'] ) - strtotime( $date_en );
				$lastdate       = $value['date_en'];
			}

			$date_debut_str = strtotime( $date_en );

			if ( ! $date_pluspetite ) {

				foreach ( $data_plan as $key => $value ) {
					array_push( $data_withnewdate, $value );
				}

					$temp_length                                 = count( $data_withnewdate );
					$data_withnewdate[ $temp_length ]['date']    = $date;
					$data_withnewdate[ $temp_length ]['date_en'] = $date_en;
					$data_withnewdate[ $temp_length ]['minutary_duration'] = $minuraty_duration;

			} else {

				foreach ( $data_plan as $key => $value ) {
					if ( $key == $date_update ) {
						$temp_length                                 = count( $data_withnewdate );
						$data_withnewdate[ $temp_length ]['date']    = $date;
						$data_withnewdate[ $temp_length ]['date_en'] = $date_en;
						$data_withnewdate[ $temp_length ]['minutary_duration'] = $minuraty_duration;
					}
					array_push( $data_withnewdate, $value );
				}
			}

			$this->callback_update_db_planning( $user['id'], $data_withnewdate[ $date_update ]['minutary_duration'], $date_debut_str, $date_duree_str, $minuraty_duration );
		}

		update_user_meta( $user['id'], '_tm_planning_users', $data_withnewdate );
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

	/**
	 * Return une date au format Y-m-d
	 *
	 * @param  [date] $start  [date de début].
	 * @param  [date] $end    [date de fin].
	 * @param  date   $format [date].
	 *
	 * @return [date]
	 *
	 * @since 1.9.0 - BETA
	 */
	function get_dates_from_range( $start, $end, $format = 'Y-m-d' ) {
		return array_map(
			function( $timestamp ) use ( $format ) {
				return date( $format, $timestamp );
			},
			range( strtotime( $start ) + ( $start < $end ? 4000 : 8000 ), strtotime( $end ) + ( $start < $end ? 8000 : 4000 ), 86400 )
		);
	}

	/**
	 * Recupere le nombre jour entre deux dates
	 * Cette function va servir a update les plannings des utilisateurs
	 *
	 * @param  [date] $debut    [date de debut].
	 * @param  [int]  $duree    [strtotime de la durée : date_de_fin - date_de_debut].
	 * @param  string $planning [planning utilisateur].
	 *
	 * @return object [liste des jours avec leur durée]
	 *
	 * @since 1.9.0 - BETA
	 */
	function get_all_day_between_two_dates( $debut, $duree, $planning = '' ) {

		$every_day_between_les_deux_dates = $this->get_dates_from_range( date( 'Y-m-d', $debut ), date( 'Y-m-d', $debut + $duree ) );

		$full_data = [];

		foreach ( $every_day_between_les_deux_dates as $key => $value ) {

			$length = 0;
			if ( ! empty( $full_data[ date( 'Y', strtotime( $value ) ) ][ date( 'm', strtotime( $value ) ) ] ) ) {
				$length = count( $full_data[ date( 'Y', strtotime( $value ) ) ][ date( 'm', strtotime( $value ) ) ] );
			}

			$full_data[ date( 'Y', strtotime( $value ) ) ][ date( 'm', strtotime( $value ) ) ][ $length + 1 ] = array(
				'date'    => $value,
				'jour'    => date( 'D', strtotime( $value ) ),
				'default' => $planning[ date( 'l', strtotime( $value ) ) ], // @info Valeur définis historique -> Jour / défaut
				'absent'  => 0, // @info 0 || définis par l'utilisateur -> Absence
				'holiday' => 0, // @info 0 || définis par l'utilisateur -> Vacance (Congés)
				'seek'    => 0, // @info 0 || définis par l'utilisateur -> Malade
				'free'    => 0, // @info 0 || définis par l'admin -> Fériés
				'ticket'  => 0, // @info 0 || définis par l'utilisateur -> Tickets
			);
		}

		return $full_data;
	}

	/**
	 * Recupere le nouveau planning d'un utilisateur pour l'ajouter à la base de donnée
	 *
	 * @param  [int]    $id       [id de l'utilisateur].
	 * @param  [object] $data     [données].
	 * @param  [date]   $debut    [date de début].
	 * @param  [int]    $duree    [duree de planning].
	 * @param  [type]   $planning [liste des jours avec leur durée respective].
	 *
	 * @return void
	 *
	 * @since 1.9.0 - BETA
	 */
	public function callback_update_db_planning( $id, $data, $debut, $duree, $planning ) {
		// @info Id utilisateur | date nouveau planning
		if ( 0 == $duree ) { // @info Si la personne créait un nouvel emploi du temps => 5 ans
			$duree = 31556925 * 5; // @info Année tropique * 5 Y
		}

		$all_day_object = $this->get_all_day_between_two_dates( $debut, $duree, $planning );

		$premier_mois = false;

		foreach ( $all_day_object as $keyyear => $year ) {
			$dernier_mois['annee'] = $keyyear;
			foreach ( $year as $keymonth => $months ) {
				$dernier_mois['mois'] = $keymonth;
			}
		}

		foreach ( $all_day_object as $keyy => $years ) {
			foreach ( $years as $keym => $month ) {
				$db_planning_name_column = '_tm_planning_' . $keyy . '_' . $keym;

				if ( ! $premier_mois && get_user_meta( $id, $db_planning_name_column, true ) != null ) { // @info Si le premier mois est existant, on ne l'écrase pas entièrement, on récupere le début de mois

					$premier_mois            = true; // @info Seulement pour le premier mois
					$content_month_user_meta = get_user_meta( $id, $db_planning_name_column, true );
					$plan_month              = $content_month_user_meta;

					foreach ( $month as $key => $value ) { // @info On parcours le nouveau planning
						foreach ( $content_month_user_meta as $keydb => $valuedb ) { // @info On parcours l'ancien planning
							if ( $value['date'] == $valuedb['date'] ) { // @info Si on retrouve une date commune -> le nouvel emploi du temps l'ecrase
								$plan_month[ $keydb ] = $value;
								break;
							}
						}
					}
					update_user_meta( $id, $db_planning_name_column, $plan_month );
				} elseif ( $keyy == $dernier_mois['annee'] && $keym == $dernier_mois['mois'] && get_user_meta( $id, $db_planning_name_column, true ) != null ) { // @info Dernier mois
					$content_month_user_meta = get_user_meta( $id, $db_planning_name_column, true );
					$plan_month              = $content_month_user_meta;

					foreach ( $month as $key => $value ) { // @info On parcours le nouveau planning
						foreach ( $content_month_user_meta as $keydb => $valuedb ) { // @info On parcours l'ancien planning
							if ( $value['date'] == $valuedb['date'] ) { // @info Si on retrouve une date commune -> le nouvel emploi du temps l'ecrase
								$plan_month[ $keydb ] = $value;
								break;
							}
						}
					}

					update_user_meta( $id, $db_planning_name_column, $plan_month );
				} else {
					update_user_meta( $id, $db_planning_name_column, $month );
				}
			}
		}
	}

	/**
	 * Supprime toute la base de donnée d'un itilisateur
	 *
	 * @param  [int]  $id       [Id de l'utilisateur].
	 * @param  [type] $planning [Planning de l'utilisateur].
	 *
	 * @return void
	 *
	 * @since 1.9.0 - BETA
	 */
	public function delete_all_db_planning( $id, $planning ) {

		$date_fin_str   = strtotime( $planning[0]['date_en'] );
		$date_debut_str = strtotime( $planning[ count( $planning ) - 1 ]['date_en'] );
		$duree_str      = $date_fin_str - $date_debut_str + 31556925 * 5;

		$all_day_object = $this->get_all_day_between_two_dates( $date_debut_str, $duree_str );

		foreach ( $all_day_object as $keyy => $years ) {
			foreach ( $years as $keym => $month ) {
				$db_planning_name_column = '_tm_planning_' . $keyy . '_' . $keym;
				if ( get_user_meta( $id, $db_planning_name_column, true ) != null ) {
					delete_usermeta( $id, $db_planning_name_column );
				}
			}
		}
	}

	/**
	 * Creer le planning d'un utilisateur sur une période donnée
	 *
	 * @param  [type] $id       [id de l'utilisateur].
	 * @param  [type] $planning [planning de l'utilisateur].
	 *
	 * @return void
	 * @since 1.9.0 - BETA
	 */
	public function create_all_db_planning( $id, $planning ) {

		if ( empty( $planning ) ) { // @info si il reste une seule ligne sur le planning -> planning vide -> rien à créer
			return;
		}

		foreach ( $planning as $key => $ligne ) {
			$date_debut_str    = strtotime( $ligne['date_en'] );
			$minutary_duration = $ligne['minutary_duration'];

			if ( 0 == $key ) {
				$duree_str = 31556925 * 5;

			} else {
				$duree_str = $last_date_str - $date_debut_str;
			}

			$last_date_str = $date_debut_str;

			$this->callback_update_db_planning( $id, 0, $date_debut_str, $duree_str, $minutary_duration );
		}
	}

	/**
	 * Récupère l'archive de l'utilisateur
	 *
	 * @param  [type] $id [id de l'utilisateur].
	 *
	 * @return data
	 * @since 1.9.0 - BETA
	 */
	public function get_User_Archive( $id ) {
		if ( get_user_meta( $id, '_tm_planning_archives' ) != null && get_user_meta( $id, '_tm_planning_archives' ) != '' ) {
			return get_user_meta( $id, '_tm_planning_archives', true );
		} else {
			return '';
		}
	}
}

Follower_Class::g();
