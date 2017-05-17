<?php
/**
 * Edition d'un commentaire le backend.
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

<li class="comment edit">
	<input type="hidden" name="action" value="edit_comment" />
	<?php wp_nonce_field( 'edit_comment' ); ?>
	<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->id ); ?>" />
	<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
	<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />

	<ul class="wpeo-comment-container">
		<li class="avatar"><?php echo do_shortcode( '[task_avatar ids="' . $comment->author_id . '" size="16"]' ); ?></li>
		<li class="wpeo-comment-date"><span class="fa fa-calendar-o"></span> <input type="text" class="date" name="date" value="<?php echo esc_attr( substr( $comment->date, 0, 10 ) ); ?>" /></li>
		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <input type="text" name="time" value="<?php echo esc_attr( $comment->time_info['elapsed'] ); ?>" /></li>
		<li class="wpeo-save-point"><i data-parent="comment" class="action-input fa fa-floppy-o" aria-hidden="true"></i></li>
		<li class="wpeo-comment-content">
			<input type="hidden" name="content" value="<?php echo esc_attr( $comment->content ); ?>" />
			<div class="content" contenteditable="true">
				<?php echo $comment->content; ?>
			</div>
			<?php if ( empty( $comment->id ) ) : ?>
				<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Votre commentaire ici...', 'task-manager' ); ?></span>
			<?php endif; ?>
		</li>
	</ul>
</li>
