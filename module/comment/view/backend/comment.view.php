<?php
/**
 * Un commentaire dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li class="comment">
	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="16"]' ); ?></li>
		<li class="wpeo-comment-date"><?php echo esc_html( $comment->author->display_name ) . ', ' . esc_html( Date_Util::g()->mysqldate2wordpress( $comment->date ) ); ?></li>
		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <?php echo esc_html( $comment->time_info['elapsed'] ); ?></li>
		<li class="wpeo-comment-action">
			<div class="toggle wpeo-comment-setting"
					data-parent="toggle"
					data-target="content">

				<div class="action">
					<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Comment options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
				</div>

				<ul class="left content point-header-action">
					<li class="action-attribute"
							data-action="load_edit_view_comment"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_edit_view_comment' ) ); ?>"
							data-id="<?php echo esc_attr( $comment->id ); ?>"
						<span><?php esc_html_e( 'Edit comment', 'task-manager' ); ?></span>
					</li>

					<li class="action-delete"
							data-action="delete_comment"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_comment' ) ); ?>"
							data-id="<?php echo esc_attr( $comment->id ); ?>"
						<span><?php esc_html_e( 'Delete comment', 'task-manager' ); ?></span>
					</li>
				</ul>
			</div>
		</li>
		<li class="wpeo-comment-content"><?php echo esc_html( $comment->content ); ?></li>
	</ul>
</li>
