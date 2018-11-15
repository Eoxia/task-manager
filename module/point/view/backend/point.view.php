<?php
/**
 * La vue d'un point dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="point <?php echo ! empty( $point->data['id'] ) ? esc_attr( 'edit' ) : esc_attr( 'new' ); ?>" data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
		data-point-state="<?php echo esc_attr( ! empty( $point->data['completed'] ) ? 'completed' : 'uncompleted' ); ?>" >
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
				<?php echo apply_filters( 'tm_point_before', '', $point ); // WPCS: XSS  ok. ?><!-- / filter for point first column -->
			</li>

			<li class="point-content content">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $point->data['content'] ) ); ?>" />
				<div class="wpeo-point-new-contenteditable" contenteditable="true"><?php echo trim( $point->data['content'] ); ?></div>
				<?php if ( empty( $point->data['id'] ) ) : ?>
					<span class="wpeo-point-new-placeholder"><i class="far fa-plus"></i> <?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $point->data['id'] ) ) : ?>
					<ul class="wpeo-point-summary">
						<li class="wpeo-block-id"><i class="far fa-hashtag"></i> <?php echo esc_attr( $point->data['id'] ); ?></li>
						<li class="wpeo-point-time">
							<i class="far fa-clock"></i>
							<span class="wpeo-time-in-point"><?php echo esc_attr( $point->data['time_info']['elapsed'] ); ?></span>
						</li>
						<li>
							<i class="far fa-comment-dots"></i>
							<?php echo esc_html( $point->data['count_comments'] ); ?>
						</li>
					</ul>
				<?php endif; ?>
			</li>

			<li class="point-action">

				<?php echo apply_filters( 'tm_point_after', '', $point ); // WPCS: XSS  ok. ?><!-- / filter for point last column -->

				<?php	if ( empty( $point->data['id'] ) ) : ?>
					<div 	class="wpeo-point-new-btn wpeo-button button-main button-square-30 button-rounded action-input animated no-action"
								data-parent="form"
								data-loader="point"
								data-action="edit_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"
								style="opacity: 0;"
								title="<?php esc_attr( 'Add this point', 'task-manager' ); ?>">
						<i class="button-icon fas fa-plus"></i>
					</div>

					<div class="wpeo-modal-event wpeo-tooltip-event quick-point-event"
							data-action="load_modal_quick_point"
							data-title="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
							aria-label="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
							data-task-id="<?php echo esc_attr( $parent_id ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_modal_quick_point' ) ); ?>"
							data-quick="true"
							data-class="tm-wrap quick-point">
						<span class="fa-layers fa-fw">
							<i class="fas fa-list-ul"></i>
							<i class="fas fa-circle" data-fa-transform="up-6 right-8"></i>
							<i class="fas fa-plus" data-fa-transform="shrink-6 up-6 right-8"></i>
						</span>
					</div>

				<?php else : ?>
					<div class="hidden action-input update"
								data-parent="form"
								data-action="edit_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"></div>

					<div class="wpeo-dropdown wpeo-task-setting"
							data-parent="toggle"
							data-target="content"
							data-mask="wpeo-project-task">

						<span class="wpeo-button button-transparent dropdown-toggle" ><i class="fa fa-ellipsis-v"></i></span>

						<div class="dropdown-content point-header-action">
							<?php \eoxia\View_Util::exec( 'task-manager', 'point', 'backend/toggle-content', array( 'point' => $point ) ); ?>
						</div>
					</div>
				<?php	endif; ?>
			</li>
		</ul>
	</div>

	<ul class="comments <?php echo ( Task_Comment_Class::g()->is_parent( $point->data['id'], $comment_id ) || ( $point->data['id'] === $point_id && 0 !== $point->data['id'] ) ) ? '' : 'hidden'; ?>" data-id="<?php echo esc_attr( $point->data['id'] ); ?>">
		<?php
		if ( Task_Comment_Class::g()->is_parent( $point->data['id'], $comment_id ) || ( $point->data['id'] === $point_id && 0 !== $point->data['id'] ) ) :
			Task_Comment_Class::g()->display( $point->data['post_id'], $point->data['id'] );
		endif;
		?>
	</ul>
</div>
