<?php
/**
 * Affiches un historique.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="list-element" data-id="<?php echo esc_attr( $history_time->id ); ?>">
	<ul>
		<li class="avatar"><?php echo get_avatar( $history_time->author_id, 16 ); ?></li>
		<li class="author"><?php echo esc_html( $history_time->author->display_name ); ?></li>

		<li class="date">
			<?php if ( $history_time->repeat ) : ?>
				<?php esc_html_e( 'Repeat monthly', 'task-manager' ); ?>
			<?php else : ?>
				<span class="dashicons dashicons-calendar-alt"></span>
				<?php echo esc_html( substr( $history_time->due_date['date_human_readable'], 0, strlen( $history_time->due_date['date_human_readable'] ) - 8 ) ); ?>
			<?php endif; ?>
		</li>
		<li class="time">
			<span class="dashicons dashicons-clock"></span>
			<?php echo esc_html( $history_time->estimated_time ); ?>
		</li>
		<li class="time">
			<?php echo esc_html( sprintf( __( '( %smin )', 'task-manager' ), $history_time->estimated_time ) ); ?>
		</li>
		<li class="delete action-delete"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_history_time' ) ); ?>"
				data-action="delete_history_time"
				data-id="<?php echo esc_attr( $history_time->id ); ?>">
			<span class="dashicons dashicons-no-alt"></span>
		</li>
	</ul>
</li>
