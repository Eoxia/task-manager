<?php
/**
 * For each user for display it.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package module/user
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php if ( ! empty( $users ) ) :
	foreach ( $users as $user ) : ?>
		<li class="action-attribute tooltip hover"
				aria-label="<?php echo esc_attr( $user->data['displayname'] ); ?>"
				data-action="switch_owner"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'switch_owner' ) ); ?>"
				data-task-id="<?php echo esc_attr( $task_id ); ?>"
				data-id="<?php echo esc_attr( $user->data['id'] ); ?>">
			<?php do_shortcode( '[task_avatar ids="' . $user->data['id'] . '" size="32"]' ); ?>
		</li>
	<?php endforeach;
endif; ?>
