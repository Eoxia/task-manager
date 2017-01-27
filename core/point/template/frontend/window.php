<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div id="wpeo-task-account-window" class="<?php echo empty( $show ) ? 'wpeo-no-display' : ''; ?>">
	<div class="wpeo-task-account-window-content">
		<ul>
			<?php require( wpeo_tag_template_ctr::get_template_part( WPEOMTM_POINT_DIR, WPEOMTM_POINT_TEMPLATES_MAIN_DIR, 'frontend', 'list', 'point-time' ) ); ?>
		</ul>
		
		<form class="wpeo-task-account-form-comment" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
			<?php wp_nonce_field( 'wpeo_nonce_create_point_time_frontend_' . $_POST['point_id'] ); ?>
			<input type="hidden" name="action" value="create_point_time_client" />
		    <input type="hidden" name="point_time[post_id]" value="<?php echo $_POST['task_id']; ?>" />
		    <input type="hidden" name="point_time[parent_id]" value="<?php echo $_POST['point_id']; ?>" />
		    <input type="hidden" name="point_time[author_id]" value="<?php echo get_current_user_id(); ?>" />
		    <input type="hidden" class="point_time_id" name="point_time[id]" value="" />
			<textarea name="point_time[content]"></textarea>
			<input class="button-primary" type="button" value="<?php _e( 'Add comment', 'task-manager' ); ?>" >
		</form>
	</div>
</div>