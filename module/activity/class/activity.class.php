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
 		"SELECT TASK.post_title AS T_title, TASK.ID as T_ID,
 			POINT.comment_content AS POINT_title, POINT.comment_ID AS POINT_ID,
 			CREATED_COMMENT.comment_content AS COM_title, CREATED_COMMENT.comment_ID as COM_ID,
 			COMMENTMETA.meta_value AS COM_DETAILS, CREATED_COMMENT.comment_date AS COM_DATE,
 			CREATED_COMMENT.user_id AS COM_author_id
 		FROM {$GLOBALS['wpdb']->comments} AS CREATED_COMMENT
 			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = CREATED_COMMENT.comment_ID
 			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = CREATED_COMMENT.comment_parent
 			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
 		WHERE CREATED_COMMENT.comment_date >= %s
 			AND CREATED_COMMENT.comment_date <= %s
 			AND CREATED_COMMENT.comment_approved != 'trash'
 			AND TASK.ID IN( " . implode( ',', $tasks_id ) . " )
 			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )";

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
 		"SELECT TASK_PARENT.post_title as PT_title, TASK_PARENT.ID as PT_ID,
 			TASK.post_title AS T_title, TASK.ID as T_ID,
 			POINT.comment_content AS POINT_title, POINT.comment_ID AS POINT_ID,
 			COMMENT.comment_content AS COM_title, COMMENT.comment_ID as COM_ID,
 			COMMENTMETA.meta_value AS COM_DETAILS, COMMENT.comment_date AS COM_DATE,
 			COMMENT.user_id AS COM_author_id
 		FROM {$GLOBALS['wpdb']->comments} AS COMMENT
 			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_ID
 			INNER JOIN {$GLOBALS['wpdb']->users} AS USER ON COMMENT.user_id = USER.ID
 			INNER JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID = USERMETA.user_id AND USERMETA.meta_key = 'wp_user_level'
 			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = COMMENT.comment_parent
 			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
 				LEFT JOIN {$GLOBALS['wpdb']->posts} AS TASK_PARENT ON TASK_PARENT.ID = TASK.post_parent
 		WHERE COMMENT.comment_date >= %s
 			AND USERMETA.meta_value = 10
 			AND COMMENT.comment_date <= %s
 			AND COMMENTMETA.meta_key = %s
 			AND COMMENT.comment_approved != 'trash'
 			AND POINT.comment_approved != 'trash'
 			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' ) ";

 		if ( ! empty( $user_id ) ) {
 			$query_string .= "AND COMMENT.user_id = " . $user_id . " ";
 		}

 		if ( ! empty( $customer_id ) ) {
 			$query_string .= "AND TASK.post_parent = " . $customer_id . " ";
 		}

 		$query_string .= "ORDER BY COMMENT.comment_date DESC";

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
		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}

		$date_start_strtotime = strtotime( $date_start ); // @info Debut 0 à 1:00
		$date_end_strtotime   = strtotime( $date_end ) + 86400; // @info Debut à 1:00 + 24h -> Jour suivant

		$temp_month = '';

		// 86 400 = Une journée en seconde
		$date_gap = ( $date_end_strtotime - $date_start_strtotime ) / 86400; // @info Recupere le nombre jour d'ecart

		$data_full_planning = get_user_meta( $user_id, '_tm_planning_users', true );

		$dates   = array();
		$current = strtotime( $date_start );
		$last    = strtotime( $date_end );

		$data_monthyear_db = array();

		while ( $current <= $last ) {
			if ( date( 'm', $current ) != $temp_month ) {

				$temp_month = date( 'm', $current );

				$data_monthyear_db[ count( $data_monthyear_db ) ] = array(
					'month' => date( 'm', $current ),
					'year'  => date( 'Y', $current ),
				);
			}

			$dates[] = date( 'd/m/Y', $current );
			$current = strtotime( '+1 day', $current );
		}

		$data_planningeachmonth_user = array();

		foreach ( $data_monthyear_db  as $nbr => $month ) { // @info Recupere dans la db, le planning de la période ciblée

			$data_planningeachmonth_user[ count( $data_planningeachmonth_user ) ] = get_user_meta( $user_id, '_tm_planning_' . $month['year'] . '_' . $month['month'], true );
		}

		$data_array_return = $this->forEachDay( $date_gap, $date_start_strtotime, $data_planningeachmonth_user, $datas );

		$date_return['datatime']   = $data_array_return[0];
		$date_return['date_gap']   = $data_array_return[1];
		$date_return['date_start'] = $date_start;
		$date_return['date_end']   = $date_end;

		return $date_return;
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
	public function forEachDay( $date_gap, $date_start_strtotime, $data_planningeachmonth_user, $datas ) {

		$datatime           = [];
		$datatime_estimated = [];
		$datatime_reel      = [];

		for ( $p = 0; $p < $date_gap; $p++ ) { // @info BOUCLE FOR | Pour chaque jours (intervalle choisi)
			$strtotime_int = $p * 86400;

			$time_timestamp = $date_start_strtotime + $strtotime_int;
			$time           = date( 'l', $date_start_strtotime + $strtotime_int );

			$worktoday     = false;
			$default_value = 0;
			foreach ( $data_planningeachmonth_user as $keyyear => $valueyear ) {
				$day_found = false;
				foreach ( $valueyear as $key => $value ) {

					if ( strtotime( $value['date'] ) == $time_timestamp ) {
						if ( $value['default'] > 0 ) { // @info On verifie que la personne travaille ce jour la
							$worktoday     = true;
							$default_value = $value['default'];
						}

						$day_found = true;
						break;
					}
				}

				if ( $day_found ) {
					break;
				}
			}

			if ( ! $worktoday ) {
				continue;
			} else {

					$datatime_length = count( $datatime );

					$temp_day = strftime( '%d-%m-%Y', $date_start_strtotime + $strtotime_int );

					$locale = get_locale();
					$date   = new \DateTime( $temp_day );

				if ( class_exists( '\IntlDateFormatter' ) ) {
					$formatter    = new \IntlDateFormatter( $locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE );
					$data['date'] = $formatter->format( $date );
				}

				$datatime[ $datatime_length ]['jour'] = $data['date'];

				$datatime[ $datatime_length ]['strtotime']     = $date_start_strtotime + $strtotime_int;
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
			}
		}

		$return[0] = $datatime;
		$return[1] = $date_gap;

		return $return;
	}

}
Activity_Class::g();
