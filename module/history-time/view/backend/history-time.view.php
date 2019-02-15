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

<li class="list-element" data-id="<?php echo esc_attr( $history_time->data['id'] ); ?>">
	<ul>
		<li class="avatar"><?php echo get_avatar( $history_time->data['author_id'], 16 ); ?></li>
		<li class="author"><?php echo esc_html( $history_time->data['author']->display_name ); ?></li>

		<li class="date">
			<?php if ( 'recursive' === $history_time->data['custom'] ) : ?>
				<?php esc_html_e( 'Repeat monthly', 'task-manager' ); ?>
			<?php else : ?>
				<span class="dashicons dashicons-calendar-alt"></span>
				<?php echo esc_html( substr( $history_time->data['due_date']['rendered']['date_human_readable'], 0, strlen( $history_time->data['due_date']['rendered']['date_human_readable'] ) - 8 ) ); ?>
			<?php endif; ?>
		</li>
		<li class="time">
			<span class="dashicons dashicons-clock"></span>
			<?php echo esc_html( $history_time->data['estimated_time'] ); ?>
		</li>
		<li class="time">
			<?php
				/* translators: */
				echo esc_html( sprintf( __( '( %smin )', 'task-manager' ), $history_time->data['estimated_time'] ) );
			?>
		</li>
		<li class="delete action-delete"
				data-message-delete="<?php esc_attr_e( 'Are you sure you want to delete this element ?', 'task-manager' ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_history_time' ) ); ?>"
				data-action="delete_history_time"
				data-id="<?php echo esc_attr( $history_time->data['id'] ); ?>">
			<span class="dashicons dashicons-no-alt"></span>
		</li>
	</ul>
</li>
