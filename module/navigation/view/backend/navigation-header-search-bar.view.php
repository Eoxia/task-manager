<li class="tm-notification tm-wrap">
	<div class="wpeo-dropdown dropdown-right dropdown-padding-0">
		<div class="dropdown-toggle wpeo-button button-transparent button-square-40 <?php echo $number_notifications != 0 ? 'notification-active' : ''; ?>">
			<i class="button-icon fas fa-bell"></i>
			<span class="notification-number <?php echo $number_notifications != 0 ? 'notification-number-active' : ''; ?>"><?php echo $number_notifications; ?></span>
		</div>
		<div class="dropdown-content notification-container">
			<?php
			$i = 0;
			if ( ! empty( $notifications ) ) :
				foreach ( $notifications as $notification ) :
					$i++;

					if ( $i > 5 ) :
						break;
					endif;

					\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/page/item', array(
						'entry' => $notification,
					) );
				endforeach;
			endif;
			?>
			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=tm-notification' ) ); ?>" class="notification-content">

				<div class="content">
					<div class="main-content">
						<p>
							<?php esc_html_e( 'See all notifications', 'task-manager' ); ?>
						</p>
					</div>
				</div>
			</a>
		</div>
	</div>
</li>
