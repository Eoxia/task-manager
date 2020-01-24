<?php
/**
 * Un commentaire dans le backend.
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

<div class="table-column">
	<div class="table-row" data-id="<?php echo $comment->data['id']; ?>"
	     data-post-id="<?php echo esc_attr( $task_id ); ?>"
	     data-parent-id="<?php echo esc_attr( $point_id ); ?>"
	     data-nonce="<?php echo wp_create_nonce( 'edit_comment' ); ?>">
		<div class="table-cell">
			<div class="table-cell-container comment-title" contenteditable="true"><?php echo $comment->data['content']; ?></div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="40"]' ); ?>
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container group-date" data-time="true">
				<input type="hidden" class="mysql-date" name="due_date" value="<?php echo esc_attr( $comment->data['date']['rendered']['raw'] ); ?>" />
				<input class="date form-field" type="text" value="<?php echo esc_attr( $comment->data['date']['rendered']['date_time'] ); ?>" />
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container comment-time" contenteditable="true">
				<?php echo esc_html( $comment->data['time_info']['elapsed'] ); ?>
			</div>
		</div>

		<div class="table-cell table-50 table-end">
			<div class="table-cell-container">
				<div class="wpeo-dropdown dropdown-right">
					<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
					<div class="dropdown-content point-header-action">
						<?php \eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/toggle-content', array( 'comment' => $comment ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

