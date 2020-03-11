<?php
/**
 * Fichier de gestion des filtres du support
 *
 * @since 1.0.0
 * @version 1.3.0
 *
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion des filtres
 */
class Support_Filter {

	/**
	 * Instanciation du module
	 */
	public function __construct() {
		add_filter( 'wps_my_account_extra_part_menu', array( $this, 'callback_my_account_menu' ) );
		add_filter( 'wps_my_account_extra_panel_content', array( $this, 'callback_my_account_content' ), 10, 2 );
		add_filter( 'wp_redirect', array( $this, 'callback_wp_redirect' ), 10, 2 );
		add_filter( 'tm_activity_filter_input', array( $this, 'callback_tm_activity_filter_input' ), 10, 1 );

		add_filter( 'task_manager_popup_notify_after', array( $this, 'callback_task_manager_popup_notify_after' ), 10, 2 );
		add_filter( 'task_manager_notify_send_notification_recipients', array( $this, 'callback_task_manager_notify_send_notification_recipients' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_subject', array( $this, 'callback_task_manager_notify_send_notification_subject' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_body', array( $this, 'callback_task_manager_notify_send_notification_body' ), 10, 3 );
		add_filter( 'task_manager_notify_send_notification_body_administrator', array( $this, 'callback_task_manager_notify_send_notification_body_administrator' ), 10, 3 );

		add_filter( 'wps_account_navigation_items', function( $menu ) {
			$task_manager_item = array(
				'link'  => \wpshop\Pages::g()->get_account_link() . 'support/',
				'icon'  => 'fas fa-tasks',
				'title' => __( 'Support', 'wpshop' ),
			);

			$logout_position = array_search( 'logout', array_keys( $menu ) );

			$before_menu = array_slice( $menu, 0, $logout_position, true );
			$after_menu  = array_slice( $menu, $logout_position, count( $menu ) - 1, true );

			$menu = array_merge( $before_menu, array( 'support' => $task_manager_item ), $after_menu );

			return $menu;
		}, 10, 1 );
		add_filter( 'wps_navigation_shortcode', function( $tab, $query_vars ) {
			if ( array_key_exists( 'support', $query_vars ) ) {
				$tab = 'support';
			}

			return $tab;
		}, 10, 2  );

	}

	/**
	 * [callback_my_account_menu description]
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function callback_my_account_menu() {
		\eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/menu' );
	}

	/**
	 * [callback_my_account_content description]
	 *
	 * @since 1.0.0
	 * @version 1.2.0
	 *
	 * @param string $output         Output content.
	 * @param string $dashboard_part slug du menu.
	 *
	 * @return string
	 */
	public function callback_my_account_content( $output, $dashboard_part ) {
		if ( 'support' === $dashboard_part && isset( $_COOKIE['wps_current_connected_customer'] ) ) {

			if ( ! empty( $_REQUEST['project_id'] ) && ! empty( $_REQUEST['task_id'] ) ) {
				$project = Task_Class::g()->get( array( 'id' => (int) $_REQUEST['project_id'] ), true );
				$project = Support_Class::g()->get_data( $project );
				$task    = Point_Class::g()->get( array( 'id' => (int) $_REQUEST['task_id'] ), true );
				$comments = Task_Comment_Class::g()->get_comments( $task->data['id'] );

				ob_start();
				\eoxia\View_Util::exec(
					'task-manager',
					'support',
					'frontend/single', array(
						'project'  => $project,
						'task'     => $task,
						'comments' => $comments,
					)
				);

				$output = ob_get_clean();
			} else {
				ob_start();
				\eoxia\View_Util::exec(
					'task-manager',
					'support',
					'frontend/main'
				);
				$output = ob_get_clean();
			}
		}

		return $output;
	}

	/**
	 * Gestion de la redirection après l'authentification par le lien 'token'.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @param  string  $location L'URL.
	 * @param  integer $status  Le status de la requête.
	 * @return string
	 */
	public function callback_wp_redirect( $location, $status ) {
		if ( 'wp-login.php?tokeninvalid=true' === $location ) {
			$location = get_option( 'tl_login_redirect_url' );
		}
		return $location;
	}

	/**
	 * Filtre de l'input d'une activité
	 *
	 * @param  [type] $output [vue].
	 * @return [type] $output [vue]
	 */
	public function callback_tm_activity_filter_input( $output ) {
		if ( ! is_admin() ) {
			$output .= '<input type="hidden" name="frontend" value="true" />';
		}
		return $output;
	}

	/**
	 * Ajoutes du contenu de la popup "notification".
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string     $content Le contenu de la popup.
	 * @param  Task_Model $task    Les données de la tâche.
	 *
	 * @return string              Le contenu de la popup modifié.
	 */
	public function callback_task_manager_popup_notify_after( $content, $task ) {
		if ( 0 === $task->data['parent_id'] ) {
			return $content;
		}
		$post_type = get_post_type( $task->data['parent_id'] );
		if ( ! $post_type ) {
			return $content;
		}
		if ( 'wpshop_customers' !== $post_type && 'wpshop_shop_order' !== $post_type ) {
			return $content;
		}
		$post = get_post( \eoxia\Config_Util::$init['task-manager']->id_mail_support );
		$body = __( 'No support post found', 'task-manager' );
		if ( ! empty( $post->post_content ) ) {
			$body = $post->post_content;
		}
		$datas     = \task_manager\Activity_Class::g()->get_activity( array( $task->data['id'] ), 0 );
		$query     = $GLOBALS['wpdb']->prepare( "SELECT ID FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
		$page_id   = $GLOBALS['wpdb']->get_var( $query );
		$permalink = get_permalink( $page_id );
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'activity',
			'backend/mail/list',
			array(
				'datas'     => $datas,
				'last_date' => '',
				'permalink' => $permalink,
			)
		);
		$body    .= ob_get_clean();
		$users_id = get_post_meta( $task->data['parent_id'], '_wpscrm_associated_user', true );
		if ( empty( $users_id ) ) {
			$users_id = array();
		}
		$customer_post = get_post( $task->data['parent_id'] );
		if ( ! empty( $customer_post ) && ! in_array( $customer_post->post_author, (array) $users_id ) ) {
			$users_id[] = $customer_post->post_author;
		}
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'notify',
			'backend/support/main',
			array(
				'users_id' => $users_id,
				'post'     => $post,
				'body'     => $body,
			)
		);
		$content .= ob_get_clean();
		return $content;
	}
	/**
	 * Ajoutes l'email du client WPShop lié à la tâche.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  array       $recipients Un tableau contenant l'email des utilisateurs liées à la tâche.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return array                   Le tableau contenant l'email des utilisateurs + celui du client.
	 */
	public function callback_task_manager_notify_send_notification_recipients( $recipients, $task, $form_data ) {
		if ( 0 === $task->data['parent_id'] ) {
			return $recipients;
		}
		$post_type = get_post_type( $task->data['parent_id'] );
		if ( ! $post_type ) {
			return $recipients;
		}
		if ( 'wpshop_customers' !== $post_type && 'wpshop_shop_order' !== $post_type ) {
			return $recipients;
		}
		if ( ! empty( $form_data['customers_id'] ) ) {
			$customers_id = explode( ',', $form_data['customers_id'] );
			foreach ( $customers_id as $user_id ) {
				$user_info    = get_userdata( $user_id );
				$recipients[] = $user_info;
			}
		}
		return $recipients;
	}
	/**
	 * Modifie le sujet du mail envoyé au client.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string      $subject    Le sujet du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le sujet du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_subject( $subject, $task, $form_data ) {
		if ( 0 === $task->data['parent_id'] ) {
			return $subject;
		}
		$post_type = get_post_type( $task->data['parent_id'] );
		if ( ! $post_type ) {
			return $subject;
		}
		if ( 'wpshop_customers' !== $post_type && 'wpshop_shop_order' !== $post_type ) {
			return $subject;
		}
		$post = get_post( \eoxia\Config_Util::$init['task-manager']->id_mail_support );
		if ( ! $post ) {
			return $subject;
		}
		$subject = $post->post_title;
		return $subject;
	}
	/**
	 * Modifie le contenu du mail envoyé au client.
	 *
	 * @since 1.2.0
	 * @version 1.3.0
	 *
	 * @param  string      $body    Le contenu du mail.
	 * @param  Task_Object $task       La tâche en elle même.
	 * @param  array       $form_data  Les données du formulaire.
	 * @return string                  Le contenu du mail modifié par ce filtre.
	 */
	public function callback_task_manager_notify_send_notification_body( $body, $task, $form_data ) {
		if ( 0 === $task->data['parent_id'] ) {
			return $body;
		}
		$post_type = get_post_type( $task->data['parent_id'] );
		if ( ! $post_type ) {
			return $body;
		}
		if ( 'wpshop_customers' !== $post_type && 'wpshop_shop_order' !== $post_type ) {
			return $body;
		}
		$post = get_post( \eoxia\Config_Util::$init['task-manager']->id_mail_support );
		if ( ! $post ) {
			return $body;
		}
		$body      = $post->post_content;
		$datas     = \task_manager\Activity_Class::g()->get_activity( array( $task->data['id'] ), 0 );
		$query     = $GLOBALS['wpdb']->prepare( "SELECT ID FROM {$GLOBALS['wpdb']->posts} WHERE ID = %d", get_option( 'wpshop_myaccount_page_id' ) );
		$page_id   = $GLOBALS['wpdb']->get_var( $query );
		$permalink = get_permalink( $page_id );
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'activity',
			'backend/mail/list',
			array(
				'datas'     => $datas,
				'last_date' => '',
				'permalink' => $permalink,
			)
		);
		$body .= ob_get_clean();
		return $body;
	}

	/**
	 * Envois les notifications dans le body administrator
	 *
	 * @param  [type] $body      [description].
	 * @param  [type] $task      [description].
	 * @param  [type] $form_data [description].
	 * @return [type]            [description]
	 */
	public function callback_task_manager_notify_send_notification_body_administrator( $body, $task, $form_data ) {
		if ( 0 === $task->data['parent_id'] ) {
			return $body;
		}
		$post_type = get_post_type( $task->data['parent_id'] );
		if ( ! $post_type ) {
			return $body;
		}
		if ( 'wpshop_customers' !== $post_type && 'wpshop_shop_order' !== $post_type ) {
			return $body;
		}
		$current_user = wp_get_current_user();
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'notify',
			'backend/support/body-admin',
			array(
				'current_user' => $current_user,
				'task'         => $task,
			)
		);
		$body = ob_get_clean() . $body;
		return $body;
	}
}

new Support_Filter();
