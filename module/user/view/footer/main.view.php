<?php
/**
 * The users view at the bottom of a task.
 *
 * @package module/user
 * @since 0.1
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div 	class="wpeo-bloc-user action-attribute"
			data-action="load_edit_mode_user"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_mode_user' ) ); ?>"
			data-id="<?php echo esc_attr( $element->id ); ?>"
			data-module="user"
			data-before-method="beforeLoadEditModeUser">

			<ul>
				<?php if ( ! empty( $users ) ) :
					foreach ( $users as $user ) : ?>
						<li><?php echo esc_html( $user->display_name ); ?>
					<?php endforeach;
				endif; ?>
			</ul>
</div>
