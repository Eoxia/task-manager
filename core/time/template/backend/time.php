<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-point-comment wpeo-point-comment-<?php echo $time->id; ?>" data-id="<?php echo $time->id; ?>">
	<li class="avatar">
		<?php echo apply_filters( 'task_avatar', '', $time->author_id, 26, true ); ?>
	</li>
	<li class="date">
		<?php
		_e( 'On', 'task-manager' );
		echo ' '; comment_date( get_option( 'date_format' ), $time->id ); echo ' ';
		_e( 'at', 'task-manager' );
		echo ' '; comment_date( get_option( 'time_format' ), $time->id );
		?>
	</li>
	<li class="action">
		<?php echo $time->option['time_info']['elapsed']; ?>
		<span class="dashicons dashicons-clock"></span>
		<?php if ( get_current_user_id() == $time->author_id ): ?>
			<span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_get_point_time_' . $time->id ); ?>" class="dashicons dashicons-edit wpeo-point-time-edit-btn"></span>
			<span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_point_time_' . $time->id ); ?>" class="dashicons dashicons-no wpeo-send-point-time-to-trash"></span>
		<?php endif; ?>
	</li>
	<li class="comment"><?php echo nl2br( stripslashes( $time->content ) ); ?></li>
</ul>
