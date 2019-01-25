<?php
/**
 * Le bouton pour envoyer la notification.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<button class="action-input send-notification"
data-parent="wpeo-modal"
data-action="send_notification"
data-nonce="<?php echo esc_attr( wp_create_nonce( 'send_notification' ) ); ?>"
data-id="<?php echo esc_attr( $task->data['id'] ); ?>"><?php echo esc_html_e( 'Send notification', 'task-manager' ); ?></button>
