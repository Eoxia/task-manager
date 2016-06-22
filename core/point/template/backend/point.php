<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<form class="form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
	<?php wp_nonce_field( 'ajax_edit_point_' . $point->id ); ?>
	<input type="hidden" name="point[post_id]" value="<?php echo $object_id; ?>" />
	<input type="hidden" name="point[author_id]" value="<?php echo !empty( $point->author_id ) ? $point->author_id : get_current_user_id(); ?>" />
	<li class="wpeo-task-li-point" data-id="<?php echo $point->id ?>">
		<ul>
			<li>
				<input type="hidden" name="point[id]" value="<?php echo $point->id; ?>" />
				<!-- Dashicons pour Drag and Drop -->

				<?php echo apply_filters( 'point_action_before', '', $point ); ?>

				<!-- L'id du point -->
				<span class="wpeo-block-id">#<?php echo $point->id ?></span>
			</li>

			<li class="wpeo-point-input" data-nonce="<?php echo wp_create_nonce( 'ajax_load_dashboard_frontend_' . $point->id ); ?>">
				<!-- Le contenu du point -->
				<textarea <?php echo $disabled_filter; ?> data-nonce="<?php echo wp_create_nonce( 'ajax_load_dashboard_' . $point->id ); ?>" class="wpeo-point-textarea" name="point[content]"><?php echo stripslashes($point->content); ?></textarea>
			</li>

			<li>
				<span class="dashicons dashicons-clock"></span>
				<span class="wpeo-time-in-point"><?php echo $point->option['time_info']['elapsed']; ?></span>
				<?php echo apply_filters( 'point_action_after', '', $point ); ?>
			</li>
		</ul>
	</li>

</form>
