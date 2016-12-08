<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="wpeo-task-form-point-time">
	<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
		 <!-- For admin-ajax -->
		 <?php wp_nonce_field( 'wpeo_nonce_create_point_time_' . $element->id ); ?>
	    <input type="hidden" name="point_time[post_id]" value="<?php echo $element->post_id; ?>" />
	    <input type="hidden" class="wpeo-point-id" name="point_time[parent_id]" value="<?php echo $element->id; ?>" />
	    <input type="hidden" class="wpeo-point-time-id" name="point_time_id" value="0" />
	    <input type="hidden" name="point_time[author_id]" value="<?php echo get_current_user_id(); ?>" />
	    <input type="hidden" class="wpeo-point-time-time" name="point_time[time]" value="<?php echo current_time( 'H:i:s' ); ?>" />

		<div class="wpeo-task-form-input">
			<div>
				<?php echo apply_filters( 'task_window_time_date', '' ); ?>
				<textarea name="point_time[content]" class="wpeo-point-comment"></textarea>
				<?php echo apply_filters( 'task_window_time', '' ); ?>
				<input type="button" class="button-primary wpeo-submit" value="+" />
			</div>
			<!--<span class="wpeo-open-point-time-form"><?php _e( 'Click here to add a comment', 'task-manager' ); ?></span>-->
		</div>
	</form>
</div>
