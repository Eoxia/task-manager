<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-point-comment-edit wpeo-point-comment wpeo-point-comment-<?php echo $point_time->id; ?>" data-id="<?php echo $point_time->id; ?>">
	<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
		<?php wp_nonce_field( 'ajax_create_point_time' ); ?>
		<input type="hidden" name="point_time[post_id]" value="<?php echo $point_time->post_id; ?>" />
		<input type="hidden" class="wpeo-point-time-id" name="point_time_id" value="<?php echo $point_time_id; ?>" />
		<input type="hidden" class="wpeo-point-time-id" name="point_time[parent_id]" value="<?php echo $point_time->parent_id; ?>" />

		<li>
			<?php echo get_avatar( $point_time->author_id, 32 ); ?>
		</li>
		<li>
			<div class="wpeo-input-date">
				<span class="dashicons dashicons-calendar-alt"></span> <input class="isDate" type="text" name="point_time[date]" value="<?php echo $date[0]; ?>" />
			</div>
		</li>
		<li>
			<input type="text" name="point_time[content]" class="wpeo-point-comment" value="<?php echo $point_time->content; ?>" placeholder="<?php _e( 'Enter your comment here...', 'task-manager' ); ?>" />
		</li>
		<li>
			<span class="dashicons dashicons-clock"></span>
			<input type="text" class="wpeo-point-time-elapsed" name="point_time[option][time_info][elapsed]" value="<?php echo $point_time->option['time_info']['elapsed']; ?>" />

			<input type="button" class="button-primary wpeo-submit" value="Save"/>
		</li>


	</form>
</ul>
