<?php
/**
 * Affichage d'un réglage de temps rapide.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="item">
	<li class="task"><?php echo esc_html( '#' . $quick_time['displayed']['task']->data['id'] . ' ' . $quick_time['displayed']['task']->data['title'] ); ?></li>
	<li class="point wpeo-tooltip-event"
		aria-label="<?php echo esc_attr( '#' . $quick_time['displayed']['point']->data['id'] . ' ' . $quick_time['displayed']['point']->data['content'] ); ?>"><?php echo esc_html( $quick_time['displayed']['point_fake_content'] ); ?></li>
	<li class="content"><?php echo ! empty( $quick_time['content'] ) ? esc_html( $quick_time['content'] ) : __( 'No comment', 'task-manager' ); ?></li>
	<li class="actions">
		<div class="action-delete wpeo-button button-progress button-grey button-square-20 button-rounded"
			data-action="remove_config_quick_time"
			data-message-delete="<?php echo esc_attr_e( 'Delete this preset ?', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'remove_config_quick_time' ) ); ?>"
			data-key="<?php echo esc_attr( $key ); ?>"
			data-task-id="<?php echo esc_attr( $quick_time['task_id'] ); ?>"
			data-point-id="<?php echo esc_attr( $quick_time['point_id'] ); ?>">
			<span class="button-icon fa fa-times" aria-hidden="true"></span>
		</div>
	</li>
</ul>
