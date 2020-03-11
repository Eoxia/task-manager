<?php
/**
 * Gestion des actions cotées 'support'.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des actions cotées 'support'.
 */
class Support_Action {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_action( 'wp_ajax_open_popup_create_ticket', array( $this, 'callback_open_popup_create_ticket' ) );
		add_action( 'wp_ajax_create_ticket', array( $this, 'callback_create_ticket' ) );
		add_action( 'wp_ajax_load_last_activity_in_support', array( $this, 'callback_load_last_activity_in_support' ) );

		add_action( 'wp_token_login', array( $this, 'callback_wp_token_login' ), 11, 1 );

		add_action( 'wps_account_navigation_endpoint', function() {
			add_rewrite_endpoint( 'support', EP_ALL );
		}, 10, 0 );

		add_action( 'wps_account_support', function() {
			$contact     = \wpshop\Contact::g()->get( array( 'id' => get_current_user_id() ), true );
			$third_party = \wpshop\Third_Party::g()->get( array( 'id' => $contact->data['third_party_id'] ), true );

			$tasks_id = array();

			$total_time_elapsed           = 0;
			$total_time_estimated         = 0;
			$last_modification_date       = '';
			$last_modification_date_mysql = '';

			$tasks = \task_manager\Task_Class::g()->get_tasks(
				array(
					'post_parent' => $third_party->data['id'],
				)
			);

			$args = array(
				'post_parent' => $third_party->data['id'],
				'post_type'   => \eoxia\Config_Util::$init['task-manager']->associate_post_type,
				'numberposts' => -1,
				'post_status' => 'any',
			);

			$children = get_posts( $args );

			if ( ! empty( $children ) ) {
				foreach ( $children as $child ) {
					$tasks = array_merge(
						$tasks,
						\task_manager\Task_Class::g()->get_tasks(
							array(
								'post_parent' => $child->ID,
							)
						)
					);
				}
			}

			if ( ! empty( $tasks ) ) {
				foreach ( $tasks as $task ) {
					$tasks_id[]            = $task->data['id'];
					$total_time_elapsed   += $task->data['time_info']['elapsed'];
					$total_time_estimated += $task->data['last_history_time']->data['estimated_time'];

					if ( empty( $last_modification_date ) || ( $last_modification_date_mysql < $task->data['date_modified']['raw'] ) ) {
						$last_modification_date_mysql = $task->data['date_modified']['raw'];
						$last_modification_date       = $task->data['date_modified']['rendered']['date_time'];
					}
				}
			}

			View_Util::exec(
				'task-manager',
				'support',
				'frontend/main',
				array(
					'last_modification_date' => $last_modification_date,
					'tasks'                  => $tasks,
					'tasks_id'               => implode( ',', $tasks_id ),
					'parent_id'              => $third_party->data['id'],
					'customer_id'            => $third_party->data['id'],
					'user_id'                => get_current_user_id(),
					'total_time_elapsed'     => $total_time_elapsed,
					'total_time_estimated'   => $total_time_estimated,
				)
			);
		} );

		add_action( 'wps_account_quotations', array( $this, 'display_support' ) );

		add_action( 'wp_ajax_send_response_to_customer', array( $this, 'callback_send_response_to_customer' ) );

	}

	/**
	 * Appel la vue contenant le formulaire pour faire une nouvelle demande.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return void
	 */
	public function callback_open_popup_create_ticket() {
		check_ajax_referer( 'open_popup_create_ticket' );

		ob_start();
		View_Util::exec( 'task-manager', 'support', 'frontend/form-create-ticket' );
		wp_send_json_success(
			array(
				'namespace'        => 'taskManagerFrontend',
				'module'           => 'frontendSupport',
				'callback_success' => 'openedPopupCreateTicket',
				'buttons_view'     => '',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Fonction de callback pour les demandes de tâches depuis le frontend
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 */
	public function callback_create_ticket() {
		check_ajax_referer( 'create_ticket' );

		$current_url                      = ! empty( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
		$subject                          = ! empty( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
		$description                      = ! empty( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';
		$current_customer_account_to_show = ! empty( $_COOKIE['wps_current_connected_customer'] ) ? wp_unslash( $_COOKIE['wps_current_connected_customer'] ) : '';

		if ( empty( $subject ) || empty( $description ) || strlen( $subject ) > 150 ) {
			wp_send_json_error();
		}

		global $wpdb;

		$edit     = false;
		$projects = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name LIKE %s AND post_parent = %d AND post_status = 'publish'", array( 'ask-task-%', $current_customer_account_to_show ) ) );
		/** On crée la tâche */
		if ( 0 === count( $projects ) ) {
			$project    = \task_manager\Task_Class::g()->update(
				array(
					'title'     => __( 'Ask', 'task-manager' ),
					'slug'      => 'ask-task-' . get_current_user_id(),
					'parent_id' => (int) $current_customer_account_to_show,
				)
			);
			$project_id = $project->data['id'];
		} else {
			$edit       = true;
			$project_id = $projects[0]->ID;
		}

		$project   = \task_manager\Task_Class::g()->get( array( 'id' => $project_id ), true );

		$project->tags = array();
		if ( ! empty( $project->data['taxonomy'][ Tag_Class::g()->get_type() ] ) ) {
			$project->tags = Tag_Class::g()->get(
				array(
					'include' => $project->data['taxonomy'][ Tag_Class::g()->get_type() ],
				)
			);
		}

		$project->readable_tag = '';

		if ( ! empty( $project->tags ) ) {
			foreach ( $project->tags as $tags ) {
				$project->readable_tag .= $tags->data['name'] . ', ';
			}
		}

		$project->readable_tag = substr( $project->readable_tag, 0, strlen( $project->readable_tag ) - 2 );

		$task_data = array(
			'content' => $subject,
			'post_id' => (int) $project_id,
			'order'   => (int) ( $project->data['count_uncompleted_points'] - 1 ),
		);

		$task = \task_manager\Point_Class::g()->update( $task_data );

		$comment_data = array(
			'content'        => $description,
			'post_id'        => (int) $project_id,
			'comment_parent' => $task->data['id'],
			'time_info'      => array(
				'elapsed' => 0,
			),
		);

		$comment = \task_manager\Task_Comment_Class::g()->update( $comment_data );

		// Ajoutes une demande dans la donnée compilé.
		do_action( 'tm_action_after_comment_update', $comment->data['id'] );

		ob_start();
		View_Util::exec(
			'task-manager',
			'support',
			'frontend/project-header',
			array(
				'project' => $project,
			)
		);
		$project_view = ob_get_clean();

		ob_start();
		View_Util::exec(
			'task-manager',
			'support',
			'frontend/task',
			array(
				'project'     => $project,
				'task'        => $task,
				'current_url' => $current_url,
			)
		);
		$task_view = ob_get_clean();

		ob_start();
		View_Util::exec( 'task-manager', 'support', 'frontend/created-ticket-success' );
		$success_view = ob_get_clean();

		wp_send_json_success(
			array(
				'project_id'       => $project_id,
				'edit'             => $edit,
				'namespace'        => 'taskManagerFrontend',
				'module'           => 'frontendSupport',
				'callback_success' => 'createdTicket',
				'project_view'     => $project_view,
				'task_view'        => $task_view,
				'success_view'     => $success_view,
			)
		);
	}

	/**
	 * Ajoutes le cookie wps_current_connected_customer avec l'action wp_token_login
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  WP_User $user L'objet user de WordPress.
	 * @return void
	 */
	public function callback_wp_token_login( $user ) {
		$customer_id = \wps_customer_ctr::get_customer_id_by_author_id( $user->ID );
		if ( empty( $customer_id ) ) {
			$query       = $GLOBALS['wpdb']->prepare( "SELECT post_id FROM {$GLOBALS['wpdb']->postmeta} WHERE meta_key = %s AND meta_value LIKE %s ORDER BY meta_id LIMIT 1", '_wpscrm_associated_user', "%;i:$user->ID;%" );
			$customer_id = $GLOBALS['wpdb']->get_var( $query );
		}

		setcookie( 'wps_current_connected_customer', $customer_id, strtotime( '+30 days' ), SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}

	public function callback_send_response_to_customer() {
		check_ajax_referer( 'send_response_to_customer' );

		$project_id  = ! empty( $_POST['project_id'] ) ? (int) $_POST['project_id'] : 0;
		$task_id     = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$description = ! empty( $_POST['description'] ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ) : '';

		$comment_data = array(
			'content'        => $description,
			'post_id'        => $project_id,
			'comment_parent' => $task_id,
			'time_info'      => array(
				'elapsed' => 0,
			),
		);

		$comment = Task_Comment_Class::g()->update( $comment_data );

		ob_start();
		View_Util::exec(
			'task-manager',
			'support',
			'frontend/comment',
			array(
				'comment' => $comment,
			)
		);
		$view = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManagerFrontend',
				'module'           => 'frontendSupport',
				'callback_success' => 'sendedResponseToSupport',
				'view'             => $view,
			)
		);
	}
}

new Support_Action();
