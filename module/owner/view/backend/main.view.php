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

<img 	class="avatar avatar-32 action-attribute"
			data-action="load_edit_mode_owner"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_mode_owner' ) ); ?>"
			data-task-id="<?php echo esc_attr( $task_id ); ?>"
			src="<?php echo esc_attr( $avatar_url ); ?>" height="32" width="32" />
			<div class="wpeo-avatar-initial"><span><?php echo $user->initial; ?></span></div>

<ul class="users">
</ul>
