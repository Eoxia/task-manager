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

<ul>
<?php if ( ! empty( $users ) ) :
	foreach ( $users as $user ) : ?>
		<li data-id="<?php echo esc_attr( $user->id ); ?>">
			<?php echo esc_html( $user->displayname ); ?>
		</li>
	<?php endforeach;
endif; ?>
</ul>

<i  class="fa fa-floppy-o save-user" aria-hidden="true"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'save_user' ) ); ?>"
		data-id="<?php echo esc_attr( $task->id ); ?>"></i>
