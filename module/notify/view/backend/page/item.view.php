<a class="notification-content" href="<?php echo esc_attr( $entry->link ); ?>">

	<span class="notification-close"><i class="fas fa-times"></i></span>

	<div class="avatars">
		<div class="avatar action">
			<?php echo do_shortcode( '[task_avatar ids="' . $entry->action_user_id. '" size="30"]' ) ?>
		</div>

		<?php
		if ( ! empty( $entry->notified_users_id ) ) :
			foreach ( $entry->notified_users_id as $notified_user_id ) :
				?>
				<div class="avatar notified">
					<?php echo do_shortcode( '[task_avatar ids="' . $notified_user_id. '" size="30"]' ) ?>
				</div>
			<?php
			endforeach;
		endif;
		?>
	</div>

	<div class="content">
		<div class="project">
			<p>
				<?php echo esc_attr( $entry->project_name ); ?>
			</p>
		</div>
		<div class="main-content">
			<p>
				<?php echo $entry->content; ?>
			</p>
		</div>

		<div class="subject">
			<p>
				<?php echo $entry->subject->data['formatted_content']; ?>
			</p>
		</div>
		<div class="time">
			<?php echo sprintf( __( 'Il y a %s', 'task-manager' ), esc_html( $entry->time ) ); ?>
		</div>
	</div>
</a>
