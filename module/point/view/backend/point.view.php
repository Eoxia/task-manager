<?php
/**
 * La vue d'un point dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="point <?php echo ! empty( $point->data['id'] ) ? esc_attr( 'edit' ) : ''; ?>" data-id="<?php echo esc_attr( $point->data['id'] ); ?>">

	<div class="form">

		<input type="hidden" name="id" value="<?php echo esc_attr( $point->data['id'] ); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo esc_attr( $parent_id ); ?>" />
		<ul class="point-container">
			<li class="point-valid">
				<?php if ( ! empty( $point->data['id'] ) ) : ?>
					<span class="wpeo-sort-point" title="<?php esc_attr_e( 'Drag and drop', 'task-manager' ); ?>">
						<i class="fa fa-ellipsis-v"></i>
						<i class="fa fa-ellipsis-v"></i>
					</span>
					<input type="checkbox" <?php echo ! empty( $point->data['completed'] ) ? 'checked' : ''; ?> class="completed-point" data-nonce="<?php echo esc_attr( wp_create_nonce( 'complete_point' ) ); ?>" />
				<?php endif; ?>
			</li>

			<li class="point-content content">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $point->data['content'] ) ); ?>" />
				<div class="point-toggle">
					<?php if ( ! empty( $point->data['id'] ) ) : ?>
						<span class="wpeo-block-id">#<?php echo esc_attr( $point->data['id'] ); ?></span>
					<?php endif; ?>
				</div>
				<div class="wpeo-point-new-contenteditable" contenteditable="true"><?php echo trim( $point->data['content'] ); ?></div>
				<?php if ( empty( $point->data['id'] ) ) : ?>
					<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</li>

			<li class="point-action">
				<?php	if ( empty( $point->data['id'] ) ) : ?>
					<div 	class="wpeo-point-new-btn action-input animated no-action"
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
						<span class="wpeo-time-in-point"><?php echo esc_attr( $point->data['time_info']['elapsed'] ); ?></span>
					</div>

					<div class="wpeo-dropdown wpeo-task-setting"
							data-parent="toggle"
							data-target="content"
							data-mask="wpeo-project-task">

						<span class="wpeo-button button-transparent dropdown-toggle"
							><i class="fa fa-ellipsis-v"></i></span>

						<div class="dropdown-content point-header-action">
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

	<ul class="comments <?php echo ( Task_Comment_Class::g()->is_parent( $point->data['id'], $comment_id ) || ( $point->data['id'] === $point_id && 0 !== $point->data['id'] )  ) ? '' : 'hidden'; ?>" data-id="<?php echo esc_attr( $point->data['id'] ); ?>">
		<?php
		if ( Task_Comment_Class::g()->is_parent( $point->data['id'], $comment_id ) || ( $point->data['id'] === $point_id && 0 !== $point->data['id'] ) ) :
			Task_Comment_Class::g()->display( $point->data['post_id'], $point->data['id'] );
		endif;
		?>
	</ul>
</div>
