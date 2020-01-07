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

<div class="table-column <?php echo $point->data['completed'] ? 'task-completed' : ''; ?>"
     data-parent-id="<?php echo esc_attr( $point->data['post_id'] ); ?>"
     data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
     data-nonce="<?php echo wp_create_nonce( 'edit_point' ); ?>">

	<div class="table-row">
		<div class="table-cell table-25 task-toggle-comment">
			<div class="table-cell-container">
				<i class="fas fa-angle-right"></i>
			</div>
		</div>

		<div class="table-cell table-50 task-complete-point">
			<div class="table-cell-container">
				<input class="task-complete-point-field" type="checkbox" <?php echo ! empty( $point->data['completed'] ) ? 'checked' : ''; ?> />
			</div>
		</div>

		<div class="table-cell table-300 task-content">
			<div class="table-cell-container task-title" contenteditable="true">
				<?php echo esc_html( $point->data['content'] ); ?>
			</div>
		</div>

		<div class="table-cell table-50 task-comment-number">
			<div class="table-cell-container">
				<span class="number-comments"><?php echo esc_html( $point->data['count_comments'] ); ?></span>
			</div>
		</div>

		<div class="table-cell table-50 task-id">
			<div class="table-cell-container">
				<?php echo esc_html( $point->data['id'] ); ?>
			</div>
		</div>

		<div class="table-cell table-75 task-time">
			<div class="table-cell-container">
				<span class="elapsed"><?php echo esc_html( $point->data['time_info']['elapsed'] ); ?></span>
				<span class="unit">min</span>
			</div>
		</div>

		<div class="table-cell table-150 task-created-date">
			<div class="table-cell-container">
				<?php echo esc_html( $point->data['date']['rendered']['date_time'] ); ?>
			</div>
		</div>

		<div class="table-cell table-100 task-due-time">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell table-100 task-waiting-for">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell table-100 task-author">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_avatar ids="' . $point->data['author_id'] . '" size="20"]' ); ?>
			</div>
		</div>

		<div class="table-cell table-150 task-users">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->data['id'] . ']' ); ?>
			</div>
		</div>

		<div class="table-cell table-50 table-end">
			<div class="table-cell-container">
				<div class="wpeo-dropdown dropdown-right">
					<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
					<div class="dropdown-content point-header-action">
						<?php \eoxia\View_Util::exec( 'task-manager', 'point', 'backend/toggle-content', array( 'point' => $point ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="column-extend hidden"></div>
</div>
