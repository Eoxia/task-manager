<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<form class="form wpeo-edit-point" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
	<?php wp_nonce_field( 'wpeo_nonce_edit_point_' . $point->id ); ?>
	<input type="hidden" name="action" value="edit_point" />
	<input type="hidden" name="point[id]" value="<?php echo $point->id; ?>" />
	<input type="hidden" name="point[post_id]" value="<?php echo $point->post_id; ?>" />
	<input type="hidden" name="point[author_id]" value="<?php echo !empty( $point->author_id ) ? $point->author_id : get_current_user_id(); ?>" />
	<li class="wpeo-task-li-point">
		<ul>
			<li>
				<?php echo apply_filters( 'point_action_before', '', $point ); ?>
				<span class="wpeo-block-id">#<?php echo $point->id ?></span>
			</li>
			<li class="wpeo-point-input">
				<input type="hidden" name="point[content]" value="" />
				<div data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_point_' . $point->id ); ?>" class="wpeo-point-contenteditable" contenteditable="true"><?php echo stripslashes($point->content); ?></div>
			</li>
			<li>
				<span class="dashicons dashicons-clock"></span>
				<span class="wpeo-time-in-point"><?php echo ! empty( $point->time_info['elapsed'] ) ? $point->time_info['elapsed'] : 0; ?></span>
				<?php echo apply_filters( 'point_action_after', '', $point ); ?>
			</li>
		</ul>
		<input type="hidden" class="submit-form-point-edit_point_callback" />
	</li>
</form>
