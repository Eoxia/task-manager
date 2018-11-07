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

<li class="comment new">
	<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->data['id'] ); ?>" />
	<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />

	<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?>

	<div class="comment-container">
		<div class="comment-content">

			<div class="comment-content-text">
				<input type="hidden" name="content" value="<?php echo esc_attr( $comment->data['content'] ); ?>" />
				<div contenteditable="true" class="content"></div>
				<?php if ( empty( $comment->data['id'] ) ) : ?>
					<span class="placeholder"><i class="far fa-plus"></i> <?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
				<?php endif; ?>
			</div>

			<div class="comment-meta wpeo-form">
				<div class="form-element group-date">
					<label class="form-field-container">
						<input type="hidden" class="mysql-date" name="mysql_date" value="<?php echo current_time( 'mysql' ); ?>" />
						<span class="form-field-icon-prev"><i class="fal fa-calendar-alt"></i></span>
						<input type="text" class="form-field date" value="<?php echo current_time( 'd/m/Y' ); ?>" />
					</label>
				</div>

				<div class="form-element">
					<label class="form-field-container">
						<span class="form-field-icon-prev"><i class="fas fa-clock"></i></span>
						<input type="text" name="time" class="form-field" />
					</label>
				</div>
			</div>

		</div><!-- .comment-content -->
		<div class="comment-action">
			<div class="fa-layers fa-fw save-icon action-input"
				data-parent="comment"
				data-action="edit_comment">

				<i class="fas fa-circle"></i>
				<i class="fa-inverse fas fa-save" data-fa-transform="shrink-6"></i>
			</div>
		</div>
	</div>
</li>
