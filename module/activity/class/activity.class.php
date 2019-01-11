<?php
/**
 * Gestion des activitées.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
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
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Récupères les commentaires et les points dans l'ordre de date décroissante.
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 *
	 * @param array   $tasks_id        L'ID des tâches parents.
	 * @param integer $offset          Le nombre de résultat à passer.
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

		$query_string .= "ORDER BY CREATED_COMMENT.comment_date DESC";


		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_end . ' 00:00:00', $date_start . ' 23:59:59' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

	/**
	 * Récupères l'activité d'un utilisateur entre deux dates.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param  integer $user_id    L'ID de l'utilisateur.
	 * @param  string  $date_end Date de fin.
	 * @param  string  $date_start Date de début.
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

	public function get_data_chart( $datas, $date_end = '', $date_start = '', $time = '', $user_id, $display_specific_week ) {


		$datatime = [];
		$datatime_estimated = [];
		$datatime_reel = [];

		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}

		$date_start_strtotime = strtotime( $date_start ); // Debut 0 à 1:00
		$date_end_strtotime   = strtotime( $date_end ) + 86400; // Debut à 1:00 + 24h -> Jour suivant

		//86 400 = Une journée en seconde
		$date_gap = ( $date_end_strtotime - $date_start_strtotime ) / 86400; // Recupere le nombre jour d'ecart

		$data_full_planning = get_user_meta( $user_id, '_tm_planning_users', true );

		$dates = array();
    $current = strtotime( $date_start );
    $last = strtotime( $date_end );

		$temp_year = '';
		$temp_month = '';

		$data_monthyear_db = array();

    while( $current <= $last ) {
			if( date( 'm', $current ) != $temp_month ){

				$temp_month = date( 'm', $current );

				$data_monthyear_db[ count( $data_monthyear_db ) ] = array(
					'month' => date( 'm', $current ),
 					'year' => date( 'Y', $current )
				);
			}

      $dates[] = date( 'd/m/Y', $current );
      $current = strtotime( '+1 day', $current );
    }

		foreach ( $data_monthyear_db  as $nbr => $month ) { // Recupere dans la db, le planning de la période ciblée

			$data_planningeachmonth_user[ count( $data_planningeachmonth_user ) ] = get_user_meta( $user_id, '_tm_planning_' . $month['year'] . '_' . $month['month'], true );
		}

		for( $p = 0; $p < $date_gap; $p++ ){ // BOUCLE FOR | Pour chaque jours (intervalle choisi)
			$strtotime_int = $p * 86400;


			$time_timestamp = $date_start_strtotime + $strtotime_int;
			$time = date("l", $date_start_strtotime + $strtotime_int);

			$worktoday = false;
			$default_value = 0;


			foreach( $data_planningeachmonth_user as $keyyear => $valueyear ){
				$day_found = false;
				foreach ( $valueyear as $key => $value) {
					if( $time_timestamp == strtotime( $value['date'] ) ){
						if( $value['default'] > 0 ){ // On verifie que la personne travaille ce jour la
							$worktoday = true;
							$default_value = $value['default'];
						}
						$day_found = true;
						break;
					}
				}

				if( $day_found ){
					break;
				}
			}

			if( ! $worktoday ){
				continue;
			}else{

				$datatime_length = count( $datatime );

				$temp_day = strftime( '%d-%m-%Y', $date_start_strtotime + $strtotime_int );

				$locale = get_locale();
				$date   = new \DateTime( $temp_day );

				$data['mysql']   = $current_time;
				$data['iso8601'] = mysql_to_rfc3339( $current_time );

				if ( class_exists( '\IntlDateFormatter' ) ) {
					$formatter    = new \IntlDateFormatter( $locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE );
					$data['date'] = $formatter->format( $date );
				}

				$datatime[ $datatime_length ][ 'jour' ] = $data['date'];

				$datatime[ $datatime_length ][ 'strtotime' ] = $date_start_strtotime + $strtotime_int;
				$datatime[ $datatime_length ][ 'duree_journée' ] = $default_value;// Nombre de journée de travail * la durée d'une journée de travail
				$datatime[ $datatime_length ][ 'duree_travail' ] = 0;

				$work_ = false;

				foreach ($datas as $i => $data_user) { // BOUCLE FOR EACH | Pour chaque tache effectué par l'utilisateur

					if( strtotime( strftime( '%Y-%m-%d', strtotime( $data_user->COM_DATE ) ) ) == $time_timestamp ){
						$work_ = true;

						$data_comdetails = json_decode( $data_user->COM_DETAILS );
						$datatime[ $datatime_length ][ 'duree_travail' ] += $data_comdetails->time_info->elapsed;
						$datatime[ $datatime_length ][ 'date' ]          = $data_user->COM_DATE;


						if( count( $datatime[ $datatime_length ][ 'tache_effectue' ] ) == 0 ){
							$temp = count( $datatime[ $datatime_length ][ 'tache_effectue' ] );
							$datatime[ $datatime_length ][ 'tache_effectue' ][ $temp ]['point_id'] = $data_user->POINT_ID;
							$datatime[ $datatime_length ][ 'tache_effectue' ][ $temp ]['duree'] = $data_comdetails->time_info->elapsed;
						}else{
							$datatime_tacheeffectue_length = count( $datatime[ $datatime_length ][ 'tache_effectue' ] );
							$tache_already_exist = false;

							for( $u = 0; $u < $datatime_tacheeffectue_length; $u ++ ) { // BOUCLE FOR | Pour chaque tache effectué un jours précis

								if( $datatime[ $datatime_length ][ 'tache_effectue' ][ $u ]['point_id'] == $data_user->POINT_ID ){
									$tache_already_exist = true;
									$datatime[ $datatime_length ][ 'tache_effectue' ][ $u ]['duree'] += $data_comdetails->time_info->elapsed;
								}
							}
							if( !$tache_already_exist ){
								$temp = count( $datatime[ $datatime_length ][ 'tache_effectue' ] );
								$datatime[ $datatime_length ][ 'tache_effectue' ][ $temp ]['point_id'] = $data_user->POINT_ID;
								$datatime[ $datatime_length ][ 'tache_effectue' ][ $temp ]['duree'] = $data_comdetails->time_info->elapsed;
							}
						}
					}
				}

				if( $work_ == false ){
					$datatime[ $datatime_length ][ 'duree_travail' ] = 0;
					$datatime[ $datatime_length ][ 'date' ]          = '';
				}
			}
		}

		$date_return['datatime']   = $datatime;
    $date_return['date_gap']   = $date_gap;
		$date_return['date_start'] = $date_start;
		$date_return['date_end']   = $date_end;

		return $date_return;
	}
}

Activity_Class::g();
