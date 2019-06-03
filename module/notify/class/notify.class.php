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

	public function send_notification_followers_are_tags( $users_id = array(), $task_id = 0, $point_id = 0, $comment_id = 0 ){
		if( empty( $users_id ) || $task_id == null || $point_id == null ){
			return false;
		}

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
}

Notify_Class::g();
