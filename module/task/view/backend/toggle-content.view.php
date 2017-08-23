<?php
/**
 * Les propriétés d'une tâche.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.4.0-ford
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

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
		<?php echo esc_html( $task->date ); ?>
	</div>
</div>

<ul class="actions">
	<li class="action-attribute"
			data-action="<?php echo ( 'archive' !== $task->status ) ? 'to_archive' : 'to_unarchive'; ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( ( 'archive' === $task->status ) ? 'to_archive' : 'to_unarchive' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span><?php esc_html_e( ( 'archive' !== $task->status ) ? 'Archive' : 'Unarchive', 'task-manager' ); ?></span>
	</li>

	<li class="action-delete"
			data-action="delete_task"
			data-message-delete="<?php echo esc_attr( 'Delete this task ?', 'task-manager' ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span><?php esc_html_e( 'Delete', 'task-manager' ); ?></span>
	</li>


	<li class="action-attribute"
			data-action="notify_by_mail"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'notify_by_mail' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span><?php esc_html_e( 'Notify team', 'task-manager' ); ?></span>
	</li>


	<li class="action-attribute"
			data-action="export_task"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_task' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span><?php esc_html_e( 'Export', 'task-manager' ); ?></span>
	</li>
</ul>

<div class="move-to">
	<div class="form">
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task->id ); ?>" />
		<input type="hidden" name="action" value="move_task_to" />
		<?php wp_nonce_field( 'move_task_to' ); ?>

		<label for="move_task"><?php esc_html_e( 'Move the task to', 'task-manager' ); ?></label>
		<input type="text" class="search-parent" />
		<input type="hidden" name="to_element_id" />
		<input type="button" class="action-input" data-loader="form" data-parent="form" value="<?php esc_html_e( 'Move', 'task-manager' ); ?>" />
		<div class="list-posts">
		</div>
	</div>
</div>
