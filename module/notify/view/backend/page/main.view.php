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
			<div class="filters">
				<a href="<?php echo admin_url( 'admin.php?page=tm-notification&mode=unread' ); ?>" class="<?php echo $mode == 'unread' ? 'active' : ''; ?>"><span class="count"><?php echo $count_unread; ?></span>Unread</a>
				<a href="<?php echo admin_url( 'admin.php?page=tm-notification&mode=read' ); ?>" class="<?php echo $mode == 'read' ? 'active' : ''; ?>"><span class="count"><?php echo $count_read; ?></span>Read</a>
				<a href="<?php echo admin_url( 'admin.php?page=tm-notification&mode=both' ); ?>" class="<?php echo $mode == 'both' ? 'active' : ''; ?>"><span class="count"><?php echo $count_read + $count_unread; ?></span>All notifications</a>
			</div>

			<hr />

			<div class="filters">
				<?php
				if ( ! empty( $notifications_by_elements ) ) :
					foreach ( $notifications_by_elements as $key => $element ) :
						?><a href="<?php echo admin_url( 'admin.php?page=tm-notification&parent=' . $key ); ?>" class="<?php echo $parent == $key ? 'active' : ''; ?>"><span class="count"><?php echo esc_attr( $element['count'] ); ?></span><?php echo esc_attr( $element['title'] ); ?></a><?php
					endforeach;
				endif;
				?>
			</div>
		</div>

		<div class="notification-container">
			<?php
			if ( ! empty( $data ) ) :
				foreach ( $data as $entry ) :
					\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/page/item', array(
						'entry' => $entry,
					) );
				endforeach;
			else:
				?>
				<div class="notification-content">

					<div class="content">
						<div class="main-content">
							<p>
								<?php esc_html_e( 'No notification in this section', 'task-manager' ); ?>
							</p>
						</div>

					</div>
				</div>
				<?php
			endif;
			?>
		</div>
	</div>
</div>
