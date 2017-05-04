<?php
/**
 * The owner view of the task header.
 *
 * @package module/user
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="toggle"
		data-parent="toggle"
		data-target="content"
		data-action="load_edit_mode_owner"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_mode_owner' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task_id ); ?>">

	<div class="action">
		<?php echo do_shortcode( '[task_avatar ids="' . $owner_id . '" size="32"]' ); ?>
	</div>

	<ul class="content right">
	</ul>
</div>
