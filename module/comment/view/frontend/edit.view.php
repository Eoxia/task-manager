<?php
/**
 * La vue d'édition des commentaires dans le frontend
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

<li class="comment new">
	<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
		<ul class="wpeo-comment-container">

			<input type="hidden" name="action" value="edit_comment_front" />
			<?php wp_nonce_field( 'edit_comment_front' ); ?>
			<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->data['id'] ); ?>" />
			<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
			<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />

			<li class="wpeo-comment-content">
				<input type="hidden" name="content" value="<?php esc_attr( $comment->data['content'] ); ?>" />
				<div class="content" contenteditable="true">
					<?php echo $comment->data['content']; ?>
				</div>
				<?php if ( empty( $comment->data['id'] ) ) : ?>
					<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</li>

			<?php if ( ! empty( $comment->data['id'] ) ) : ?>
				<li class="wpeo-save-point"><i data-parent="edit" class="action-input far fa-save" aria-hidden="true"></i></li>
			<?php else : ?>
				<span data-parent="edit" class="wpeo-point-new-btn action-input dashicons dashicons-plus-alt"></span>
			<?php endif; ?>

		</ul>
	</form>
</li>
