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
					<input type="checkbox" <?php echo ! empty( $point->data['completed'] ) ? 'checked' : ''; ?> class="completed-point"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'complete_point' ) ); ?>"
					data-checked="<?php echo ! empty( $point->data['completed'] ) ? 'true' : 'false'; ?>"
					style="position : relative" />

					<?php // Pour 1.11
					/*
					<div class="point-list-element" style="position : absolute; display : none; z-index : 2">
						<div class="wpeo-button button-blue button-radius-3 button-square-30 button-bordered action-attribute" style="left : 6px; top : -40px"
						data-statut="completed"
						data-parent="point-list-element"
						data-id="<?php echo esc_attr( $parent_id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_statut_task' ) ); ?>"
						data-action="update_statut_task">
							<i class="fas fa-check"></i>
						</div>
						<div class="wpeo-button button-yellow button-radius-3 button-square-30 button-bordered action-attribute" style="left: 10px; top: -8px;"
						data-statut="nc"
						data-parent="point-list-element"
						data-id="<?php echo esc_attr( $parent_id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_statut_task' ) ); ?>"
						data-action="update_statut_task">
							<b><?php echo esc_html( 'NC', 'task-manager' ); ?></b>
						</div>
						<div class="wpeo-button button-red button-radius-3 button-square-30 button-bordered action-attribute" style="left: -60px; top: 25px;"
						data-statut="uncompleted"
						data-parent="point-list-element"
						data-id="<?php echo esc_attr( $parent_id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_statut_task' ) ); ?>"
						data-action="update_statut_task">
							<i class="fas fa-times"></i>
						</div>
						<div class="wpeo-button button-dark button-radius-3 button-square-30 button-bordered action-attribute" style="left: -135px; top: -10px;"
						data-statut="na"
						data-parent="point-valid"
						data-id="<?php echo esc_attr( $parent_id ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_statut_task' ) ); ?>"
						data-action="update_statut_task">
							<b><?php echo esc_html( 'NA', 'task-manager' ); ?></b>
						</div>
					</div>*/

					?>
				<?php endif; ?>
				<?php echo apply_filters( 'tm_point_before', '', $point ); // WPCS: XSS  ok. ?><!-- / filter for point first column -->
			</li>

			<li class="point-content content">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $point->data['content'] ) ); ?>" />
				<div class="wpeo-point-new-contenteditable" contenteditable="true"><?php echo trim( $point->data['content'] ); ?></div>
				<?php if ( empty( $point->data['id'] ) ) : ?>
					<span class="wpeo-point-new-placeholder"><i class="fas fa-plus"></i> <?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $point->data['id'] ) ) : ?>
					<ul class="wpeo-point-summary">
						<?php echo apply_filters( 'tm_point_summary', '', $point ); ?>
					</ul>
				<?php endif; ?>
			</li>

			<li class="point-action">

				<?php echo apply_filters( 'tm_point_after', '', $point ); // WPCS: XSS  ok. ?><!-- / filter for point last column -->

				<?php if ( empty( $point->data['id'] ) ) : ?>
					<div 	class="wpeo-point-new-btn wpeo-button button-main button-square-30 button-rounded action-input animated no-action wpeo-util-hidden"
								data-parent="form"
								data-loader="point"
								data-action="edit_point"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>"
								title="<?php esc_attr( 'Add this point', 'task-manager' ); ?>">
						<i class="button-icon fas fa-plus"></i>
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
				<?php echo apply_filters( 'tm_point_action', '', $point, $parent_id ); ?>
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
