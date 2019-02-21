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

		$datas = Activity_Class::g()->display_user_activity_by_date( $user_id, $date_end, $date_start );

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend/daily-activity',
			array(
				'user_id'     => $user_id,
				'customer_id' => 0,
				'date_start'  => $date_start,
				'date_end'    => $date_end,
				'datas'       => $datas,
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


}

new Indicator_Class();
