<?php
/**
 * Affichage de la popup pour gérer les notifications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>
	<h2>
		<?php esc_html_e( 'Teams', 'task-manager' ); ?>
		(<span class="selected-number"><?php echo esc_html( count( $affected_id ) ); ?></span>/<span class="total-number"><?php echo esc_html( count( $followers ) ); ?></span>)
	</h2>

	<ul class="list-follower wpeo-ul-users">
		<?php
		if ( ! empty( $followers ) ) :
			foreach ( $followers as $follower ) :
				?>
				<li class="follower <?php echo ( in_array( $follower->id, $task->user_info['affected_id'], true ) || $follower->id === $task->user_info['owner_id'] ) ? 'active' : ''; ?>" data-id="<?php echo esc_attr( $follower->id ); ?>" style="width: 50px; height: 50px;">
					<?php echo do_shortcode( '[task_avatar ids=' . $follower->id . ']' ); ?>
				</li>
				<?php
			endforeach;
		endif;
		?>
		<input type="hidden" name="users_id" value="<?php echo esc_attr( implode( ',', $affected_id ) ); ?>" />
	</ul>
</div>

<?php echo apply_filters( 'task_manager_popup_notify_after', '', $task ); ?>

<button class="action-input send-notification"
			data-parent="popup"
			data-action="send_notification"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'send_notification' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>"><?php echo esc_html_e( 'Send notification', 'task-manager' ); ?></button>
