<?php
/**
 * La vue d'un point dans le frontend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2017 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="point <?php echo ! empty( $point->id ) ? esc_attr( 'edit' ) : ''; ?>" data-id="<?php echo esc_attr( $point->id ); ?>">
	<ul class="point-container">
		<li class="point-toggle action-attribute"
				data-namespace="taskManagerFrontend"
				data-module="comment"
				data-before-method="beforeLoadComment"
				data-action="load_front_comments"
				data-loader="point"
				data-task-id="<?php echo esc_attr( $point->post_id ); ?>"
				data-point-id="<?php echo esc_attr( $point->id ); ?>">

				<i class="icon-toggle fa fa-angle-right" aria-hidden="true"></i>

			<?php if ( ! empty( $point->id ) ) : ?>
				<span class="wpeo-block-id">#<?php echo esc_attr( $point->id ); ?></span>
			<?php endif; ?>
		</li>

		<li class="point-content content action-attribute"
				data-namespace="taskManagerFrontend"
				data-module="comment"
				data-before-method="beforeLoadComment"
				data-action="load_front_comments"
				data-loader="point"
				data-task-id="<?php echo esc_attr( $point->post_id ); ?>"
				data-point-id="<?php echo esc_attr( $point->id ); ?>">
			<span><?php echo $point->content; ?></span>
		</li>

		<li class="point-action">
			<div class="wpeo-point-time">
				<span class="dashicons dashicons-clock"></span>
				<span class="wpeo-time-in-point"><?php echo esc_attr( $point->time_info['elapsed'] ); ?></span>
			</div>
			<div class="wpeo-point-comment">
				<span class="dashicons dashicons-admin-comments"></span>
				<span><?php echo esc_html( $point->count_comments ); ?></span>
			</div>
		</li>
	</ul>

	<ul class="comments hidden" data-id="<?php echo esc_attr( $point->id ); ?>"></ul>
</div>
