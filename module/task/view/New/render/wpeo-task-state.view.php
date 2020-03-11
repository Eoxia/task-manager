<div class="table-cell-container">
	<div
		<?php  if ( get_user_meta( get_current_user_id(), '_tm_project_state', true ) == true && $data['count_uncompleted_points'] > 0 ) : ?>
			class="wpeo-button button-main button-square-40 load-complete-point wpeo-tooltip-event active"
		<?php else : ?>
			class="wpeo-button button-transparent button-square-40 load-complete-point wpeo-tooltip-event"
		<?php endif; ?>
		aria-label="<?php esc_html_e( 'Show uncompleted tasks', 'task-manager' ); ?>"
		data-action="load_point"
		data-nonce="<?php echo wp_create_nonce( 'load_point' ); ?>"
		data-task-id="<?php echo $data['task_id']; ?>"
		data-point-state="uncompleted">
		<i class="button-icon far fa-square"></i>
	</div>
	<span class="count-uncompleted-tasks"><?php echo esc_attr( $data['count_uncompleted_points'] ); ?></span>
	<div class="wpeo-button button-transparent button-square-40 load-complete-point wpeo-tooltip-event"
		aria-label="<?php esc_html_e( 'Show completed tasks', 'task-manager' ); ?>"
		data-action="load_point"
		data-nonce="<?php echo wp_create_nonce( 'load_point' ); ?>"
		data-task-id="<?php echo $data['task_id']; ?>"
		data-point-state="completed">
		<i class="button-icon far fa-check-square"></i>
	</div>
	<span class="count-completed-tasks"><?php echo esc_attr( $data['count_completed_points'] ); ?></span>
</div>
