<?php
/**
 * Edition d'un commentaire dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="comment <?php echo apply_filters( 'tm_comment_edit_class', ! empty( $comment->data['id'] ) ? 'edit' : 'new' ); ?>">
	<?php $comment = apply_filters( 'tm_comment_edit_before', $comment ); ?>
	<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->data['id'] ); ?>" />
	<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />

	<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?>

	<div class="comment-container">
		<div class="comment-content">

			<div class="comment-content-text">
				<input type="hidden" name="content" value="<?php echo esc_attr( trim( $comment->data['content'] ) ); ?>" />
				<div contenteditable="true" class="content">
					<?php echo trim( $comment->data['content'] ) ? $comment->data['content'] : ''; ?></div>
				<?php if ( empty( $comment->data['id'] ) && ! apply_filters( 'tm_comment_edit_quicktimemode', '' ) ) : ?>
					<span class="placeholder"><i class="fas fa-plus"></i> <?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
				<?php else : ?>
					<span class="placeholder hidden"><i class="fas fa-plus"></i> <?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="auto-complete-user">
			</div>

			<?php echo apply_filters( 'tm_comment_edit_after', '', $comment ); ?>

		</div><!-- .comment-content -->
		<div class="comment-action">
			<div class="fa-layers fa-fw save-icon wpeo-button button-rounded button-square-30 tm_register_comment">
				<i class="button-icon fas fa-save"></i>
			</div>
		</div>
	</div>
</li>
