<li data-id="<?php echo $estimated_time->id; ?>">
	<?php echo get_avatar( $estimated_time->author_id, 20, 'blank' ); ?>
	<?php
	 	$user = $wp_project_user_controller->show( $estimated_time->author_id );
		echo $user->displayname;
	?>,
     	<span class="dashicons dashicons-clock"></span>
	<?php echo $estimated_time->option['estimated_time']; ?>
	<span class="delete-estimated-time dashicons dashicons-dismiss"></span>
</li>
