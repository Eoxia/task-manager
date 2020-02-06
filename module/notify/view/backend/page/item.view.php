<a class="notification-content" href="<?php echo esc_attr( $entry->link ); ?>">
	<div class="time" style="float: right;">
		<?php echo sprintf( __( 'Il y a %s', 'task-manager' ), esc_html( $entry->time ) ); ?>
	</div>

	<div class="header">
		<div class="icon">
			<i class="fas fa-bell"></i>
		</div>



		<div class="avatars">
			<div class="avatar action">
				<?php echo do_shortcode( '[task_avatar ids="' . $entry->action_user_id. '" size="40"]' ) ?>
			</div>

			<?php
			if ( ! empty( $entry->notified_users_id ) ) :
				foreach ( $entry->notified_users_id as $notified_user_id ) :
					?>
					<div class="avatar notified">
						<?php echo do_shortcode( '[task_avatar ids="' . $notified_user_id. '" size="40"]' ) ?>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>

	<div class="content">
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
	</div>
</a>
