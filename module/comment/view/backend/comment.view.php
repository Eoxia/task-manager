<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Eoxia <dev@gmail.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="comment view <?php echo ( $comment_selected_id === $comment->data['id'] ) ? 'blink' : ''; ?>">
	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="20"]' ); ?></li>
		<li class="wpeo-comment-date"><?php echo esc_html( $comment->data['date']['rendered']['date_human_readable'] ); ?></li>
		<li class="wpeo-comment-time"><span class="dashicons dashicons-clock"></span> <?php echo esc_html( $comment->data['time_info']['elapsed'] ); ?></li>
		<li class="wpeo-comment-action">

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
		</li>
		<li class="wpeo-comment-content"><?php echo nl2br( $comment->data['content'] ); ?></li>
	</ul>
</li>
