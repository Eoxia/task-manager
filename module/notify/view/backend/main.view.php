<?php
/**
 * Affichage de la popup pour gérer les notifications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/list-admin', array(
	'followers' => $followers,
	'task' => $task,
	'affected_id' => $affected_id,
) );

echo apply_filters( 'task_manager_popup_notify_after', '', $task ); ?>

<button class="action-input"
			data-parent="popup"
			data-action="send_notification"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'send_notification' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>"><?php echo esc_html_e( 'Send notification', 'task-manager' ); ?></button>
