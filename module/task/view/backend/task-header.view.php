<?php
/**
 * La vue du header d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="wpeo-task-header">
	<li class="wpeo-task-author"><?php echo do_shortcode( '[task_manager_owner_task task_id="' . $task->data['id'] . '" owner_id="' . $task->data['user_info']['owner_id'] . '"]' ); ?></li>

	<li class="wpeo-task-main-info" >
		<div class="wpeo-task-title">
			<input type="text" name="task[title]" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title" value="<?php echo esc_html( $task->data['title'] ); ?>" />
		</div>
		<ul>
			<li class="wpeo-task-id">#<?php echo esc_html( $task->data['id'] ); ?></li>

			<li class="wpeo-task-time-history wpeo-modal-event tooltip hover"
					aria-label="<?php esc_html_e( 'Edit dead line', 'task-manager' ); ?>"
					data-class="history-time wpeo-wrap wpeo-project-wrap"
					data-action="load_time_history"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_time_history' ) ); ?>"
					data-title="<?php echo sprintf( __( '#%1$s Time history', 'task-manager' ), esc_attr( $task->data['id'] ) ); ?>"
					data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">

				<?php if ( 0 !== $task->data['last_history_time']->data['id'] ) : ?>
					<?php if ( 'recursive' === $task->data['last_history_time']->data['custom'] ) : ?>
						<span><?php esc_html_e( 'Repeated', 'task-manager' ); ?>
					<?php else : ?>
						<span class="wpeo-task-date tooltip hover" aria-label="<?php echo esc_html_e( 'Dead line', 'task-manager' ); ?>">
							<i class="dashicons dashicons-calendar-alt"></i>
							<span><?php echo esc_html( $task->data['last_history_time']->data['due_date']['rendered']['date'] ); ?></span>
						</span>
					<?php endif; ?>
				<?php endif; ?>

				<span class="wpeo-task-elapsed">
					<i class="dashicons dashicons-clock"></i>
					<span class="elapsed tooltip hover" aria-label="<?php echo esc_html_e( 'Elapsed time', 'task-manager' ); ?>"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] ) ); ?></span>
				</span>
				<span class="wpeo-task-estimated">
					<?php if ( ! empty( $task->data['last_history_time']->data['estimated_time'] ) ) : ?>
						<span class="estimated tooltip hover" aria-label="<?php echo esc_html_e( 'Estimated time', 'task-manager' ); ?>">/ <?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'] ) ); ?></span>
					<?php endif; ?>
				</span>

			</li>
		</ul>
	</li>

	<li class="wpeo-dropdown wpeo-task-setting"
			data-parent="toggle"
			data-target="content"
			data-mask="wpeo-project-task">

		<span class="wpeo-button button-transparent dropdown-toggle"
			><i class="fa fa-ellipsis-v"></i></span>

		<div class="dropdown-content task-header-action">
			<?php
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/toggle-content', array(
				'task' => $task,
			) );
			?>
		</div>
	</li>
</ul>

<ul class="wpeo-task-filter" >
	<?php echo apply_filters( 'tm_task_header', '', $task ); // WPCS: XSS ok. ?>

	<li class="display-method-buttons">
		<span class="dashicons dashicons-screenoptions list-display active wpeo-tooltip-event"
			aria-label="<?php echo esc_attr_e( 'Edit display', 'task-manager' ); ?>"></span>

		<span class="action-attribute dashicons dashicons-editor-ul grid-display wpeo-tooltip-event"
					data-action="load_last_activity"
					aria-label="<?php echo esc_attr_e( 'Activity display', 'task-manager' ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
					data-tasks-id="<?php echo esc_attr( $task->data['id'] ); ?>"></span>
	</li>
</ul>
