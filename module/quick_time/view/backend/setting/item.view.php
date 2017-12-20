<?php
/**
 * Affichage d'un réglage de temps rapide.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
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
	<li class="task"><?php echo esc_html( '#' . $quick_time['displayed']['task']->id . ' ' . $quick_time['displayed']['task']->title ); ?></li>
	<li class="point wpeo-tooltip-event"
		aria-label="<?php echo esc_attr( '#' . $quick_time['displayed']['point']->id . ' ' . $quick_time['displayed']['point']->content ); ?>"><?php echo esc_html( $quick_time['displayed']['point_fake_content'] ); ?></li>
	<li class="content"><?php echo ! empty( $quick_time['content'] ) ? esc_html( $quick_time['content'] ) : __( 'No comment', 'task-manager' ); ?></li>
	<li class="actions">
		<div class="action-delete wpeo-button button-progress button-red"
			data-action="remove_config_quick_time"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'remove_config_quick_time' ) ); ?>"
			data-key="<?php echo esc_attr( $key ); ?>">
			<span class="fa fa-times-circle" aria-hidden="true"></span>
		</div>
	</li>
</ul>
