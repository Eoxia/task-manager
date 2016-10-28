<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-point-comment wpeo-point-comment-<?php echo $point_time->id; ?>" data-id="<?php echo $point_time->id; ?>">
	<li class="wpeo-point-comment-author">
		<div><?php echo get_avatar( $point_time->author_id, 32 ); ?></div>
		<div>
			<?php
			echo $list_user_in[$point_time->author_id]->user_nicename . ', ';
			_e( 'On', 'task-manager' );
			echo ' '; comment_date( get_option( 'date_format' ), $point_time->id ); echo ' ';
			_e( 'at', 'task-manager' );
			echo ' '; comment_date( get_option( 'time_format' ), $point_time->id );
			?>
		</div>
	</li>
	<li class="wpeo-point-comment-time">
		<div>
			<strong><?php echo $point_time->option['time_info']['elapsed']; ?></strong>
			<span class="dashicons dashicons-clock"></span>
		</div>
		<div>
			<?php if ( get_current_user_id() == $point_time->author_id ):
				?> <span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_get_point_time_' . $point_time->id ); ?>" class="dashicons dashicons-edit wpeo-point-time-edit-btn"></span> <?php
			endif; ?>
		</div>
	</li>
	<li class="wpeo-point-comment-content">
		<?php echo stripslashes( $point_time->content ); ?>
	</li>
</ul>
