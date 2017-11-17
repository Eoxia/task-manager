<?php
/**
 * Edition d'un commentaire dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="comment new">
	<ul class="wpeo-comment-container">

		<input type="hidden" name="action" value="edit_comment" />
		<?php wp_nonce_field( 'edit_comment' ); ?>
		<input type="hidden" name="comment_id" value="<?php echo esc_attr( $comment->id ); ?>" />
		<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
		<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />

		<li class="wpeo-comment-date group-date" data-namespace="taskManager" data-module="comment" data-after-method="afterTriggerChangeDate">
			<input type="text" style="width: 0px;" class="mysql-date" name="date" value="<?php echo esc_attr( $comment->date['date_input']['date'] ); ?>" />
			<input type="hidden" name="value_changed" value="<?php echo ( ! empty( $comment->id ) ) ? 1 : 0; ?>" />
			<div class="tooltip hover" aria-label="<?php echo esc_attr( $comment->date['date_input']['fr_FR']['date_time'] ); ?>"><span class="date-time dashicons dashicons-calendar-alt"></span></div>
		</li>

		<li class="wpeo-comment-content">
			<input type="hidden" name="content" value="<?php echo esc_attr( $comment->content ); ?>" />
			<div class="content" contenteditable="true"><?php echo trim( $comment->content ); ?></div>
			<?php if ( empty( $comment->id ) ) : ?>
				<span class="wpeo-point-new-placeholder"><?php esc_html_e( 'Your comment here...', 'task-manager' ); ?></span>
			<?php endif; ?>
		</li>

		<li class="wpeo-comment-time"><span class="fa fa-clock-o"></span> <input type="text" name="time" value="<?php echo esc_attr( empty( $comment->id ) && isset( $comment->time_info['calculed_elapsed'] ) ? $comment->time_info['calculed_elapsed'] : $comment->time_info['elapsed'] ); ?>" /></li>

		<?php if ( ! empty( $comment->id ) ) : ?>
			<li class="wpeo-save-point"><i data-parent="wpeo-comment-container" class="action-input fa fa-floppy-o" aria-hidden="true"></i></li>
		<?php else : ?>
			<li data-parent="wpeo-comment-container" style="opacity: 0.4;" class="wpeo-point-new-btn action-input dashicons dashicons-plus-alt"></li>
		<?php endif; ?>
	</ul>
</li>
