<?php
/**
 * La vue d'un point dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="point <?php echo ! empty( $point->id ) ? esc_attr( 'edit' ): ''; ?>" data-id="<?php echo esc_attr( $point->id ); ?>">
	<form class="form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">

		<?php wp_nonce_field( 'edit_point' ); ?>
		<input type="hidden" name="action" value="edit_point" />
		<input type="hidden" name="id" value="<?php echo esc_attr( $point->id ); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo esc_attr( $parent_id ); ?>" />
		<ul class="point-container">
			<li class="point-valid">
				<?php if ( ! empty( $point->id ) ) : ?>
					<span class="wpeo-sort-point dashicons dashicons-screenoptions" title="<?php esc_attr_e( 'Drag and drop for set the order', 'task-manager' ); ?>"></span>
					<input type="checkbox" <?php echo ! empty( $point->point_info['completed'] ) ? 'checked': ''; ?> class="completed-point" data-nonce="<?php echo esc_attr( wp_create_nonce( 'complete_point' ) ); ?>" />
				<?php endif; ?>
			</li>

			<li class="point-toggle">
				<?php if ( ! empty( $point->id ) ) : ?>
					<span data-action="<?php echo esc_attr( 'load_comments' ); ?>"
								data-task-id="<?php echo esc_attr( $parent_id ); ?>"
								data-point-id="<?php echo esc_attr( $point->id ); ?>"
								data-module="comment"
								data-before-method="beforeLoadComments"
								class="animated dashicons dashicons-arrow-right-alt2 action-attribute"></span>

					<span class="wpeo-block-id">#<?php echo esc_attr( $point->id ); ?></span>
				<?php endif; ?>
			</li>

			<li class="point-content content">
				<input type="hidden" name="content" value="<?php esc_attr( $point->content ); ?>" />
				<div class="wpeo-point-new-contenteditable" contenteditable="true">
					<?php echo esc_html( stripslashes( $point->content ) ); ?>
					<?php if ( empty( $point->id ) ) : ?>
						<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
					<?php endif; ?>
				</div>
			</li>

			<li class="point-action">
				<?php	if ( empty( $point->id ) ) : ?>
					<div 	class="wpeo-point-new-btn action-input animated"
								data-parent="form"
								data-loader="point"
								title="<?php esc_attr( 'Add this point', 'task-manager' ); ?>">
						<i class="dashicons dashicons-plus-alt"></i>
					</div>
				<?php else : ?>
					<div class="hidden submit-form" data-parent="form"></div>

					<div class="wpeo-point-time">
						<span class="dashicons dashicons-clock"></span>
						<span class="wpeo-time-in-point"><?php echo esc_attr( $point->time_info['elapsed'] ); ?></span>
					</div>

					<div class="toggle wpeo-task-setting"
							data-parent="point"
							data-target="content">

						<div class="action">
							<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Task options', 'task-manager' ); ?>"><i class="fa fa-cog"></i></span>
						</div>

						<ul class="content point-header-action">
							<li class="open-popup-ajax"
									data-action="load_point_properties"
									data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point_properties' ) ); ?>"
									data-id="<?php echo esc_attr( $point->id ); ?>"
									data-parent="wpeo-project-task"
									data-target="popup">
								<span><?php esc_html_e( 'Point properties', 'task-manager' ); ?></span>
							</li>
						</ul>
					</div>

					<!-- <span data-action="delete_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_point' ) ); ?>"
								data-id="<?php echo esc_attr( $point->id ); ?>"
								class="dashicons dashicons-no action-delete"></span> -->
				<?php	endif; ?>
			</li>
		</ul>
	</form>

	<ul class="comments hidden" data-id="<?php echo esc_attr( $point->id ); ?>"></ul>
</div>
