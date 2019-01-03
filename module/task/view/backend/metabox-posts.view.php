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
<div class="tm-wrap wpeo-wrap">
	<div class="tm-post-dashboard">
		<p class="alignright"><?php esc_html_e( 'Total time past', 'task-manager' ); ?> : <?php echo esc_html( $total_time_elapsed ); ?> / <?php echo esc_html( $total_time_estimated ); ?></p>
	</div>

	<?php	if ( ! empty( $tasks ) ) : ?>
		<div class="wpeo-project-wrap">
			<div class="list-task">
			<div class="grid-col grid-col--1"></div>
			<div class="grid-col grid-col--2"></div>
			<?php foreach ( $tasks as $key => $data ) : ?>
				<?php if ( ! empty( $data['title'] ) ) : ?>
					<hr/><h2><?php echo esc_html( $data['title'] ); ?></h2>
				<?php endif; ?>
				<?php \task_manager\Task_Class::g()->display_tasks( $data['data'] ); ?>
			<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
