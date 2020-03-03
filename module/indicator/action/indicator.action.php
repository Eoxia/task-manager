<?php
/**
 * Les actions relatives aux indications.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @since     1.5.0
 * @version   1.6.1
 * @copyright 2015-2017 Eoxia
 * @package   Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \eoxia\Custom_Menu_Handler as CMH;

/**
 * Les actions relatives aux indications.
 */
class Indicator_Action {


	/**
	 * Initialise les actions liées au indications.
	 *
	 * @since   1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 30 );

		add_action( 'load-toplevel_page_wpeomtm-dashboard', array( $this, 'callback_load' ) );
		add_action( 'admin_footer-toplevel_page_wpeomtm-dashboard', array( $this, 'callback_admin_footer' ) );

		add_action( 'wp_ajax_mark_as_read', array( $this, 'callback_mark_as_reak' ) );
		add_action( 'tm_delete_task', array( $this, 'callback_tm_delete_task' ) );
		add_action( 'tm_archive_task', array( $this, 'callback_tm_archive_task' ) );
		add_action( 'wp_ajax_tm_unpack_task', array( $this, 'callback_tm_unpack_task' ) );
		add_action( 'tm_complete_point', array( $this, 'callback_tm_complete_point' ) );
		add_action( 'tm_delete_point', array( $this, 'callback_tm_delete_point' ) );
		add_action( 'tm_edit_comment', array( $this, 'callback_tm_edit_comment' ), 10, 3 );
		add_action( 'tm_action_after_comment_update', array( $this, 'callback_tm_add_entry_customer_ask' ) );
		add_action( 'tm_customer_remove_entry_customer_ask', array( $this, 'callback_tm_remove_entry_customer_ask' ) );
		add_action( 'tm_after_move_point_to', array( $this, 'callback_tm_after_move_point_to' ), 10, 2 );

		add_action( 'wp_ajax_load_stats_indicator', array( $this, 'callback_load_stats_indicator' ) );
		add_action( 'wp_ajax_update_indicator_stats', array( $this, 'callback_update_indicator_stats' ) );

		add_action( 'wp_ajax_update_indicator_stats_deadline', array( $this, 'callback_update_indicator_stats_deadline' ) );

		add_action( 'wp_ajax_load_tags_stats', array( $this, 'callback_load_tags_stats' ) );
	}


	// @temp add_submenu_page( 'wpeomtm-dashboard', __( 'Categories', 'task-manager' ), __( 'Categories', 'task-manager' ), 'manage_task_manager', 'edit-tags.php?taxonomy=wpeo_tag' );

	/**
	 * Ajoutes la page 'Indicator' dans le sous menu de Task Manager.
	 *
	 * @since 1.5.0
	 */
	public function callback_admin_menu() {
		CMH::register_menu( 'wpeomtm-dashboard', __( 'Indicator', 'task-manager' ), __( 'Indicator', 'task-manager' ), 'manage_task_manager', 'indicator-page', array( Indicator_Class::g(), 'callable_indicator_page' ), 'fa fa-chart-pie' );
		add_meta_box( 'tm-indicator-activity', __( 'Daily activity', 'task-manager' ), array( Indicator_Class::g(), 'callback_my_daily_activity' ), 'wpeomtm-dashboard', 'normal' );
		add_meta_box( 'indicator-page-id', __( 'Indicator User', 'task-manager' ), array( Indicator_Class::g(), 'callback_load_indicator_page' ), 'indicator-page', 'normal' );
		add_meta_box( 'indicator-page-client', __( 'Indicator Client', 'task-manager' ), array( Indicator_Class::g(), 'callback_load_client_page' ), 'indicator-page', 'normal' );
		add_meta_box( 'indicator-page-listtag', __( 'Indicator Tag', 'task-manager' ), array( Indicator_Class::g(), 'callback_load_tag_page' ), 'indicator-page', 'normal' );
	}

	/**
	 * Charge les scripts
	 *
	 * @return void
	 */
	public function callback_load() {
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
	}

	/**
	 * Renvois le footer admin
	 *
	 * @return void
	 *
	 * @since ? Before 1.9.0 - BETA
	 */
	public function callback_admin_footer() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
				postboxes.add_postbox_toggles( 'wpeomtm-dashboard' );
			});
		</script>
		<?php
	}

	/**
	 * Lors de la suppresion d'une tâche, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et dans cette tâche.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param  Task_Model $task La tâche.
	 * @return void
	 */
	public function callback_tm_delete_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		if ( ! empty( $ids[ $task->data['id'] ] ) ) {
			foreach ( $ids[ $task->data['id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on archive une tâche, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et dans cette tâche.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param  Task_Model $task La tâche.
	 * @return void
	 */
	public function callback_tm_archive_task( $task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		if ( ! empty( $ids[ $task->data['id'] ] ) ) {
			foreach ( $ids[ $task->data['id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on archive une tâche, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et dans cette tâche.
	 *
	 * @since   3.0.1
	 * @version 3.0.1
	 *
	 * @param  Task_Model $task La tâche.
	 * @return void
	 */
	public function callback_tm_unpack_task() {
		check_ajax_referer( 'tm_unpack_task' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$task = Task_Class::g()->get( array( 'id' => $id ), true );
		$task->data['status'] = 'publish';
		Task_Class::g()->update( $task->data );
	}

	/**
	 * Lorsqu'on complète un point, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et correspondant à ce point.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param  Point_Model $point Le point.
	 * @return void
	 */
	public function callback_tm_complete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		if ( ! empty( $ids[ $point->data['post_id'] ] ) ) {
			foreach ( $ids[ $point->data['post_id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on supprime un point, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et correspondant à ce point.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param  Point_Model $point Le point.
	 * @return void
	 */
	public function callback_tm_delete_point( $point ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		if ( ! empty( $ids[ $point->data['post_id'] ] ) ) {
			foreach ( $ids[ $point->data['post_id'] ] as $more_ids ) {
				if ( ! empty( $more_ids ) ) {
					foreach ( $more_ids as $point_id => $id ) {
						$this->callback_tm_remove_entry_customer_ask( $id );
					}
				}
			}
		}
	}

	/**
	 * Lorsqu'on écrit un commentaire, enlève les ID des commentaires se trouvant dans le tableau "key_customer_ask" et contenu dans le point de ce commentaire.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param Task_Model    $task    La
	 *                               tâche.
	 * @param Point_Model   $point   Le point.
	 * @param Comment_Model $comment Le commentaire.
	 *
	 * @return boolean
	 */
	public function callback_tm_edit_comment( $task, $point, $comment ) {
		$user = get_userdata( $comment->data['author_id'] );
		if ( ! in_array( 'administrator', $user->roles, true ) ) {
			return false;
		}
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		if ( empty( $ids[ $task->data['id'] ] ) ) {
			return false;
		}
		if ( empty( $ids[ $task->data['id'] ][ $point->data['id'] ] ) ) {
			return false;
		}
		$comments_customer = Task_Comment_Class::g()->get(
			array(
				'comment__in' => $ids[ $task->data['id'] ][ $point->data['id'] ],
			)
		);
		if ( ! empty( $comments_customer ) ) {
			foreach ( $comments_customer as $comment_customer ) {
				if ( strtotime( $comment_customer->data['date']['raw'] ) < strtotime( $comment->data['date']['raw'] ) ) {
					$this->callback_tm_remove_entry_customer_ask( $comment_customer->data['id'] );
				}
			}
		}
	}

	/**
	 * Ajoutes l'ID d'un point ou d'un commentaire dans le tableau de la meta key_customer_ask.
	 *
	 * @since   1.2.0
	 * @version 1.2.0
	 *
	 * @param integer $id L'ID du commentaire.
	 *
	 * @return bool
	 */
	public function callback_tm_add_entry_customer_ask( $id ) {
		\eoxia\LOG_Util::log( '------------------------------------------------------------------------------------------------', 'task-manager' );
		$comment = Task_Comment_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);
		if ( 0 === $comment->data['id'] ) {
			/* translators: */
			\eoxia\LOG_Util::log( sprintf( __( 'Given comment identifier does not correspond to a comment in task manager. Request id: %s', 'task-manager' ), $id ), 'task-manager' );
			return false;
		}
		// @info Check if the comment must be added to current ticket .
		$comment->data['author'] = get_userdata( $comment->data['author_id'] );
		if ( in_array( 'administrator', $comment->data['author']->roles, true ) ) {
			/* translators: %d1$, %2$s */
			\eoxia\LOG_Util::log( sprintf( __( 'The comment author role does not allowed support request. Request customer id: %1$d. Customer roles: %2$s', 'task-manager' ), $comment->data['author_id'], wp_json_encode( $comment->data['author']->roles ) ), 'task-manager' );
			return false;
		}
		// If the code continue from here it means that we have to set a new support request.
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		/* translators: */
		\eoxia\LOG_Util::log( sprintf( __( 'Current support request list: %s', 'task-manager' ), wp_json_encode( $ids ) ), 'task-manager' );

		/* translators: */
		\eoxia\LOG_Util::log( sprintf( __( 'Comment for adding in request %s', 'task-manager' ), wp_json_encode( $comment ) ), 'task-manager' );
		if ( empty( $ids[ $comment->data['post_id'] ] ) ) {
			$ids[ $comment->data['post_id'] ] = array(
				$comment->data['parent_id'] => array(
					$comment->data['id'],
				),
			);
		}
		if ( empty( $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] ) ) {
			$ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] = array(
				$comment->data['id'],
			);
		}
		if ( ! empty( $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ] ) && ! in_array( $comment->data['id'], $ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ], true ) ) {
			$ids[ $comment->data['post_id'] ][ $comment->data['parent_id'] ][] = (int) $comment->data['id'];
		}
		update_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, $ids );
		/* translators: */
		\eoxia\LOG_Util::log( sprintf( __( 'New support ticket list: %s', 'task-manager' ), wp_json_encode( $ids ) ), 'task-manager' );
		return true;
	}

	/**
	 * Appelle la méthode remove_entry_customer_ask.
	 *
	 * @since   1.2.0
	 * @version 1.3.0
	 *
	 * @param integer $id L'ID du commentaire.
	 *
	 * @return void
	 */
	public function callback_tm_remove_entry_customer_ask( $id ) {
		Indicator_Class::g()->remove_entry_customer_ask( $id );
	}

	/**
	 * Met à jour le tableau des demandes des clients de WPShop.
	 *
	 * @since   1.3.0
	 * @version 1.3.0
	 *
	 * @param Point_Model $point    Les données du point.
	 * @param Task_Model  $old_task Les données de l'ancienne point ou la tâche était rattaché.
	 *
	 * @return void
	 */
	public function callback_tm_after_move_point_to( $point, $old_task ) {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );
		$tmp = array();
		if ( isset( $ids[ $old_task->data['id'] ] ) && isset( $ids[ $old_task->data['id'] ][ $point->data['id'] ] ) ) {
			$tmp = $ids[ $old_task->data['id'] ][ $point->data['id'] ];
			unset( $ids[ $old_task->data['id'] ][ $point->data['id'] ] );
		}
		if ( ! isset( $ids[ $point->data['post_id'] ][ $point->data['id'] ] ) && ! empty( $tmp ) ) {
			$ids[ $point->data['post_id'] ][ $point->data['id'] ] = $tmp;
		}
		update_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, $ids );
	}

	/**
	 * Fonction développé < 18/01/2019
	 * sans description - Corentin
	 * [callback_mark_as_reak description]
	 *
	 * @return void
	 */
	public function callback_mark_as_reak() {

		check_ajax_referer( 'mark_as_read' );
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		Indicator_Class::g()->remove_entry_customer_ask( $id );
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'indicator',
				'callback_success' => 'markedAsReadSuccess',
			)
		);
	}

	public function callback_load_stats_indicator(){
		check_ajax_referer( 'load_stats_indicator' );

		$customer_id = isset( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : 0;

		if( ! $customer_id ){
			wp_send_json_error();
		}

		$tasks = Task_Class::g()->update_client_indicator( $customer_id );

		$year       = $tasks[ 'year' ];
		$type       = $tasks[ 'type' ];
		$info       = $tasks[ 'info' ];
		$everymonth = $tasks[ 'everymonth' ];

		$view = ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/metabox-head-indicator',
			array(
				'post_id'     => $customer_id,
				'post_author' => 0,
				'year'        => date( 'Y' ),
				'parent_id'   => 0 //Inutilisé
			)
		);

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/metabox-indicators',
			array(
				'type'       => $type,
				'info'       => $info,
				'everymonth' => $everymonth
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'indicator',
				'callback_success' => 'updateIndicatorClientSuccess',
				'view'             => ob_get_clean(),
				'year'             => $year
			)
		);
	}

	public function callback_update_indicator_stats(){
		check_ajax_referer( 'update_indicator_stats' );

		$date = isset( $_POST[ 'month' ] ) ? strtotime( $_POST[ 'month'] ) : strtotime( 'now' );

		$month = array(
			'value'     => date( 'F', $date ),
			'start'     => date( 'Y-m-01', $date ), // premier jour du mois actuel
			'start_str' => strtotime( date( 'Y-m-01', $date ) ), // 00h00min00sec
			'end'       => date( 'Y-m-t',$date ), // dernier jour du mois actuel
			'end_str'   => strtotime( date( 'Y-m-t', $date ) ) + 86399 // 23h59min59sec
		);

		$customers = Indicator_Class::g()->callback_load_client_page( $month );

		ob_start();

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

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'indicator',
				'callback_success' => 'updateStatsClient',
				'view'             => ob_get_clean()
			)
		);
	}

	public function callback_update_indicator_stats_deadline(){
		check_ajax_referer( 'update_indicator_stats_deadline' );

		$date    = isset( $_POST[ 'month' ] ) ? strtotime( $_POST[ 'month'] ) : strtotime( 'now' );
		$element = isset( $_POST[ 'type' ] ) ? $_POST[ 'type'] : '';

		$month = array(
			'value'     => date( 'F', $date ),
			'start'     => date( 'Y-m-01', $date ), // premier jour du mois actuel
			'start_str' => strtotime( date( 'Y-m-01', $date ) ), // 00h00min00sec
			'end'       => date( 'Y-m-t',$date ), // dernier jour du mois actuel
			'end_str'   => strtotime( date( 'Y-m-t', $date ) ) + 86399 // 23h59min59sec
		);

		$customers = Indicator_Class::g()->callback_load_client_deadline( $month );

		ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-stats/indicator-main',
			array(
				'date'      => $month,
				'customers' => $customers,
				'element'   => $element
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'indicator',
				'callback_success' => 'updateStatsClient',
				'view'             => ob_get_clean()
			)
		);
	}

	public function callback_load_tags_stats(){
		$tagid = isset( $_POST[ 'tag_id' ] ) ? (int) $_POST[ 'tag_id' ] : 0;
		$year = isset( $_POST[ 'year' ] ) ? (int) $_POST[ 'year' ] : 0;
		$orderby = isset( $_POST[ 'order' ] ) ? sanitize_text_field( $_POST[ 'order' ] ) : '';

		if( ! $tagid ){
			wp_send_json_error( 'Error tagid undefined' );
		}

		$tasks = Task_Class::g()->get_tasks(
			array(
				'categories_id'  => $tagid,
			)
		);

		$data = Indicator_Class::g()->generate_data_indicator_tag( $tasks, $year );

		$everymonth = $data[ 'everymonth' ];
		$year       = $data[ 'year' ];
		$type_stats = __( 'Customer', 'task-manager' );

		$type = $data[ 'type' ];
		$info     = $data[ 'info' ];

		if( ! $orderby ){
			foreach( $data[ 'type' ] as $key => $type_ ){
				$type[ $key ] = Indicator_Class::g()->sort_indicator_by_name( $type_, $data[ 'info' ][ $key ] );
			}
		}else{
			foreach( $data[ 'type' ] as $key => $type_ ){
				$type[ $key ] = Indicator_Class::g()->sort_indicator_by_percent( $type_, $data[ 'info' ][ $key ], $orderby );
			}
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator-tag/indicator-table-page',
			array(
				'type' => $type,
				'info' => $info,
				'everymonth' => $everymonth,
				'type_stats' => $type_stats
			)
		);

		$view = ob_get_clean();
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator-tag/header',
			array(
				'year' => $year,
				'tagid' => $tagid
			)
		);

		$header_view = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'indicator',
				'callback_success' => 'updateIndicatorTag',
				'view'             => $view,
				'year'             => $year,
				'header_view'      => $header_view,
				'content_empty'    => empty( $info ) ? 'true' : 'false'
			)
		);
	}
}

new Indicator_Action();
