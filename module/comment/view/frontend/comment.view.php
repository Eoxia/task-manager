<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<li class="comment view <?php echo ( $comment_selected_id === $comment->data['id'] ) ? 'blink' : ''; ?>">

	<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?>

	<div class="comment-container">

		<div class="comment-content">
			<div class="comment-content-text">
				<?php echo nl2br( $comment->data['rendered'] ); ?>
			</div>

			<?php echo apply_filters( 'tm_comment_advanced_view', '', $comment ); ?>
		</div><!-- .comment-content -->

		<div class="comment-action">
			<div class="wpeo-dropdown wpeo-comment-setting"
					data-parent="toggle"
					data-target="content"
					data-mask="wpeo-project-task">

				<span class="wpeo-button button-transparent dropdown-toggle"><i class="fa fa-ellipsis-v"></i></span>

				<ul class="dropdown-content left content point-header-action">

					<?php echo apply_filters( 'tm_comment_toggle_before', '', $comment ); ?>
					<li class="dropdown-item action-attribute"
							data-action="load_edit_view_comment"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_view_comment' ) ); ?>"
							data-id="<?php echo esc_attr( $comment->data['id'] ); ?>"
						<span><i class="fas fa-pencil fa-fw"></i> <?php esc_html_e( 'Edit this comment', 'task-manager' ); ?></span>
					</li>

					<li class="dropdown-item action-delete"
							data-action="delete_task_comment"
							data-message-delete="<?php echo esc_attr_e( 'Delete this comment ?', 'task-manager' ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task_comment' ) ); ?>"
							data-id="<?php echo esc_attr( $comment->data['id'] ); ?>"
						<span><i class="fas fa-trash fa-fw"></i> <?php esc_html_e( 'Delete this comment', 'task-manager' ); ?></span>
					</li>
					<?php echo apply_filters( 'tm_comment_toggle_after', '', $comment ); ?>
				</ul>
			</div>
		</div>

	</div>
</li>
