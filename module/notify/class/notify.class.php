<?php
/**
 * Gestion des notifications.
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

define( 'TM_NOTIFY_ACTION_ANSWER', 0 );
define( 'TM_NOTIFY_ACTION_COMPLETE', 1 );
define( 'TM_NOTIFY_ACTION_WAITING_FOR', 2 );
define( 'TM_NOTIFY_NEW_COMMENT', 3 );
define( 'TM_NOTIFY_CREATE_TASK', 4 );
define( 'TM_NOTIFY_MENTION', 5 );

/**
 * Gestion des notifications.
 */
class Notify_Class extends \eoxia\Singleton_Util {

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
	 * Load all notification data for the current user.
	 * Call the notification view.
	 *
	 * @since 3.1.0
	 */
	public function display() {
		$notifications = get_posts( array(
			'post_type'   => 'wpeo-notification',
			'numberposts' => -1,
			'post_status' => 'publish',
			'author'      => get_current_user_id(),
		) );

		$mode   = isset( $_GET['mode'] ) ? $_GET['mode'] : 'unread';
		$parent = isset( $_GET['parent'] ) ? (int) $_GET['parent'] : 0;

		if ( ! empty( $parent ) ) {
			$mode = '';
		}

		$notifications_by_elements = array();
		$filtered_notifications    = array();

		$count_unread = 0;
		$count_read   = 0;

		if ( ! empty( $notifications ) ) {
			foreach ( $notifications as &$notification ) {
				$notification = Notify_Class::g()->get_notification_data( $notification );

				if ( $notification->read ) {
					$count_read++;
				} else {
					$count_unread++;
				}

				if ( ! isset( $notifications_by_elements[ $notification->element_id ] ) ) {
					$notifications_by_elements[ $notification->element_id ] = array(
						'title' => $notification->subject->data['formatted_content'],
						'count' => 1,
					);
				} else {
					$notifications_by_elements[ $notification->element_id ]['count']++;
				}

				if ( $mode == 'read' && $notification->read ) {
					$filtered_notifications[] = $notification;
				} else if ( $mode == 'unread' && ! $notification->read ) {
					$filtered_notifications[] = $notification;
				} else if ( $mode == 'both' ) {
					$filtered_notifications[] = $notification;
				}

				if ( ! empty( $parent ) && $notification->element_id == $parent ) {
					$filtered_notifications[] = $notification;
				}
			}
		}

		\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/page/main', array(
			'data'                      => $filtered_notifications,
			'notifications_by_elements' => $notifications_by_elements,
			'count_read'                => $count_read,
			'count_unread'              => $count_unread,
			'mode'                      => $mode,
			'parent'                    => $parent,
		) );
	}

	public function get_notification_data( $notification ) {
		$notification->action_user_id    = get_post_meta( $notification->ID, '_tm_action_user_id', true );
		$notification->notified_users_id = get_post_meta( $notification->ID, '_tm_notified_users_id', true );
		$notification->element_id        = get_post_meta( $notification->ID, '_element_id', true );
		$notification->type_of_element   = get_post_meta( $notification->ID, '_type_of_element', true );
		$notification->action_type       = get_post_meta( $notification->ID, '_action_type', true );
		$notification->read              = get_post_meta( $notification->ID, 'read', true );
		$notification->action_user       = get_the_author_meta( 'display_name', $notification->action_user_id );
		$notification->notified_users    = array();
		$notification->project_name      = __( 'Error occured', 'task-manager');
		$notification->subject           = new \stdClass();
		$notification->subject->data     = array(
			'formatted_content' => __( 'Error occured', 'task-manager' ),
		);

		if ( ! empty( $notification->notified_users_id ) ) {
			foreach ( $notification->notified_users_id as $notified_user_id ) {
				$notification->notified_users[ $notified_user_id ] = get_the_author_meta( 'display_name', $notified_user_id );
			}
		}

		$notification->content = __( 'Error occured', 'task-manager' );

		switch ( $notification->type_of_element ) {
			case 'task':
				$notification = $this->load_additional_data_notification_for_task( $notification );
				break;
			case 'point':
				$notification = $this->load_additional_data_notification_for_point( $notification );
				break;
			case 'comment':
				$notification = $this->load_additional_data_notification_for_comment( $notification );
				break;
		}

		$now                = strtotime( 'now' );
		$notification->time = $notification->post_date;
		$time               = strtotime( 'now + 1 hour' ) - strtotime( $notification->time );
		$notification->time = Task_Class::g()->time_elapsed( $time );

		return $notification;
	}

	public function load_additional_data_notification_for_task( $entry ) {
		$entry->subject = Task_Class::g()->get( array( 'id' => $entry->element_id ), true );
		$entry->subject->data['formatted_content'] = $entry->subject->data['title'];
		$entry->project_name                       = $entry->subject->data['title'];

		switch ( $entry->action_type ) {
			case TM_NOTIFY_ACTION_ANSWER:
				$entry->content = sprintf( '<strong>%s</strong> answer to %s in the project #%s', $entry->action_user, implode( ', ', $entry->notified_users ), $entry->element_id );
				break;
		}

		$entry->link = admin_url( 'admin.php?page=wpeomtm-dashboard&task_id=' . $entry->element_id . '&notification=' . $entry->ID );

		return $entry;
	}

	public function load_additional_data_notification_for_point( $entry ) {
		$entry->subject                            = Point_Class::g()->get( array( 'id' => $entry->element_id ), true );

		if ( ! $entry->subject ) {
			return $entry;
		}

		$entry->subject->data['formatted_content'] = $entry->subject->data['content'];

		$task = Task_Class::g()->get( array( 'id' => $entry->subject->data['post_id'] ), true );
		$entry->project_name                       = $task->data['title'];

		switch ( $entry->action_type ) {
			case TM_NOTIFY_ACTION_COMPLETE:
				$entry->content = sprintf( '<strong>%s</strong> completed the task #%s.', $entry->action_user, $entry->element_id );
				break;
			case TM_NOTIFY_ACTION_WAITING_FOR:
				$entry->content = sprintf( 'An action is required for you on the task #<strong>%s</strong>', $entry->element_id );
				break;
			case TM_NOTIFY_NEW_COMMENT:
				$entry->content = sprintf( '%s add new comment on the task #<strong>%s</strong>', $entry->action_user, $entry->element_id );
				break;
			case TM_NOTIFY_CREATE_TASK:
				$entry->content = sprintf( '%s Add new task on the project #<strong>%s</strong>', $entry->action_user, $entry->subject->data['post_id'] );
				break;
			case TM_NOTIFY_MENTION:
				$entry->content = sprintf( '%s mentionned you for the task #<strong>%s</strong>', $entry->action_user, $entry->subject->data['post_id'] );
				break;
		}

		$entry->link = admin_url( 'admin.php?page=wpeomtm-dashboard&task_id=' . $entry->subject->data['post_id'] . '&point_id=' . $entry->subject->data['id'] . '&notification=' . $entry->ID );

		return $entry;
	}

	public function load_additional_data_notification_for_comment( $entry ) {

		$entry->subject                            = Task_Comment_Class::g()->get( array( 'id' => $entry->element_id ), true );

		if ( ! $entry->subject ) {
			return $entry;
		}

		$entry->subject->data['formatted_content'] = $entry->subject->data['content'];

		$task = Task_Class::g()->get( array( 'id' => $entry->subject->data['post_id'] ), true );
		$entry->project_name                       = '#' . $task->data['id'] . ' ' . $task->data['title'];

		switch ( $entry->action_type ) {
			case TM_NOTIFY_NEW_COMMENT:
				$entry->content = sprintf( '%s add new comment on the task #<strong>%s</strong>', $entry->action_user, $entry->element_id );
				break;
			case TM_NOTIFY_MENTION:
				$entry->content = sprintf( '%s mentionned you for the task #<strong>%s</strong>', $entry->action_user, $entry->subject->data['parent_id'] );
				break;
		}

		$entry->link = admin_url( 'admin.php?page=wpeomtm-dashboard&task_id=' . $entry->subject->data['post_id'] . '&point_id=' . $entry->subject->data['parent_id'] . '&comment_id=' . $entry->subject->data['id'] . '&notification=' . $entry->ID );

		return $entry;
	}

	public function send_notification_followers_are_tags( $users_id = array(), $task_id = 0, $point_id = 0, $comment_id = 0 ){
		if( empty( $users_id ) || $task_id == null || $point_id == null ){
			return false;
		}

		$data = array();

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$point = Point_Class::g()->get(
			array(
				'id' => $point_id,
			),
			true
		);

		$comments = Task_Comment_Class::g()->get(
			array(
				'parent' => $point_id,
			),
			true
		);

		$sender_data = wp_get_current_user();
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name   = get_bloginfo( 'name' );

		$recipients = array();
		if( $users_id[ 0 ] == "-1" ){
			$users = Follower_Class::g()->get( // Auto complete
				array(
					'role' => array(
						'administrator',
					),
				)
			);
			$users_id = [];
			foreach( $users as $user ){
				$users_id[] = $user->data[ 'id' ];
			}
		}

		foreach ( $users_id as $user_id ) {
			$user_info    = get_userdata( $user_id );
			$recipients[] = $user_info;

			Notify_Class::g()->add_notification( $user_id, get_current_user_id(), $task->data['user_info']['affected_id'], $comment_id, 'comment', TM_NOTIFY_MENTION );
		}

		$subject = 'Task Manager: ';

		// translators: La tâche #150 Nouvelle tâche.
		$subject .= sprintf( __( 'The task #%1$d %2$s', 'task-manager' ), $task->data['id'], $task->data['title'] );

		$body = '<p>' . __( 'This mail has been send automaticly', 'task-manager' ) . '</p>';

		$body .= '<h2>';
		// translators: #150 Nouvelle tâche send by username (username@domain.com).
		$body .= sprintf( __( 'You have been notified by %3$s (%4$s)', 'task-manager' ), $task->data['id'], $task->data['title'], $sender_data->user_login, $sender_data->user_email );
		$body .= '</h2>';

		//$body  = apply_filters( 'task_points_mail', $body, $task ); Listes tous les points de la tache
		//$
		$body .= '<h4>' . __( 'Task ', 'task-manager' ) . '(#' . $task->data['id'] . ') <br /><i>-' . $task->data['title'] . '</i>' . '</h4>';
		$body .= '<h4>' . __( 'Point ', 'task-manager' ) . '(#' . $point->data['id'] . ') <br /><i>-' . $point->data[ 'content' ] . '</i>' . '</h4>';
		$body .= '<h4>' . __( 'Comment ', 'task-manager' ) . '(#' . $comment_id . ')' . '</h4>';

		$body .= '<ul>';
		foreach( $comments as $comment ){
			if( $comment->data[ 'id' ] == $comment_id ){
				$body .= '<li> -> <b> #' . $comment->data[ 'id' ] . '</b> <br /><i>' . $comment->data[ 'content' ] . '</i></li>';
			}else{
				$body .= '<li> #' . $comment->data[ 'id' ] . ' <br /><i>' . $comment->data[ 'content' ] . '</i></li>';
			}
		}
		$body .= '</ul>';

		$body .= '<ul>';
		if ( ! empty( $task->data['parent_id'] ) ) {
			$body .= '<li><a href="' . admin_url( 'post.php?action=edit&post=' . $task->data['parent_id'] ) . '">' . __( 'Customer link', 'task-manager' ) . '</a></li>';
		}
		$body .= '<li><a href="' . admin_url( 'admin.php?page=wpeomtm-dashboard&point_id=' . $point_id ) . '">' . __( 'Point link', 'task-manager' ) . '</a></li>';
		$body .= '</ul>';

		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );
		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		$recipients = apply_filters( 'task_manager_notify_send_notification_recipients', $recipients, $task, $data );
		$subject    = apply_filters( 'task_manager_notify_send_notification_subject', $subject, $task, $data );
		$body       = apply_filters( 'task_manager_notify_send_notification_body', $body, $task, $data );

		if ( ! empty( $recipients ) && ! empty( $subject ) && ! empty( $body ) ) {
			if ( ! empty( $recipients ) ) {
				foreach ( $recipients as $recipient ) {
					$cloned_body = $body;
					if ( in_array( 'administrator', $recipient->roles, true ) ) {
						$cloned_body = apply_filters( 'task_manager_notify_send_notification_body_administrator', $body, $task, $data );
					}

					if ( wp_mail( $recipient->user_email, $subject, $cloned_body, $headers ) ) {
						\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s success', $task->data['id'], $recipient->user_email ), 'task-manager' );
					} else {
						\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s failed', $task->data['id'], $recipient->user_email ), 'task-manager', 'EO_ERROR' );
					}
				}
			}
		} else {
			\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d failed', $task->data['id'] ), 'task-manager', 'EO_ERROR' );
		}
	}

	public function add_notification( $to_user_id, $action_user_id, $notified_users_id, $element_id, $type_of_element, $action_type ) {
		$data = array(
			'post_status' => 'publish',
			'post_author' => $to_user_id,
			'post_title' => 'Notification',
			'post_type'  => 'wpeo-notification',
		);

		$post_id = wp_insert_post( $data );

		add_post_meta( $post_id, '_tm_action_user_id', $action_user_id );
		add_post_meta( $post_id, '_tm_notified_users_id', $notified_users_id );
		add_post_meta( $post_id, '_element_id', $element_id );
		add_post_meta( $post_id, '_type_of_element', $type_of_element );
		add_post_meta( $post_id, '_action_type', $action_type );
		add_post_meta( $post_id, 'read', false );
	}
}

Notify_Class::g();
