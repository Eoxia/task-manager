<?php
/**
 * La vue d'un point dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="point <?php echo ! empty( $point->id ) ? esc_attr( 'edit' ) : ''; ?>" data-id="<?php echo esc_attr( $point->id ); ?>">

	<div class="form">

		<input type="hidden" name="id" value="<?php echo esc_attr( $point->id ); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo esc_attr( $parent_id ); ?>" />
		<ul class="point-container">
			<li class="point-valid">
				<?php if ( ! empty( $point->id ) ) : ?>
					<span class="wpeo-sort-point dashicons dashicons-screenoptions" title="<?php esc_attr_e( 'Drag and drop', 'task-manager' ); ?>"></span>
					<input type="checkbox" <?php echo ! empty( $point->point_info['completed'] ) ? 'checked': ''; ?> class="completed-point" data-nonce="<?php echo esc_attr( wp_create_nonce( 'complete_point' ) ); ?>" />
				<?php endif; ?>
			</li>

			<li class="point-toggle">
				<?php if ( ! empty( $point->id ) ) : ?>
					<span class="wpeo-block-id">#<?php echo esc_attr( $point->id ); ?></span>
				<?php endif; ?>
			</li>

			<li class="point-content content">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $point->content ) ); ?>" />
				<div class="wpeo-point-new-contenteditable" contenteditable="true"><?php echo trim( $point->content ); ?></div>
				<?php if ( empty( $point->id ) ) : ?>
					<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</li>

			<li class="point-action">
				<?php	if ( empty( $point->id ) ) : ?>
					<div 	class="wpeo-point-new-btn action-input animated"
								data-parent="form"
								data-loader="point"
								data-action="edit_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"
								style="opacity: 0.4;"
								title="<?php esc_attr( 'Add this point', 'task-manager' ); ?>">
						<i class="dashicons dashicons-plus-alt"></i>
					</div>
				<?php else : ?>
					<div class="hidden action-input update"
								data-parent="form"
								data-action="edit_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"></div>

					<div class="wpeo-point-time">
						<span class="dashicons dashicons-clock"></span>
						<span class="wpeo-time-in-point"><?php echo esc_attr( $point->time_info['elapsed'] ); ?></span>
					</div>

					<div class="toggle wpeo-task-setting"
							data-parent="toggle"
							data-target="content"
							data-mask="wpeo-project-task">

						<div class="action">
							<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Point options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
						</div>

						<div class="left content point-header-action">
							<?php
							\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/toggle-content', array(
								'point' => $point,
							) );
							?>
						</div>
					</div>
				<?php	endif; ?>
			</li>
		</ul>
	</div>

	<ul class="comments <?php echo ( Task_Comment_Class::g()->is_parent( $point->id, $comment_id ) || ( $point->id === $point_id && 0 !== $point->id )  ) ? '' : 'hidden'; ?>" data-id="<?php echo esc_attr( $point->id ); ?>">
		<?php
		if ( Task_Comment_Class::g()->is_parent( $point->id, $comment_id ) || ( $point->id === $point_id && 0 !== $point->id ) ) :
			Task_Comment_Class::g()->display( $point->post_id, $point->id );
		endif;
		?>
	</ul>
</div>
