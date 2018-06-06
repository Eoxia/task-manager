<?php
/**
 * La vue du header d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><li class="tm-task-display-method-buttons">
	<span class="dashicons dashicons-screenoptions list-display active wpeo-tooltip-event"
		aria-label="<?php echo esc_attr_e( 'Edit display', 'task-manager' ); ?>"></span>

	<span class="action-attribute dashicons dashicons-editor-ul grid-display wpeo-tooltip-event"
		data-action="load_last_activity"
		aria-label="<?php echo esc_attr_e( 'Activity display', 'task-manager' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
		data-tasks-id="<?php echo esc_attr( $task->data['id'] ); ?>"></span>
</li>
