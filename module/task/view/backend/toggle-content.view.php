<?php
/**
 * Les propriétés d'une tâche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="gridwrapper w2">
	<ul class="task-color">
		<?php
		if ( ! empty( Task_Class::g()->colors ) ) :
			foreach ( Task_Class::g()->colors as $color ) :
				?>
				<li class="color-element">
					<span class="action-attribute <?php echo esc_attr( $color ); ?>" data-action="change_color"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'change_color' ) ); ?>"
								data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
								data-color="<?php echo esc_attr( $color ); ?>"
								data-namespace="taskManager"
								data-module="task"
								data-before-method="beforeChangeColor"></span>
				</li>
				<?php
			endforeach;
		endif;
		?>
	</ul>
	<div class="task-informations">
		<?php echo do_shortcode( '[task_avatar ids="' . $task->data['author_id'] . '" size="50"]' ); ?>
		<span class="time"><i class="dashicons dashicons-calendar-alt"></i>
			<?php echo esc_html_e( 'Create ', 'task-manager' ); ?>
			<?php echo esc_html( mb_strtolower( $task->data['date']['rendered']['date_human_readable'] ) ); ?>
		</span>
	</div>
</div>

<ul class="actions">
		<li class="action-attribute wpeo-tooltip-event" data-direction="top"
			aria-label="<?php echo esc_html_e( 'Recompile task', 'task-manager' ); ?>"
			data-action="recompile_task"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'recompile_task' ) ); ?>"
			data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
			data-loader="task-header-action">
		<span><i class="fas fa-redo"></i></span>
	</li>

	<li class="wpeo-modal-event wpeo-tooltip-event" data-direction="top"
			aria-label="<?php esc_html_e( 'Notify team', 'task-manager' ); ?>"
			data-class="popup-notification"
			data-action="load_notify_popup"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_notify_popup' ) ); ?>"
			data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Notify popup', 'task-manager' ), esc_attr( $task->data['id'] ) ) ); ?>"
			data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
		<span><i class="fas fa-bell"></i></span>
	</li>

	<li class="wpeo-task-time-history wpeo-modal-event wpeo-tooltip-event"
		data-class="history-time wpeo-wrap tm-wrap"
		aria-label="<?php esc_html_e( 'Handle time', 'task-manager' ); ?>"
		data-action="load_time_history"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_time_history' ) ); ?>"
		data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Time history', 'task-manager' ), $task->data['id'] ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">
		<span><i class="fas fa-calendar-alt"></i></span>
	</li>

	<li class="wpeo-modal-event wpeo-tooltip-event" data-direction="top"
			aria-label="<?php esc_html_e( 'Export', 'task-manager' ); ?>"
			data-action="load_export_popup"
			data-class="popup-export"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_export_popup' ) ); ?>"
			data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
			data-loader="task-header-action"
			data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Export task data', 'task-manager' ), esc_attr( $task->data['id'] ) ) ); ?>">
		<span><i class="fas fa-upload"></i></span>
	</li>

	<?php apply_filters( 'task_manager_task_header_actions_after', $task->data['id'], $task ); ?>

	<li class="action-attribute wpeo-tooltip-event" data-direction="top"
		aria-label="<?php esc_html_e( 'Hide Point', 'task-manager' ); ?>"
		data-action="hide_points"
		data-hide="true"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hide_points' ) ); ?>"
		data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
		data-loader="task-header-action">
		<span><i class="fas fa-eye"></i></span>
	</li>

	<li class="action-attribute wpeo-tooltip-event" data-direction="top"
			aria-label="<?php ( 'archive' !== $task->data['status'] ) ? esc_html_e( 'Archive', 'task-manager' ) : esc_html_e( 'Unarchive', 'task-manager' ); ?>"
			data-action="<?php echo ( 'archive' !== $task->data['status'] ) ? 'to_archive' : 'to_unarchive'; ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( ( 'archive' === $task->data['status'] ) ? 'to_archive' : 'to_unarchive' ) ); ?>"
			data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
			data-loader="task-header-action">
		<span><i class="fas fa-archive"></i></span>
	</li>

	<li class="action-delete wpeo-tooltip-event" data-direction="top"
		aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
		data-action="delete_task"
		data-message-delete="<?php echo esc_attr_e( 'Delete this task ? Attention, the time will also be deleted. Remember to archive.', 'task-manager' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
		data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
		data-loader="task-header-action">
		<span><i class="fas fa-trash"></i></span>
	</li>


	<li class="wpeo-tooltip-event" data-direction="top"
		aria-label="<?php esc_html_e( 'Media', 'task-manager' ); ?>">
		<?php echo do_shortcode( '[wpeo_upload id="' . $task->data['id'] . '" single="false" model_name="/task_manager/Task_Class" field_name="image"]' ); ?>
	</li>
</ul>

<div class="move-to">
	<div>
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task->data['id'] ); ?>" />

		<label for="move_task"><?php esc_html_e( 'Move the task to', 'task-manager' ); ?></label>
		<div class="form-fields">
			<input type="text" class="search-parent" />
			<input type="hidden" name="to_element_id" />
			<input type="button" class="action-input" data-action="move_task_to" data-nonce="<?php echo esc_attr( wp_create_nonce( 'move_task_to' ) ); ?>" data-loader="move-to" data-parent="move-to" value="<?php esc_html_e( 'OK', 'task-manager' ); ?>" />
		</div>
		<div class="list-posts">
		</div>
	</div>
</div>


<?php echo apply_filters( 'task_manager_task_header_action_end', '', $task->data['id'] ); ?>
