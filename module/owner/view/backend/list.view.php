<?php
/**
 * For each user for display it.
 *
 * @package module/user
 * @since 0.1
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php if ( ! empty( $users ) ) :
	foreach ( $users as $user ) : ?>
		<li class="action-attribute tooltip hover"
				aria-label="<?php echo esc_attr( $user->displayname ); ?>"
				data-action="switch_owner"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'switch_owner' ) ); ?>"
				data-task-id="<?php echo esc_attr( $task_id ); ?>"
				data-id="<?php echo esc_attr( $user->id ); ?>">
			<?php do_shortcode( '[task_avatar ids="' . $user->id . '" size="32"]' ); ?>
		</li>
	<?php endforeach;
endif; ?>
