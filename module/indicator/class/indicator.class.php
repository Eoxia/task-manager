<?php
/**
 * La classe gérant Les indications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La classe gérant Les indications.
 */
class Indicator_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur de la classe
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Affiche dans la barre du menu de bootstrap, le lien de la plage des Indicator
	 *
	 * @return void
	 */
	public function callback_submenu_page() {
		$closed_meta_box = get_user_meta( get_current_user_id(), 'closedpostboxes_tache_page_task-manager-indicator' );
		$order_meta_box  = get_user_meta( get_current_user_id(), 'meta-box-order_tache_page_task-manager-indicator' );

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend/main',
			array(
				'closed_meta_box' => $closed_meta_box,
				'order_meta_box'  => $order_meta_box,
			)
		);
	}

	/**
	 * Récupère les activités journalières de l'utilisateur
	 *
	 * @return void
	 */
	public function callback_my_daily_activity() {
		$user_id    = ! empty( $_POST['user_id_selected'] ) ? (int) $_POST['user_id_selected'] : 0;
		$date_start = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );
		$date_end   = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );
		$first_load = ! empty( $_GET ) && ! empty( $_GET['first_load'] ) ? $_GET['first_load'] : false;

		$page = '';
		$customer_id = get_the_ID();

		$datas = Activity_Class::g()->display_user_activity_by_date( $user_id, $date_end, $date_start, $customer_id );

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend/daily-activity',
			array(
				'user_id'     => $user_id,
				'customer_id' => $customer_id,
				'date_start'  => $date_start,
				'date_end'    => $date_end,
				'datas'       => $datas,
				'page'        => $page
			)
		);
	}

	/**
	 * Récupère les donées utilisateurs par la base de données
	 *
	 * @return void
	 */
	public function callback_customer() {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );

		$datas    = array();
		$comments = array();

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points ) {
				if ( ! empty( $points ) ) {
					foreach ( $points as $point_id => $id ) {
						if ( ! empty( $id ) ) {
							$comments = array_merge(
								$comments,
								Task_Comment_Class::g()->get(
									array(
										'comment__in' => $id,
									)
								)
							);
						}
					}
				}
			}
		}

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				$comment->data['point'] = Point_Class::g()->get(
					array(
						'id' => $comment->data['parent_id'],
					),
					true
				);

				$comment->data['task'] = Task_Class::g()->get(
					array(
						'id' => $comment->data['post_id'],
					),
					true
				);

				$comment->data['post_parent'] = null;

				if ( ! empty( $comment->data['task']->data['parent_id'] ) ) {
					$comment->data['post_parent'] = get_post( $comment->data['task']->data['parent_id'] );
				}

				// Organisé par date pour la lecture dans le template.
				$sql_date                      = substr( $comment->data['date']['raw'], 0, strlen( $comment->data['date']['raw'] ) - 9 );
				$time                          = substr( $comment->data['date']['raw'], 11, strlen( $comment->data['date']['raw'] ) );
				$datas[ $sql_date ][ $time ][] = $comment;
			}
		}

		krsort( $datas );

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend/request',
			array(
				'datas' => $datas,
			)
		);
	}

	/**
	 * Supprimes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since 1.3.0
	 * @version 1.3.0
	 *
	 * @param integer $id L'ID du commentaire.
	 */
	public function remove_entry_customer_ask( $id ) {
		\eoxia\LOG_Util::log( '------------------------------------------------------------------------------------------------', 'task-manager' );
		$ids     = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		$comment = Task_Comment_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);
		if ( 0 === $comment->data['id'] ) {
			return false;
		}
		$comment_found_in = array(
			'task_id'  => 0,
			'point_id' => 0,
		);

		/* translators: */
		\eoxia\LOG_Util::log( sprintf( __( 'Current support request list: %s', 'task-manager' ), wp_json_encode( $ids ) ), 'task-manager' );

		/* translators: */
		\eoxia\LOG_Util::log( sprintf( __( 'Comment for removing in request %s', 'task-manager' ), wp_json_encode( $comment ) ), 'task-manager' );
		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points_ids ) {
				if ( ! empty( $points_ids ) ) {
					foreach ( $points_ids as $point_id => $comments_ids ) {
						$key = array_search( $comment->data['id'], $comments_ids, true );
						if ( false !== $key ) {
							array_splice( $ids[ $task_id ][ $point_id ], $key, 1 );
						}
					}
				}
			}
		}
		update_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, $ids );
	}

	// ------------ INDICATOR PAGE --------------------------

	/**
	 * Appel la vue indicator
	 *
	 * @returnvoid
	 */
	public function callable_indicator_page() {

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator/indicator-main',
			array()
		);
	}

	/**
	 * Recharge la page des indicators
	 *
	 * @return void
	 */
	public function callback_load_indicator_page() {

		$date_start = date( 'Y-m-d' );
		$date_end   = date( 'Y-m-d' );

		$followers          = $this->ajax_load_followers();
		$selected_followers = [];

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator/indicator-metabox-main',
			array(
				'followers'  => $followers,
				'date_start' => $date_start,
				'date_end'   => $date_end,
			)
		);
	}

	public function callback_load_tag_page() {

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator-tag/main',
			array()
		);
	}

	/**
	 * [ajax_load_followers description]
	 *
	 * @return [type] [description]
	 */
	public function ajax_load_followers() {

		$followers = Follower_Class::g()->get(
			array(
				'role' => array(
					'administrator',
				),
			)
		);

		return $followers;
	}

	public function callback_load_client_page( $month = array() ) {
		$reload = false;

		if( empty( $month ) ){
			$month = array(
				'value'     => date( 'F' ),
				'start'     => date( 'Y-m-01' ), // premier jour du mois actuel
				'start_str' => strtotime( date( 'Y-m-01' ) ), // 00h00min00sec
				'end'       => date( 'Y-m-t' ), // dernier jour du mois actuel
				'end_str'   => strtotime( date( 'Y-m-t' ) ) + 86399 // 23h59min59sec
			);
		}else{
			$reload = true;
		}

		/*$tasks = Task_Class::g()->get_tasks(
			array(
				'post__in' => Task_Class::g()->get_task_with_history_time(),
			)
		);*/

		$tasks = get_posts( array(
			'post_type' => Task_Class::g()->get_type(),
			'posts_per_page' => -1,
			'post_status'    => array(
				'any',
				'archive',
			),
		));

		$parent = array();

		foreach( $tasks as $key => $task ){ // on garde que les taches qui ont un parent
			if( ! isset( $task->post_parent ) || $task->post_parent == 0 ){
				unset( $tasks [ $key ] );
			}else{
				$comment = get_comments( array(
					'post_id' => $task->ID,
					'type' => 'history_time',
					'number' => 1
				));

				$type = "";
				if(  ! empty( $comment ) ){
					$type = get_comment_meta( $comment[0]->comment_ID, '_tm_custom', true );
				}

				if( $type != 'recursive') {
					unset( $tasks [ $key ] );
				}else{

					if( ! isset( $parent[ $task->post_parent ] ) ){
						$task_parent = get_post( $task->post_parent );

						$parent[ $task->post_parent ] = array(
							'id' => $task->post_parent,
							'name' => $task_parent->post_title,
							'estimated' => 0,
							'elapsed'   => 0,
							'categorie' => array()
						);
					}

					$task_categories = wp_get_object_terms( $task->ID, 'wpeo_tag', true ); // Liste des catégories

					$history_time = get_comment_meta( $comment[0]->comment_ID, 'wpeo_history_time', true );
					$history_time_decode = json_decode( $history_time );

					if( ! empty( $task_categories ) ){
						foreach ( $task_categories as $key_cat => $categorie ) {
							$id_category = $categorie->term_id;
							if( empty( $parent[ $task->post_parent ][ 'categorie'][ $id_category ] ) ){
								$parent[ $task->post_parent ][ 'categorie' ][ $id_category ][ 'info' ] = $categorie->name;
								$parent[ $task->post_parent ][ 'categorie' ][ $id_category ][ 'elapsed' ] = 0;
								$parent[ $task->post_parent ][ 'categorie' ][ $id_category ][ 'estimated' ] = 0;
							}

							$parent[ $task->post_parent ][ 'categorie' ][ $id_category ][ 'task' ][] = $this->generate_data_indicator_client( $task, $month, $history_time_decode );
						}
					}else{
						$parent[ $task->post_parent ][ 'categorie' ][ 0 ][ 'elapsed' ]   = 0;
						$parent[ $task->post_parent ][ 'categorie' ][ 0 ][ 'estimated' ] = 0;
						$parent[ $task->post_parent ][ 'categorie' ][ 0 ][ 'info' ]      = 'No categorie';
						$parent[ $task->post_parent ][ 'categorie' ][ 0 ][ 'task' ][]    = $this->generate_data_indicator_client( $task, $month, $history_time_decode );
					}
				}
			}
		}


		$customers = $this->update_data_indicator_humanreadable( $parent );

		$customers = Follower_Class::g()->array_sort( $customers, 'time_percent', SORT_DESC );

		if( $reload ){
			return $customers;
		}
		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-stats/indicator-main',
			array(
				'date'  => $month,
				'customers' => $customers,
				'element' => 'recursive'
			)
		);
	}



	public function generate_data_indicator_client( $task, $month, $history_time ){

		$task_data = array();
		$categories_indicator_info = array();

		if( empty( $task ) ){ return array(); }

		if( strtotime( $task->post_date ) < $month[ 'end_str' ] && strtotime( 'now' ) >= $month[ 'start_str' ] ){
			$task_data = array(
				'id'        => $task->ID,
				'elapsed'   => 0,
				'estimated' => $history_time->estimated_time
			);
		}

		$comments = Task_Comment_Class::g()->get_comments( 0, array( 'post_id' => $task->ID	) );
		if( empty ( $comments ) ){ return array(); }

		foreach ( $comments as $key => $value_com ) {
			if( $month[ 'start_str' ] < strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) && $month[ 'end_str' ] > strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) )
			{
				$task_data[ 'elapsed' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
			}
		}

		return $task_data;
	}

	public function update_data_indicator_humanreadable( $data ){

		foreach( $data as $key_client => $value_client ){
			$client_elapsed = 0;
			$client_estimated = 0;

			foreach( $value_client[ 'categorie' ] as $key_categorie => $value_categorie ){ // All categories
				$categorie_elapsed = 0;
				$categorie_estimated = 0;

				foreach( $value_categorie[ 'task' ] as $key_task => $value_task ){
					if( isset( $value_task[ 'elapsed' ] ) ){
						$categorie_elapsed   += $value_task[ 'elapsed' ];
					}

					if( isset( $value_task[ 'estimated' ] ) ){
						$categorie_estimated += $value_task[ 'estimated' ];
					}
				}

				$temp_categorie = array(
					'elapsed'                 => $categorie_elapsed,
					'estimated'               => $categorie_estimated,
					'time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $categorie_elapsed ),
					'time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $categorie_estimated ),
					'time_percent'            => 0
				);

				if( $categorie_elapsed > 0 && $categorie_estimated > 0 ){
					$temp_categorie[ 'time_percent' ] = Task_Class::g()->percent_indicator_client( $categorie_elapsed, $categorie_estimated, 0, 'recursive' );
					$temp_categorie[ 'icon' ] = $this->percent_display_icon( $temp_categorie[ 'time_percent' ] );
				}
				$data[ $key_client ][ 'categorie' ][ $key_categorie ] = array_merge( $value_categorie, $temp_categorie );

				$client_elapsed += $data[ $key_client ][ 'categorie' ][ $key_categorie ][ 'elapsed' ];
				$client_estimated += $data[ $key_client ][ 'categorie' ][ $key_categorie ][ 'estimated' ];
			}

			$temp_client = array(
				'elapsed'                 => $client_elapsed,
				'estimated'               => $client_estimated,
				'time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $client_elapsed ),
				'time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $client_estimated ),
				'time_percent'            => 0
			);

			if( $client_elapsed > 0 && $client_estimated > 0 ){
				$temp_client[ 'time_percent' ] = Task_Class::g()->percent_indicator_client( $client_elapsed, $client_estimated, 0, 'recursive' );
				$temp_client[ 'icon' ] = $this->percent_display_icon( $temp_client[ 'time_percent' ] );
				$data[ $key_client ] = array_merge( $data[ $key_client ], $temp_client );
			}else{
				unset( $data[ $key_client ], $key_client );
			}
		}

		return $data;
	}

	public function percent_display_icon( $percent = 0 ){
		$icon = '';
		if( $percent <= 50 ){
			$icon = 'smile-beam';
		}else if( $percent <= 100 ){
			$icon = 'smile';
		}else{
			$icon = 'angry';
		}

		return $icon;
	}

	public function callback_load_client_deadline( $month ){
		$tasks = Task_Class::g()->get_tasks(
			array(
				'post_parent' => 0,
				'status'      => 'publish,pending,draft,future,private,inherit,private'
			)
		);

		$parent = array();

		foreach( $tasks as $key => $task ){ // on garde que les taches qui ont un parent
			if( ! isset( $task->data[ 'parent_id' ] ) || $task->data[ 'parent_id' ] == 0 || $task->data['last_history_time']->data['custom'] != 'due_date' ){
				unset( $tasks [ $key ] );
			}else{
				if( ! isset( $parent[ $task->data[ 'parent_id' ] ] ) ){
					$parent[ $task->data[ 'parent_id' ] ] = array(
						'id' => $task->data[ 'parent_id' ],
						'name' => $task->data[ 'parent' ]->post_title,
						'estimated' => 0,
						'elapsed'   => 0,
						'categorie' => array()
					);
				}

				if( $task->data['taxonomy'][ 'wpeo_tag' ] ){
					foreach ( $task->data['taxonomy'][ 'wpeo_tag' ] as $id_category ) {
						if( empty( $parent[ $task->data[ 'parent_id' ] ][ 'categorie'][ $id_category ] ) ){
							$name_categories = get_term_by( 'id', $id_category, 'wpeo_tag' );
							$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ $id_category ][ 'info' ]      = $name_categories->name;
							$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ $id_category ][ 'elapsed' ]   = 0;
							$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ $id_category ][ 'estimated' ] = 0;
						}
						$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ $id_category ][ 'task' ][] = $this->generate_data_indicator_client_deadline( $task, $month );
					}
				}else{
					$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ 0 ][ 'elapsed' ]   = 0;
					$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ 0 ][ 'estimated' ] = 0;
					$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ 0 ][ 'info' ]      = 'No categorie';
					$parent[ $task->data[ 'parent_id' ] ][ 'categorie' ][ 0 ][ 'task' ][]    = $this->generate_data_indicator_client_deadline( $task, $month );
				}
			}
		}

		$customers = $this->update_data_indicator_humanreadable( $parent );

		$customers = Follower_Class::g()->array_sort( $customers, 'time_percent', SORT_DESC );

		return $customers;
	}

	public function generate_data_indicator_client_deadline( $task, $month ){

		$task_data = array();
		$categories_indicator_info = array();

		if( empty( $task ) ){ return array(); }

		$task_data = array(
			'id'        => $task->data[ 'id' ],
			'elapsed'   => $task->data[ 'time_info' ][ 'elapsed' ],
			'estimated' => $task->data[ 'last_history_time' ]->data[ 'estimated_time' ]
		);

		return $task_data;
	}

	public function generate_data_indicator_tag( $tasks, $year = 0 ){

		if( $year == 0 ){
			$indicator_date_start = strtotime( "-1 year" );
			$indicator_date_end = strtotime( "now" ) + 3600;
			$year = date( 'Y' );
		}else{
			$indicator_date_start = strtotime( '01-01-' . $year ) - 3600;
			$indicator_date_end  = strtotime( '31-12-' . $year );
		}

		$allmonth_betweendates = Task_Class::g()->all_month_between_two_dates( $indicator_date_start, $indicator_date_end, true );
		$return = $this->generate_data_indicator_tag_stats( $tasks, $allmonth_betweendates );
		$client_indicator = isset( $return[ 'data' ] ) ? $return[ 'data' ] : array(); // Data principal
		$client_info = isset( $return[ 'info' ] ) ? $return[ 'info' ] : array(); // Info

		$return = $this->update_data_indicator_tag_humanreadable( $client_indicator, $client_info );
		$client_indicator = $return[ 'data' ]; // Data principal
		$client_info = $return[ 'info' ]; // Info

		return array( 'type' => $client_indicator, 'info' => $client_info, 'everymonth' => $allmonth_betweendates, 'year' => $year );
	}

	public function generate_data_indicator_tag_stats( $tasks, $allmonth ){
		if( empty( $tasks ) ){
			return array();
		}

		$client_indicator = array();
		$client_indicator_info = array();
		foreach ( $tasks as $key => $task ){ // Pour chaque tache
			$type = '';

			// On definie le type pour créer un tableau avec deux élements
			if( $task->data['last_history_time']->data['custom'] == 'recursive' ){ // Si recursif => $clients_indicator_recursive
				$type = 'recursive';
			}else if( $task->data['last_history_time']->data['custom'] == 'due_date' ){ // Si Deadline => $clients_indicator_deadline
				$type = 'deadline';
			}else{ // Si aucun type => RIEN
				continue;
			}

			// echo '<pre>'; print_r( ' -> P : ' . $task->data[ 'parent_id' ] . ' <- -> T : ' . $task->data[ 'id' ] ); echo '</pre>';

			$client_info = array();
			if( $task->data[ 'parent_id' ] == 0 ){
				$id_client = 0;
				$name_client = __( 'No Client', 'task-manager' );
			}else{
				$id_client = $task->data[ 'parent_id' ];
				$name_client = $task->data[ 'parent' ]->post_title;
			}

			if( empty( $client_indicator[ $type ][ $id_client ] ) ) { // On créait le client
				$client_indicator[ $type ][ $id_client ] = $allmonth; // tous les mois de l'année
				$client_info = array(
					'name'                    => $name_client, // Info
					'id'                      => $id_client, // de base
					'type'                    => $type, // De la catégorie
					'time_elapsed'            => 0,
					'time_estimated'          => 0,
					'time_elapsed_readable'   => '',
					'time_estimated_readable' => '',
					'time_percent'            => 0,
				);
			}

			$deadline_task = $task->data[ 'last_history_time' ]->data[ 'due_date' ][ 'rendered' ][ 'mysql' ];
			$history_task = $task->data[ 'last_history_time' ]->data;
			if( $type === 'recursive' ){ // Si la tache est récursive, on ajoute du temps chaque mois
				$time_history = $this->get_task_estimated_time( $task->data[ 'id' ], $type );
				foreach( $client_indicator[ $type ][ $id_client ] as $key_categorie => $month ){ // Pour chaque tache, Chaque mois de l'année
					$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ] = Task_Class::g()->return_array_indicator_tasklist();
					if( strtotime( $task->data['date'][ 'rendered' ][ 'mysql' ] ) < $month[ 'str_month_end' ] && strtotime( 'now' ) >= $month[ 'str_month_start' ] ){
						$estimated_time = $this->get_estimated_time( $time_history, $month );
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] += $estimated_time;
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] = 1;
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'month_is_valid' ] = 1;

						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] += $estimated_time;

						if( isset( $client_indicator[ $type ][ $id_client ][ $key_categorie - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] ) ){
							$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ] += $client_indicator[ $type ][ $id_client ][ $key_categorie - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated_monthly' ];
						}
					}
				}

			}else if( $type === 'deadline' && ( isset( $deadline_task ) || strtotime( $deadline_task ) > 0 ) ){ // Task deadline
				foreach( $client_indicator[ $type ][ $id_client ] as $key_categorie => $month ){ // Pour chaque tache, Chaque mois de l'année
					$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ] = Task_Class::g()->return_array_indicator_tasklist();

					if( strtotime( $task->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) < $month[ 'str_month_end' ] && strtotime( $deadline_task ) > $month[ 'str_month_start' ] ){ // Mois creer avant la fin du mois et Deadline aprés le debut du mois => Mois valide
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated'] = $history_task['estimated_time'];
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] = 1;
						$client_indicator[ $type ][ $id_client ][ $key_categorie ][ 'month_is_valid' ] = 1;
					}
				}

			}else{
				// Normalement cette condition ne doit pas etre accessible
				continue;
			}

			if( ! empty( $client_info ) ){
				$client_indicator_info[ $type ][ $id_client ][ 'info' ] = $client_info;
			}

			$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'id' ] = $task->data[ 'id' ];
			$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'title' ] = $task->data[ 'title' ];
			$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] = 0;
			$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] = 0;
			// On recupere les commentaires et on les ajoute dans leurs mois respectifs
			$comments = Task_Comment_Class::g()->get_comments( 0, array( 'post_id' => $task->data[ 'id' ]	) );
			if( empty ( $comments ) ){
				continue;
			}

			$first_month = true;
			foreach( $client_indicator[ $type ][ $id_client ] as $key_month_ => $month ){ // Pour chaque mois de l'année, on va check les commentaires
				//if( $month[ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ){ // On verifie que le mois soit valide
					if( $key_month_ == 0 && $type === 'deadline' ){
						// Si le premier mois est valide, on récupère le temps des commentaires des mois précédents
						foreach ( $comments as $key => $value_com ) {
							if( $month[ 'str_month_start' ] > strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ){
								$client_indicator[ $type ][ $id_client ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
								$client_indicator[ $type ][ $id_client ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
							}
						}
					}

				if( isset( $client_indicator[ $type ][ $id_client ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'month_is_valid' ] ) ){
						$client_indicator[ $type ][ $id_client ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] = $client_indicator[ $type ][ $id_client ][ $key_month_ - 1 ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ];
					}

					foreach ( $comments as $key => $value_com ) { // Pour chaque commentaire
						if( $month[ 'str_month_start' ] < strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) && $month[ 'str_month_end' ] > strtotime( $value_com->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ) // Si le commentaire a était fait dans le mois
						{
							$client_indicator[ $type ][ $id_client ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];
							$client_indicator[ $type ][ $id_client ][ $key_month_ ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_deadline' ] += $value_com->data[ 'time_info' ][ 'elapsed' ];

							$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_elapsed' ] = 0;
							$client_indicator_info[ $type ][ $id_client ][ 'task_list' ][ $task->data[ 'id' ] ][ 'time_estimated' ] = 0;
						}
					}
				//}
			}
		}

		if( ! empty( $client_indicator ) ){
			foreach( $client_indicator as $key => $value ){
				$return = Task_Class::g()->update_indicator_array_tasklist( $client_indicator[ $key ], $client_indicator_info[ $key ] );
				$client_indicator[ $key ]      = $return[ 'categories' ];
				$client_indicator_info[ $key ] = $return[ 'info' ];
			}
		}

		return array( 'data' => $client_indicator, 'info' => $client_indicator_info );
	}

	public function get_task_estimated_time( $id, $type ){
		$times_saves = History_Time_Class::g()->get(
			array(
				'post_id' => $id,
				'order' => 'ASC',
				'type'    => History_Time_Class::g()->get_type(),
			)
		);

		$valid_times = array();
		foreach( $times_saves as $key => $time ){
			if( $time->data[ 'custom' ] == $type ){
				$month = date( 'm', strtotime( $time->data[ 'due_date' ][ 'rendered' ][ 'mysql' ] ) );
				$year = date( 'Y', strtotime( $time->data[ 'due_date' ][ 'rendered' ][ 'mysql' ] ) );
				$addtime = array(
					'month'     => $month,
					'year'      => $year,
					'type'      => $type,
					'timestr'   => strtotime( date( 'd-m-Y', strtotime( $time->data[ 'due_date' ][ 'rendered' ][ 'mysql' ] ) ) ),
					'estimated' => $time->data[ 'estimated_time' ]
				);
				$valid_times[ $year . '-' . $month ] = $addtime;
			}
		}

		return $valid_times;
	}

	public function get_estimated_time( $validtimes = array(), $month = array() ){
		if( empty( $validtimes ) ){
			return 0;
		}

		$valid_estimated = 0;
		$lasttime = 0;

		foreach( $validtimes as $key => $time ){
			//if( $time[ 'timestr' ] < $month[ 'str_month_end' ] ){ // 11/07/2019 On vérifie plus la date d'ajout de la récusivité
			$valid_estimated = $time[ 'estimated' ];
			break;
			//}
			$lasttime = $time[ 'estimated' ];
		}

		return $valid_estimated;
	}

	public function update_data_indicator_tag_humanreadable( $clients, $info ){
		foreach( $clients as $key_type => $value_type ){ // Deadline / recusive
			foreach( $value_type as $key_client => $value_client ){ // All clients
				foreach( $value_client as $key_month => $value_month ){
					$temp_month = array(
						'total_time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $value_month[ 'total_time_elapsed' ] ),
						'total_time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $value_month[ 'total_time_estimated' ] ),
						'total_time_deadline_readable'  => Task_Class::g()->change_minute_time_to_readabledate( $value_month[ 'total_time_deadline' ] ),
						'total_time_percent'            => Task_Class::g()->percent_indicator_client( $value_month[ 'total_time_elapsed' ], $value_month[ 'total_time_estimated' ], $value_month[ 'total_time_deadline' ], $key_type )
					);

					$clients[ $key_type ][ $key_client ][ $key_month ] = array_merge( $value_month, $temp_month);

					foreach( $value_month[ 'task_list' ] as $key_task => $value_task ){
						$temp_task = array(
							'time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $value_task[ 'time_elapsed' ] ),
							'time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $value_task[ 'time_estimated' ] ),
							'time_deadline_readable'  => Task_Class::g()->change_minute_time_to_readabledate( $value_task[ 'time_deadline' ] ),
							'time_percent'            => Task_Class::g()->percent_indicator_client( $value_task[ 'time_elapsed' ], $value_task[ 'time_estimated' ], $value_task[ 'time_deadline' ], $key_type )
						);

						$clients[ $key_type ][ $key_client ][ $key_month ][ 'task_list' ][ $key_task ] = array_merge( $value_task, $temp_task);

						$info_task = $info[ $key_type ][ $key_client ][ 'task_list' ][ $key_task ];
						$temp_task_info = array(
							'time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $info_task[ 'time_elapsed' ] ),
							'time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $info_task[ 'time_estimated' ] ),
							'time_percent'            => Task_Class::g()->percent_indicator_client( $info_task[ 'time_elapsed' ], $info_task[ 'time_estimated' ], 0, 'recursive' )
						);

						$info[ $key_type ][ $key_client ][ 'task_list' ][ $key_task ] = array_merge( $info_task, $temp_task_info);

					}
				}

				$temp_info = array(
					'time_elapsed_readable'   => Task_Class::g()->change_minute_time_to_readabledate( $info[ $key_type ][ $key_client ][ 'info' ][ 'time_elapsed' ] ),
					'time_estimated_readable' => Task_Class::g()->change_minute_time_to_readabledate( $info[ $key_type ][ $key_client ][ 'info' ][ 'time_estimated' ] ),
					'time_percent'            => Task_Class::g()->percent_indicator_client( $info[ $key_type ][ $key_client ][ 'info' ][ 'time_elapsed' ], $info[ $key_type ][ $key_client ][ 'info' ][ 'time_estimated' ], 0, 'recursive' )
				);

				$info[ $key_type ][ $key_client ][ 'info' ] = array_merge( $info[ $key_type ][ $key_client ][ 'info' ], $temp_info );

			}
		}
		return array( 'data' => $clients, 'info' => $info );
	}

	public function sort_indicator_by_name( $data, $info ){
		$data_order = array();
		foreach( $info as $keyclient => $client ){
			$name = trim( strtolower( $client[ 'info' ][ 'name' ] ) );
			$data_array = array( 'id' => $client[ 'info' ][ 'id' ], 'name' => $name	);
			if( empty( $data_order ) ){
				$data_order[] = $data_array;
			}else{
				$elementisadd = false;
				foreach( $data_order as $key => $element ){
					if( strcmp( $element[ 'name' ], $name ) > 0 ){ // > 0 Si priemer element est aprés dans l'alphabet
						array_splice( $data_order, $key, 0, array( $data_array ) );
						$elementisadd = true;
						break;
					}
				}
				if( ! $elementisadd ){
					$data_order[] = $data_array;
				}
			}
		}

		$return = array();
		foreach( $data_order as $key => $value ){
			$return[ strval( $value[ 'id' ] ) ] = $data[ $value[ 'id' ] ];
		}

		return $return;
	}

	public function sort_indicator_by_percent( $data, $info, $sortby = "ASC" ){
		$data_order = array();
		foreach( $info as $keyclient => $client ){
			$data_array = array( 'id' => $client[ 'info' ][ 'id' ], 'percent' => $client[ 'info' ][ 'time_percent' ]	);
			if( empty( $data_order ) ){
				$data_order[] = $data_array;
			}else{
				$elementisadd = false;
				foreach( $data_order as $key => $element ){
					if( $sortby == "ASC" ){
						if( $element[ 'percent' ] > $data_array[ 'percent' ] ){
							array_splice( $data_order, $key, 0, array( $data_array ) );
							$elementisadd = true;
							break;
						}
					}else{
						if( $element[ 'percent' ] < $data_array[ 'percent' ] ){
							array_splice( $data_order, $key, 0, array( $data_array ) );
							$elementisadd = true;
							break;
						}
					}
				}
				if( ! $elementisadd ){
					$data_order[] = $data_array;
				}
			}
		}

		$return = array();
		foreach( $data_order as $key => $value ){
			$return[ strval( $value[ 'id' ] ) ] = $data[ $value[ 'id' ] ];
		}

		return $return;
	}
}

new Indicator_Class();
