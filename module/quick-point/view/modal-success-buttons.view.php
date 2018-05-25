<?php
/**
 * Bouton de la modal quand c'est un succés.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-button button-blue action-attribute"
		data-action="load_modal_quick_point"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_modal_quick_point' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>"
		data-reload="true">
	<span><?php esc_html_e( 'Add another quick point', 'task-manager' ); ?></span>
</div>

<div class="wpeo-button button-main modal-close">
	<span><?php esc_html_e( 'Close modal', 'task-manager' ); ?></span>
</div>
