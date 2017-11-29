<?php
/**
 * Les propriétés d'une tâche.
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

<div class="success">
	<div class="content">
		<p>Notification envoyée</p>
		<span>OK</span>
	</div>
</div>

<div class="gridwrapper w2">
	<ul class="task-color">
		<?php
		if ( ! empty( Task_Class::g()->colors ) ) :
			foreach ( Task_Class::g()->colors as $color ) :
				?>
				<li class="color-element">
					<span class="action-attribute <?php echo esc_attr( $color ); ?>" data-action="change_color"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'change_color' ) ); ?>"
								data-id="<?php echo esc_attr( $task->id ); ?>"
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
		<?php echo do_shortcode( '[task_avatar ids="' . $task->author_id . '" size="50"]' ); ?>
		<span class="time"><i class="dashicons dashicons-calendar-alt"></i>
			<?php echo esc_html_e( 'Create ', 'task-manager' ); ?>
			<?php echo esc_html( mb_strtolower( $task->date['date_human_readable'] ) ); ?>
		</span>
	</div>
</div>

<ul class="actions">
	<li class="action-attribute tooltip hover"
			aria-label="<?php ( 'archive' !== $task->status ) ? esc_html_e( 'Archive', 'task-manager' ) : esc_html_e( 'Unarchive', 'task-manager' ); ?>"
			data-action="<?php echo ( 'archive' !== $task->status ) ? 'to_archive' : 'to_unarchive'; ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( ( 'archive' === $task->status ) ? 'to_archive' : 'to_unarchive' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>"
			data-loader="task-header-action">
		<span><i class="fa fa-archive"></i></span>
	</li>

	<li class="action-delete tooltip hover"
			aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
			data-action="delete_task"
			data-message-delete="<?php echo esc_attr_e( 'Delete this task ?', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>"
			data-loader="task-header-action">
		<span><i class="fa fa-trash"></i></span>
	</li>

	<li class="open-popup-ajax tooltip hover"
			aria-label="<?php esc_html_e( 'Notify team', 'task-manager' ); ?>"
			data-parent="wpeo-project-task"
			data-target="popup"
			data-class="popup-notification"
			data-action="load_notify_popup"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_notify_popup' ) ); ?>"
			data-title="<?php echo sprintf( __( '#%1$s Notify popup', 'task-manager' ), esc_attr( $task->id ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span><i class="fa fa-bell"></i></span>
	</li>

	<li class="action-attribute tooltip hover"
			aria-label="<?php esc_html_e( 'Export', 'task-manager' ); ?>"
			data-action="export_task"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_task' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>"
			data-loader="task-header-action">
		<span><i class="fa fa-download"></i></span>
	</li>

	<?php apply_filters( 'task_manager_task_header_actions_after', $task->id ); ?>

</ul>

<div class="move-to">
	<div>
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task->id ); ?>" />

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

<?php echo apply_filters( 'task_manager_task_header_action_end', '', $task->id ); ?>
