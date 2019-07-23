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

<div class="point <?php echo ! empty( $point->data['id'] ) ? esc_attr( 'edit' ) : esc_attr( 'new' ); ?>"
		data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
		data-point-state="<?php echo esc_attr( ! empty( $point->data['completed'] ) ? 'completed' : 'uncompleted' ); ?>" >
	<div class="form">
		<input type="hidden" name="id" value="<?php echo esc_attr( $point->data['id'] ); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo esc_attr( $parent_id ); ?>" />
		<ul class="point-container">
			<li class="point-content content">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $point->data['content'] ) ); ?>" />
				<div class="wpeo-point-new-contenteditable" contenteditable="true"><?php echo trim( $point->data['content'] ); ?></div>
				<?php if ( empty( $point->data['id'] ) ) : ?>
					<span class="wpeo-point-new-placeholder"><i class="fas fa-plus"></i> <?php esc_html_e( 'Write your point here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</li>

			<li class="point-action">
				<?php echo apply_filters( 'tm_point_after', '', $point ); // WPCS: XSS  ok. ?><!-- / filter for point last column -->
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

<input type="hidden" name="tm_point_is_quick_point" value="true" />
