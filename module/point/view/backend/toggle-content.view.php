<?php
/**
 * Les propriétés d'un point.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.4.0-ford
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>
	<div class="task-informations">
		<?php echo do_shortcode( '[task_avatar ids="' . $point->data['author_id'] . '" size="50"]' ); ?>
		<p>
			<?php echo esc_html_e( 'Create ', 'task-manager' ); ?>

			<span data-namespace="taskManager" data-module="point" data-after-method="afterTriggerChangeDate" class="group-date">
				<input type="hidden" class="mysql-date" name="due_date" value="<?php echo esc_attr( $point->data['date']['raw'] ); ?>" />
				<span class="date-time"><?php echo esc_html( mb_strtolower( $point->data['date']['rendered']['date_time'] ) ); ?></span>
			</span>
		<p>
	</div>
</div>

<ul class="actions">
	<li class="action-delete wpeo-tooltip-event" data-position="top"
			aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
			data-action="delete_point"
			data-message-delete="<?php echo esc_attr_e( 'Delete this point ?', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_point' ) ); ?>"
			data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
			data-loader="actions">
		<span><i class="fas fa-trash"></i></span>
	</li>
</ul>

<div class="move-to">
	<div class="">
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $point->data['post_id'] ); ?>" />
		<input type="hidden" name="point_id" value="<?php echo esc_attr( $point->data['id'] ); ?>" />

		<label for="move_task"><?php esc_html_e( 'Move the point to', 'task-manager' ); ?></label>
		<div class="form-fields">
			<input type="text" class="search-task" />
			<input type="hidden" name="to_task_id" />
			<input type="button" class="action-input" data-action="move_point_to" data-nonce="<?php echo esc_attr( wp_create_nonce( 'move_point_to' ) ); ?>" data-loader="move-to" data-parent="move-to" value="<?php esc_html_e( 'OK', 'task-manager' ); ?>" />
		</div>
		<div class="list-tasks-to-move">
		</div>
	</div>
</div>
