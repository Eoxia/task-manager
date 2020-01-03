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

<div class="task-column <?php echo $point->data['completed'] ? 'task-completed' : ''; ?>"
     data-parent-id="<?php echo esc_attr( $point->data['post_id'] ); ?>"
     data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
     data-nonce="<?php echo wp_create_nonce( 'edit_point' ); ?>">

	<div class="table-row">
		<div class="table-cell">
			<div class="table-cell-container">
				<i class="task-toggle-comment fas fa-angle-right"></i>
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				<input class="task-complete-point" type="checkbox" <?php echo ! empty( $point->data['completed'] ) ? 'checked' : ''; ?> />
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container task-title" contenteditable="true">
				<?php echo esc_html( $point->data['content'] ); ?>
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				<span class="number-comments">2</span>
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				<?php echo esc_html( $point->data['id'] ); ?>
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				30
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				20/11/2019 10h15
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				-
			</div>
		</div>

		<div class="table-cell">
			<div class="table-cell-container">
				<i class="fas fa-ellipsis-v"></i>
			</div>
		</div>
	</div>
	<div class="column-extend hidden">
		Lalala
	</div>
</div>
