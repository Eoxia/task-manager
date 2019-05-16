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

		// Récup les tâches
		// Pour chaque tâche récupére la dernière history time
		// Je t'emmerde
		// Pour chaque history time Récup la métadonnée _tm_custom

		// $histories_times
		//
		// get_post_meta( $id_history, '_tm_custom', true );
		//
		// var_dump( Task_Class::g()->get_type());
		//

		// $tasks = Task_Class::g()->get_tasks(
		// 	array(
		// 		'post__in' => Task_Class::g()->get_task_with_history_time(),
		// 	)
		// );
		//
		// $parent = array();
		//

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
}

new Indicator_Class();
