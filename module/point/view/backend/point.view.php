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

<form class="form <?php echo ! empty( $point->id ) ? esc_attr( 'edit' ): ''; ?>" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">

	<?php wp_nonce_field( 'edit_point' ); ?>
	<input type="hidden" name="action" value="edit_point" />
	<input type="hidden" name="id" value="<?php echo esc_attr( $point->id ); ?>" />
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $parent_id ); ?>" />

	<ul class="wpeo-task-point">
		<li class="wpeo-add-point wpeo-point-no-sortable">
			<ul>
				<li>
					<input type="checkbox" <?php echo ! empty( $point->point_info['completed'] ) ? 'checked': ''; ?> class="completed-point" data-nonce="<?php echo esc_attr( wp_create_nonce( 'complete_point' ) ); ?>" />

					<span data-action="<?php echo esc_attr( 'load_comments' ); ?>"
								data-task-id="<?php echo esc_attr( $parent_id ); ?>"
								data-point-id="<?php echo esc_attr( $point->id ); ?>"
								class="action-attribute">O</span>
					<span class="wpeo-block-id">#<?php echo esc_attr( $point->id ); ?></span>
				</li>
				<li class="wpeo-point-input">
					<input type="hidden" name="content" value="<?php esc_attr( $point->content ); ?>" />
					<div class="wpeo-point-new-contenteditable" contenteditable="true">
						<?php echo esc_html( stripslashes( $point->content ) ); ?>
					</div>
					<?php if ( empty( $point->id ) ) : ?>
						<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
					<?php endif; ?>
				</li>
				<li>
					<?php	if ( empty( $point->id ) ) : ?>
						<div class="wpeo-point-new-btn submit-form" data-parent="form" title="<?php esc_attr( 'Add this point', 'task-manager' ); ?>">
							<i class="dashicons dashicons-plus-alt"></i>
						</div>
					<?php else : ?>
						<div class="hidden submit-form" data-parent="form"></div>

						<span class="dashicons dashicons-clock"></span>
						<span class="wpeo-time-in-point"><?php echo esc_attr( $point->time_info['elapsed'] ); ?></span>

						<span data-action="delete_point"
									data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_point' ) ); ?>"
									data-id="<?php echo esc_attr( $point->id ); ?>"
									class="dashicons dashicons-no action-delete"></span>
					<?php	endif; ?>
				</li>
			</ul>
		</li>
	</ul>
</form>

<ul class="comments"></ul>