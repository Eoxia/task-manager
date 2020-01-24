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

<div id="tm_client_load_task_page" style="height: 650px;">
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
					<?php \task_manager\Task_Class::g()->display( $data['data'] ); ?>
				<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
	<div class="tm-pagination-client-indicator" style="height : 40px">
		<div style="float : left">
			<?php

			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/task-pagination',
				array(
					'post_id'      => $post_id,
					'offset'       => $offset,
					'count_tasks'  => $count_tasks,
				)
			);

			?>
		</div>

		<a id="tm_include_archive_client"
			class="page-title-action alignright"
			data-showarchive="false"
			data-parent="tm-pagination-client-indicator"
			data-post-id="<?php echo esc_html( $post_id ); ?>"
			style="margin: 5px 10px;">
			<i class="button-icon fas fa-square"></i>
			<span><?php esc_html_e( 'Show Archives', 'task-manager' ); ?></span>
		</a>
	</div>
</div>
