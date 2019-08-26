<?php
/**
 * Gestion des activitées.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @since     1.5.0
 * @version   1.6.0
 * @copyright 2015-2018 Eoxia
 * @package   Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des activitées.
 */
class Activity_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	protected function construct() {
	}

	/**
	 * Récupères les commentaires et les points dans l'ordre de date décroissante.
	 *
	 * @since   1.5.0
	 * @version 1.6.0
	 *
	 * @param array   $tasks_id        L'ID des tâches parents.
	 * @param integer $offset          Le nombre de résultat à passer.
	 * @param date    $date_end        Date de début.
	 * @param date    $date_start      Date de fin.
	 * @param integer $nb_per_page     Le nombre d'élément à afficher par page.
	 * @param array   $activities_type Le type des éléments que l'on souhaite afficher.
	 *
	 * @return array            La liste des commentaires et points.
	 */
	public function get_activity( $tasks_id, $offset, $date_end = '', $date_start = '', $nb_per_page = 0, $activities_type = array( 'created-point', 'completed-point', 'created-comment' ) ) {

		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}

		if ( empty( $date_end ) ) {
			$date_end = date( 'Y-m-d', strtotime( '-1 month', strtotime( $date_start ) ) );
		}

		$query_string =
		"SELECT TASK.post_title AS t_title, TASK.ID as t_id,
 			POINT.comment_content AS point_title, POINT.comment_id AS point_id,
 			CREATED_COMMENT.comment_content AS com_title, CREATED_COMMENT.comment_id as com_id,
 			COMMENTMETA.meta_value AS com_details, CREATED_COMMENT.comment_date AS com_date,
 			CREATED_COMMENT.user_id AS com_author_id
 		FROM {$GLOBALS['wpdb']->comments} AS CREATED_COMMENT
 			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = CREATED_COMMENT.comment_id
 			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_id = CREATED_COMMENT.comment_parent
 			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_id
 		WHERE CREATED_COMMENT.comment_date >= %s
 			AND CREATED_COMMENT.comment_date <= %s
 			AND CREATED_COMMENT.comment_approved != 'trash'
 			AND TASK.ID IN( " . implode( ',', $tasks_id ) . " )
 			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )
			AND POINT.comment_approved NOT IN ( 'trash' )";

		$query_string .= ' ORDER BY CREATED_COMMENT.comment_date DESC';

		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_end . ' 00:00:00', $date_start . ' 23:59:59' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

	/**
	 * Récupères l'activité d'un utilisateur entre deux dates.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 *
	 * @param [type] $user_id     L'ID de l'utilisateur.
	 * @param string $date_end    Date de fin.
	 * @param string $date_start  Date de
	 *                            début.
	 * @param int    $customer_id id du customer.
	 *
	 * @return array
	 */
	public function display_user_activity_by_date( $user_id, $date_end = '', $date_start = '', $customer_id = 0 ) {

		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}

		$query_string =
		"SELECT TASK_PARENT.post_title as pt_title, TASK_PARENT.ID as pt_id,
 			TASK.post_title AS t_title, TASK.ID as t_id,
 			POINT.comment_content AS point_title, POINT.comment_id AS point_id,
 			COMMENT.comment_content AS com_title, COMMENT.comment_id as com_id,
 			COMMENTMETA.meta_value AS com_details, COMMENT.comment_date AS com_date,
 			COMMENT.user_id AS com_author_id
 		FROM {$GLOBALS['wpdb']->comments} AS COMMENT
 			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_id
 			INNER JOIN {$GLOBALS['wpdb']->users} AS USER ON COMMENT.user_id = USER.ID
 			INNER JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID = USERMETA.user_id AND USERMETA.meta_key = '{$GLOBALS['wpdb']->prefix}user_level'
 			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_id = COMMENT.comment_parent
 			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_id
 				LEFT JOIN {$GLOBALS['wpdb']->posts} AS TASK_PARENT ON TASK_PARENT.ID = TASK.post_parent
 		WHERE COMMENT.comment_date >= %s
 			AND USERMETA.meta_value = 10
 			AND COMMENT.comment_date <= %s
 			AND COMMENTMETA.meta_key = %s
 			AND COMMENT.comment_approved != 'trash'
 			AND POINT.comment_approved != 'trash'
 			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' ) ";

		if ( ! empty( $user_id ) ) {
			$query_string .= 'AND COMMENT.user_id = ' . $user_id . ' ';
		}

		if ( ! empty( $customer_id ) ) {
			$query_string .= 'AND TASK.post_parent = ' . $customer_id . ' ';
		}

		$query_string .= 'ORDER BY COMMENT.comment_date DESC';

		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_start . ' 00:00:00', $date_end . ' 23:59:59', 'wpeo_time' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

	/**
	 * Recupere les données principales de chaque utilisateur, et les classe par jour / taches effectuées sur une période prédéfinie
	 *
	 * @param [type] $datas      [toutes les donnes récupérées].
	 * @param [type] $user_id    [id de l'utilisateur ciblé].
	 * @param string $date_end   [date de fin].
	 * @param string $date_start [date de début].
	 * @param string $time       [choix d'affichage] 4 types => null, 'day', 'week', 'month'.
	 *
	 * @return null
	 * @since  1.9.0 - BETA
	 **/
	public function getDataChart( $datas, $user_id, $date_end = '', $date_start = '', $time = '' ) {
		$date_end = ! empty( $date_end ) ? $date_end : current_time( 'Y-m-d' );
		$date_start = ! empty( $date_start ) ? $date_start : current_time( 'Y-m-d' );

		$date_start_strtotime = strtotime( $date_start ); // @info Debut 0 à 1:00
		$date_end_strtotime   = strtotime( $date_end ) + 86400; // @info Debut à 1:00 + 24h -> Jour suivant

		$temp_month = '';

		// 86 400 = Une journée en seconde
		$date_gap = ( $date_end_strtotime - $date_start_strtotime ) / 86400; // @info Recupere le nombre jour d'ecart

		$dates   = array();
		$current = strtotime( $date_start );
		$last    = strtotime( $date_end );

		$data_monthyear_db = array();

		while ( $current <= $last ) {
			$dates[] = date( 'd-m-Y', $current );
			$current = strtotime( '+1 day', $current );
		}

		$all_days = $this->data_planning_reformat( $dates, $date_start, $date_end, $user_id );

		$data_array_return = $this->forEachDay( $date_gap, $date_start_strtotime, $all_days, $datas );
		$date_return['datatime']   = $data_array_return[0];
		$date_return['date_gap']   = $data_array_return[1];
		$date_return['date_start'] = $date_start;
		$date_return['date_end']   = $date_end;

		return $date_return;
	}

	public function data_planning_reformat( $days, $start_time_user, $end_time_user, $user_id ){

		$contracts = get_user_meta( $user_id, '_tm_planning_users_contract', true );
		// $plannings = get_user_meta( $user_id, '_tm_planning_users_indicator', true );
		$plannings = $contracts[ 'planning' ];
		$contracts = $this->define_contract_valid( $contracts, $plannings );

		$list_days = array();

		foreach ( $days as $date_day ) { // @info Recupere dans la db, le planning de la période ciblée
			$date_day_str = strtotime( $date_day );
			$end_time = strtotime( "+1 month", $date_day_str);

			$return = $this->return_day_work_minute( $date_day_str, $contracts );
			$day = array(
				'str' => $date_day_str,
				'date' => $date_day,
			  'day_type' => date( 'l', strtotime( $date_day ) ),
				'default' => $return[ 'duration' ]
			);
			$list_days[] = $day;
		}

		return $list_days;
	}

	public function return_day_work_minute( $date_day_str, $contracts ){
		$morning = $this->find_the_good_planning_for_this_date( $date_day_str, $contracts, 'morning' );
		$afternoon = $this->find_the_good_planning_for_this_date( $date_day_str, $contracts, 'afternoon' );

		$return = array(
			'day'    => date( 'd-m-Y', $date_day_str ),
			'period' => strtolower ( date( 'l', $date_day_str ) ),
			'text'   => '',
			'duration' => 0
		);

		$return[ 'duration' ] = $morning[ 'duration' ] + $afternoon[ 'duration' ];
		return $return;
	}

	public function find_the_good_planning_for_this_date( $date_day_str, $contracts, $period ){
		$info = array(
			'day'      => date( 'd-m-Y', $date_day_str ),
			'day_name' => strtolower ( date( 'l', $date_day_str ) ),
			'period'   => $period,
			'status'   => '',
			'text'     => '',
			'duration' => 0
		);

		foreach( $contracts as $contract ){
			if( $date_day_str >= $contract[ 'start_date' ] ){ // La journée est aprés le début du contrat
				if( $contract[ 'end_date_type' ] == "actual" || $date_day_str <= $contract[ 'end_date' ] ){ // Si contract actuel et date de fin aprés le jour
					$start_hour = $contract[ 'planning' ][ $info[ 'day_name' ] ][ $period ][ 'work_from' ];
					$end_hour = $contract[ 'planning' ][ $info[ 'day_name' ] ][ $period ][ 'work_to' ];

					$start_hour =	$start_hour != "" ? $this->explode_format_hour_to_minute( $start_hour ) : '0';
					$end_hour = $end_hour != "" ? $this->explode_format_hour_to_minute( $end_hour ) : '0';
					$info[ 'duration' ] = $end_hour - $start_hour > 0 ? $end_hour - $start_hour : 0;
					$info[ 'status' ] = 'success';

					return $info;
				}
			}
		}

		$info[ 'text' ] = 'No data found';
		$info[ 'status' ] = 'error';
		return $info;
	}

	public function explode_format_hour_to_minute( $time = "" ){
		$time = explode(':', $time);
		return ( $time[ 0 ] * 60 ) + ( $time[ 1 ] );
	}

	public function define_planning_valid( $actual = array(), $archive = array() ){
	/*	if( empty( $actual ) ){
			return array();
		}

		foreach( $actual as $key_d => $day ){
			foreach( $day as $key_p => $period ){
				if( ! isset( $archive[ $key_d ][ $key_p ] ) || empty( $archive[ $key_d ][ $key_p ] ) ){
					$archive[ $key_d ][ $key_p ] = array();
				}
				array_push( $archive[ $key_d ][ $key_p ], $period );
				$archive[ $key_d ][ $key_p ] = Follower_Class::g()->array_sort( $archive[ $key_d ][ $key_p ], 'day_start', SORT_DESC ); // @info Objet trié par 'date'
			}
		}

		foreach( $archive as $key_d => $day ){
			foreach( $day as $key_p => $period ){
				foreach( $period as $key_s => $settings ){
					if( ! empty( $settings ) && $settings[ 'status' ] == "delete" ){
						unset( $archive[ $key_d ][ $key_p ][ $key_s ] );
					}
				}
			}
		}

		return $archive;*/
	}

	public function define_contract_valid( $contracts, $plannings ){
		$contracts = ! empty( $contracts ) ? Follower_Class::g()->array_sort( $contracts, 'id' ) : array();

		foreach( $plannings as $planning ){
			$key = $planning[ 'info' ][ 'id' ] - 1;
			$contracts[ $key ][ 'info' ] = $planning[ 'info' ];
			$contracts[ $key ][ 'planning' ] = $planning[ 'planning' ];
		}

		foreach( $contracts as $key => $contract ){
			if( $contract[ 'status' ] == "delete" ){
				unset( $contracts[ $key ] );
			}
		}

		return $contracts;
	}

	/**
	 * Pour chaque jour d'écart entre la date de fin et la date Debut
	 * Cette fonction recupère les données et les classes par jour et par taches effectuées
	 *
	 * @param [type] $date_gap                    [ecart de jourt].
	 * @param [type] $date_start_strtotime        [date de début].
	 * @param [type] $data_planningeachmonth_user [planning de l'utilisateur] => Planning prévu, minutes de présences chaque jour.
	 * @param [type] $datas                       [liste des taches effectuées par l'utilisateur].
	 *
	 * @return [array] [0] => données utilisateurs | [1] => Jour d'écart
	 * @since  1.9.0 - BETA
	 */
	public function forEachDay( $date_gap, $date_start_strtotime, $data_planningeachday, $datas ) {
		$datatime           = [];
		$datatime_estimated = [];
		$datatime_reel      = [];

		foreach( $data_planningeachday as $data_day ){ // @info BOUCLE FOR | Pour chaque jours (intervalle choisi)
			$strtotime_int = $data_day[ 'str' ];

			$time_timestamp = $strtotime_int;
			$time           = date( 'l', $strtotime_int );

			$worktoday     = false;
			$default_value = 0;
			if( $data_day[ 'default' ] > 0 ){
				$worktoday = true;
				$default_value = $data_day[ 'default' ];
			}

			if ( ! $worktoday ) {
				continue;
			} else {

					$datatime_length = count( $datatime );

					$temp_day = strftime( '%d-%m-%Y', $strtotime_int );

					$locale = get_locale();
					$date   = new \DateTime( $temp_day );

				if ( class_exists( '\IntlDateFormatter' ) ) {
					$formatter    = new \IntlDateFormatter( $locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE );
					$date_ = $formatter->format( $date );
				}

				$datatime[ $datatime_length ]['jour'] = $date_;

				$datatime[ $datatime_length ]['strtotime']     = $strtotime_int;
				$datatime[ $datatime_length ]['duree_journée'] = $default_value;// @info Nombre de journée de travail * la durée d'une journée de travail
				$datatime[ $datatime_length ]['duree_travail'] = 0;

				$work_ = false;

				foreach ( $datas as $i => $data_user ) { // @info BOUCLE FOR EACH | Pour chaque tache effectué par l'utilisateur

					if ( strtotime( strftime( '%Y-%m-%d', strtotime( $data_user->com_date ) ) ) == $time_timestamp ) {
							$work_           = true;
							$data_comdetails = json_decode( $data_user->com_details );
							$datatime[ $datatime_length ]['duree_travail'] += $data_comdetails->time_info->elapsed;
							$datatime[ $datatime_length ]['date']           = $data_user->com_date;

						if ( empty( $datatime[ $datatime_length ]['tache_effectue'] ) || count( $datatime[ $datatime_length ]['tache_effectue'] ) == 0 ) { // @info premiere tache effectué
							$datatime[ $datatime_length ]['tache_effectue'][0]['pt_title']                   = $data_user->pt_title;
							$datatime[ $datatime_length ]['tache_effectue'][0]['pt_id']                      = $data_user->pt_id;
							$datatime[ $datatime_length ]['tache_effectue'][0]['tache_title']                = $data_user->t_title;
							$datatime[ $datatime_length ]['tache_effectue'][0]['tache_id']                   = $data_user->t_id;
							$datatime[ $datatime_length ]['tache_effectue'][0]['point_title']                = $data_user->point_title;
							$datatime[ $datatime_length ]['tache_effectue'][0]['point_id']                   = $data_user->point_id;
							$datatime[ $datatime_length ]['tache_effectue'][0]['commentary'][0]['com_title'] = $data_user->com_title;
							$datatime[ $datatime_length ]['tache_effectue'][0]['commentary'][0]['com_id']    = $data_user->com_id;
							$datatime[ $datatime_length ]['tache_effectue'][0]['commentary'][0]['com_time']  = $data_comdetails->time_info->elapsed;
							$datatime[ $datatime_length ]['tache_effectue'][0]['duree']                      = $data_comdetails->time_info->elapsed;
							$datatime[ $datatime_length ]['tache_effectue'][0]['com_date']                   = $data_user->com_date;
							$datatime[ $datatime_length ]['tache_effectue'][0]['com_author']                 = $data_user->com_author_id;
						} else {
							$datatime_tacheeffectue_length = count( $datatime[ $datatime_length ]['tache_effectue'] );
							$tache_already_exist           = false;

							for ( $u = 0; $u < $datatime_tacheeffectue_length; $u ++ ) { // @info BOUCLE FOR | Pour chaque tache effectué un jours précis

								if ( $datatime[ $datatime_length ]['tache_effectue'][ $u ]['point_id'] == $data_user->point_id ) {
									$tache_already_exist               = true;
									$length_nbrcommentary_forthispoint = count( $datatime[ $datatime_length ]['tache_effectue'][ $u ]['commentary'] );

									$datatime[ $datatime_length ]['tache_effectue'][ $u ]['duree'] += $data_comdetails->time_info->elapsed; // @info Cumul la durée
									$datatime[ $datatime_length ]['tache_effectue'][ $u ]['commentary'][ $length_nbrcommentary_forthispoint ]['com_title'] = $data_user->com_title;
									$datatime[ $datatime_length ]['tache_effectue'][ $u ]['commentary'][ $length_nbrcommentary_forthispoint ]['com_id']    = $data_user->com_id;
									$datatime[ $datatime_length ]['tache_effectue'][ $u ]['commentary'][ $length_nbrcommentary_forthispoint ]['com_time']  = $data_comdetails->time_info->elapsed;
								}
							}
							if ( ! $tache_already_exist ) {
								$temp = count( $datatime[ $datatime_length ]['tache_effectue'] );
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['pt_title']                   = $data_user->pt_title;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['pt_id']                      = $data_user->pt_id;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['tache_title']                = $data_user->t_title;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['tache_id']                   = $data_user->t_id;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['point_title']                = $data_user->point_title;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['point_id']                   = $data_user->point_id;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['commentary'][0]['com_title'] = $data_user->com_title;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['commentary'][0]['com_id']    = $data_user->com_id;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['commentary'][0]['com_time']  = $data_comdetails->time_info->elapsed;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['duree']                      = $data_comdetails->time_info->elapsed;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['com_date']                   = $data_user->com_date;
								$datatime[ $datatime_length ]['tache_effectue'][ $temp ]['com_author']                 = $data_user->com_author_id;
							}
						}
					}
				}

				if ( false == $work_ ) {
					$datatime[ $datatime_length ]['duree_travail'] = 0;
					$datatime[ $datatime_length ]['date']          = '';
				}

				$datatime[ $datatime_length ]['date_fr'] = date( 'd/m/Y', $time_timestamp );
			}
		}

		$return[0] = $datatime;
		$return[1] = $date_gap;

		return $return;
	}

}
Activity_Class::g();
