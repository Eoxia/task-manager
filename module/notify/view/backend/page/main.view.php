<?php
/**
 * Main view for the notification page.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 3.1.0
 * @version 3.1.0
 * @copyright 2015-2020 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="tm-wrap tm-main-container">
	<div class="tm-notification-page">
		<div class="bloc-filters">
			<ul class="filters">
				<li class="active"><a href="#"><span class="count">15</span>Unread</a></li>
				<li><a href="#"><span class="count">169</span>Read</a></li>
				<li><a href="#"><span class="count">15</span>All notifications</a></li>
			</ul>

			<hr />

			<ul class="filters">
				<li><a href="#"><span class="count">15</span>sav.digirisk.com</a></li>
				<li><a href="#"><span class="count">169</span>Task Manager 1.8.0</a></li>
				<li><a href="#"><span class="count">15</span>Super projet kdzaodkop....</a></li>
				<li><a href="#"><span class="count">15</span>sav.digirisk.com</a></li>
				<li><a href="#"><span class="count">169</span>Task Manager 1.8.0</a></li>
				<li><a href="#"><span class="count">15</span>Super projet kdzaodkop....</a></li>
				<li><a href="#"><span class="count">15</span>sav.digirisk.com</a></li>
				<li><a href="#"><span class="count">169</span>Task Manager 1.8.0</a></li>
				<li><a href="#"><span class="count">15</span>Super projet kdzaodkop....</a></li>
			</ul>
		</div>

		<div class="notification-container">
			<?php
			if ( ! empty( $data ) ) :
				foreach ( $data as $entry ) :
					?>
					<div class="notification-content">
						<div class="header">
							<div class="icon">
								<i class="fas fa-bell"></i>
							</div>

							<div class="avatars">
								<div class="avatar action">
									<?php echo do_shortcode( '[task_avatar ids="' . $entry['action_user_id']. '" size="40"]' ) ?>
								</div>

								<?php
								if ( ! empty( $entry['notified_users_id'] ) ) :
									foreach ( $entry['notified_users_id'] as $notified_user_id ) :
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
								<?php echo $entry['content']; ?>
								</p>
							</div>

							<div class="subject">
								<p>
									<?php echo $entry['subject']->data['content']; ?>
								</p>
							</div>
						</div>
					</div>
					<?php
				endforeach;
			else:
			endif;
			?>
		</div>
	</div>
</div>
