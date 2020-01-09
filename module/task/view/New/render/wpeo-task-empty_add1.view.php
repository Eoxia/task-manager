<div class="table-cell-container">
	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
		aria-label="<?php esc_html_e( 'Add Project', 'task-manager' ); ?>"
		data-id="<?php echo $data['value']; ?>"
		data-action="create_task"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>" >
		<i class="fas fa-thumbtack"></i>
	</div>
</div>
