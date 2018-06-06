<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wrap tm-wrap wpeo-wrap">
	<div class="tm-dashboard-header">
		<?php echo apply_filters( 'tm_posts_metabox_project_dashboard', '', $post, $tasks ); // WPCS: XSS ok. ?>
		<p class="alignright"><?php esc_html_e( 'Total time past', 'task-manager' ); ?> : <?php echo esc_html( $total_time_elapsed ); ?> / <?php echo esc_html( $total_time_estimated ); ?></p>
	</div>

	<?php	if ( ! empty( $tasks ) ) : ?>
		<?php foreach ( $tasks as $key => $data ) : ?>
			<?php if ( ! empty( $data['title'] ) ) : ?>
				<hr/><h2><?php echo esc_html( $data['title'] ); ?></h2>
			<?php endif; ?>
			<div class="list-task"><?php \task_manager\Task_Class::g()->display_tasks( $data['data'] ); ?></div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
