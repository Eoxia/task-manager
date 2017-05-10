<?php
/**
 * Task manager actions
 *
 * @package TaskManager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'taskmanager_action_01' ) ) {

	/**
	 * Task manager actions
	 */
	class taskmanager_action_01 {

		/**
		 * Instanciation des actions de task manager
		 */
		public function __construct() {
			add_action( 'wp_ajax_search', array( $this, 'ajax_search' ) );

			add_action( 'admin_bar_menu', array( $this, 'callback_admin_bar_menu' ), 106 );

			add_action( 'wp_ajax_open_popup_last_wpshop_customer_ask', array( $this, 'callback_open_popup_last_wpshop_customer_ask' ) );
			add_action( 'wp_ajax_open_popup_last_wpshop_customer_comment', array( $this, 'callback_open_popup_last_wpshop_customer_comment' ) );
		}

		/**
		 * Recherche
		 */
		public function ajax_search() {
			global $wpdb;

			$search = like_escape( $_REQUEST['term'] );

			$post_type = ! empty( $_REQUEST['post_type'] ) ? ' AND post_type="' . $_REQUEST['post_type'] . '" ' : '';

			$query = 'SELECT ID, post_title FROM ' . $wpdb->posts . ' as P
			WHERE ID LIKE \'%' . $search . '%\' OR
			post_title LIKE \'%' . $search . '%\' OR
			post_name LIKE \'%' . $search . '%\' ' . $post_type . '
			ORDER BY post_title ASC LIMIT 0,5';

			$return = array();

			foreach ( $wpdb->get_results( $query ) as $row ) { // WPCS: unprepared sql.
				$return[] = array(
					'label' => $row->post_title,
					'value' => $row->post_title,
					'id'	=> $row->ID,
				);
			}
			wp_die( wp_json_encode( $return ) );
		}

		/**
		 * Construction de la requête permettant de récupèrer la liste des
		 *
		 * @param	string $where_clause The different fields to get from database.
		 *
		 * @return string The db query to launch
		 */
		public function get_new_ask_query( $where_clause ) {
			$query = "SELECT {$where_clause}
			FROM {$GLOBALS['wpdb']->comments} AS POINT
				JOIN {$GLOBALS['wpdb']->posts} AS TASK ON POINT.comment_post_id=TASK.id
				JOIN {$GLOBALS['wpdb']->postmeta} AS TASKMETA ON TASKMETA.post_id=TASK.id
				JOIN {$GLOBALS['wpdb']->commentmeta} AS POINTMETA ON POINT.comment_ID=POINTMETA.comment_id
				JOIN {$GLOBALS['wpdb']->users} AS USER ON POINT.user_id=USER.ID
				JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID=USERMETA.user_id
			WHERE TASK.post_type='wpeo-task'
				AND TASK.post_parent != 0
				AND	POINTMETA.meta_key='wpeo_point'
				AND POINTMETA.meta_value LIKE '%completed\":false%'
				AND USERMETA.meta_key='wp_user_level'
				AND USERMETA.meta_value=0
				AND TASKMETA.meta_key = 'wpeo_task'
			ORDER BY POINT.comment_date DESC";

			return $query;
		}

		/**
		 * Construction de la requête permettant de récupèrer la liste des
		 *
		 * @param	string $where_clause The different fields to get from database.
		 *
		 * @return string The db query to launch
		 */
		public function get_new_response_query( $where_clause ) {
			$query = "SELECT {$where_clause}
			FROM {$GLOBALS['wpdb']->comments} as TIME
				JOIN {$GLOBALS['wpdb']->comments} AS POINT ON TIME.comment_parent=POINT.comment_ID
				JOIN {$GLOBALS['wpdb']->posts} AS TASK 		ON POINT.comment_post_id=TASK.id
				JOIN {$GLOBALS['wpdb']->commentmeta} AS POINTMETA ON POINT.comment_ID=POINTMETA.comment_id
				JOIN {$GLOBALS['wpdb']->users} AS USER ON TIME.user_id=USER.ID
				JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID=USERMETA.user_id
			WHERE TASK.post_type='wpeo-task'
				AND	POINTMETA.meta_key='wpeo_point'
				AND POINTMETA.meta_value LIKE '%completed\":false%'
				AND TIME.comment_content != ''
				AND TIME.comment_approved != 'trash'
				AND USERMETA.meta_key='wp_user_level'
				AND USERMETA.meta_value=0
			ORDER BY TIME.comment_date DESC";

			return $query;
		}

		/**
		 * Permet d'afficher la dashicons qui vas être affiché dans la barre de WordPress.
		 *
		 * @param mixed $wp_admin_bar L'objet de WordPress pour gérer les noeuds.
		 *
		 * @return void
		 *
		 * @since 1.0.1.0
		 * @version 1.0.1.0
		 */
		public function callback_admin_bar_menu( $wp_admin_bar ) {
			if ( current_user_can( 'administrator' ) ) {
				global $wpdb;
				$query_args = array(
					'action' => 'open_popup_last_wpshop_customer_ask',
					'width' => '1000',
					'height' => '900',
				);
				$comments = $wpdb->get_results( $this->get_new_ask_query( 'POINT.comment_ID, POINT.comment_date, TASKMETA.meta_value' ) ); // WPCS : unprepared sql ok.
				$current_date = current_time( 'timestamp' );
				$new_comment = '';
				$nb_comments = 0;
				if ( ! empty( $comments ) ) {
					foreach ( $comments as $comment ) {
						if ( strpos( $comment->meta_value, $comment->comment_ID ) ) {
							$timestamp_comment = mysql2date( 'U', $comment->comment_date );
							if ( ( $current_date - ( 3600 * 24 ) * 5 ) < $timestamp_comment ) {
								$new_comment = '🔴';
							}

							$nb_comments += 1;
						}
					}
				}
				$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
				$button_open_popup = array(
					'id'			 	=> 'button-open-popup-last-ask-customer',
					'href'			=> '#',
					'title'			=> $new_comment . $nb_comments . ' demandes',
					'meta'		 	=> array(
						'onclick' => 'tb_show( "Les derniers commentaires des clients WPShop", "' . $href . '" )',
					),
				);
				$wp_admin_bar->add_node( $button_open_popup );

				$comments = $wpdb->get_results( $this->get_new_response_query( 'TIME.comment_date' ) ); // WPCS: unprepared sql ok.
				$current_date = current_time( 'timestamp' );
				$new_comment = '';
				if ( ! empty( $comments ) ) {
					foreach ( $comments as $comment ) {
						$timestamp_comment = mysql2date( 'U', $comment->comment_date );
						if ( ( $current_date - ( 3600 * 24 ) * 5 ) < $timestamp_comment ) {
							$new_comment = '🔴';
							break;
						}
					}
				}
				$query_args = array(
					'action' => 'open_popup_last_wpshop_customer_comment',
					'width' => '1000',
					'height' => '900',
				);
				$href = add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
				$button_open_popup = array(
					'id'			 	=> 'button-open-popup-last-comment-customer',
					'href'			=> '#',
					'title'			=> $new_comment . count( $comments ) . ' réponses',
					'meta'		 	=> array(
						'onclick' => 'tb_show( "Les derniers commentaires des clients WPShop", "' . $href . '")',
					),
				);
				$wp_admin_bar->add_node( $button_open_popup );
			}
		}

		/**
		 * Cette méthode appelle une vue qui sera renvoyé au rendu de la thickbox.
		 * Elle charge également les 5 derniers commentaires des clients de WPShop.
		 *
		 * @return void
		 *
		 * @since 1.0.1.0
		 * @version 1.0.1.0
		 */
		public function callback_open_popup_last_wpshop_customer_ask() {
			global $time_controller, $wpdb;
			$comments = $wpdb->get_results( $this->get_new_ask_query( 'POINT.comment_ID, POINT.comment_content, TASK.post_parent, USER.user_email, POINT.comment_date, TASKMETA.meta_value' ) ); // WPCS: unprepared sql ok.
			ob_start();
			require( WPEO_TASKMANAGER_PATH . 'core/template/backend/popup.view.php' );
			wp_die( ob_get_clean() ); // WPCS: XSS is ok.
		}

		/**
		 * Cette méthode appelle une vue qui sera renvoyé au rendu de la thickbox.
		 * Elle charge également les 5 derniers commentaires des clients de WPShop.
		 *
		 * @return void
		 *
		 * @since 1.0.1.0
		 * @version 1.0.1.0
		 */
		public function callback_open_popup_last_wpshop_customer_comment() {
			global $time_controller, $wpdb;
			$comments = $wpdb->get_results( $this->get_new_response_query( 'TIME.comment_ID, POINT.comment_content AS point_content, TIME.comment_content, TASK.post_parent, USER.user_email, TIME.comment_date' ) ); // WPCS: unprepared sql ok.
			ob_start();
			require( WPEO_TASKMANAGER_PATH . 'core/template/backend/popup-comment.view.php' );
			wp_die( ob_get_clean() ); // WPCS: XSS is ok.
		}

	}

	global $taskmanager_action;
	$taskmanager_action = new taskmanager_action_01();

}
