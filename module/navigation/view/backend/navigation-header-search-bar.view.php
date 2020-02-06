<li class="tm-notification tm-wrap">
	<div class="wpeo-dropdown dropdown-right">
		<div class="dropdown-toggle wpeo-button button-main"><span><?php echo $number_notifications; ?></span><i class="fas fa-bell"></i></div>
		<ul class="dropdown-content notification-container" style="width: 700px;">
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
		</ul>
	</div>
</li>
