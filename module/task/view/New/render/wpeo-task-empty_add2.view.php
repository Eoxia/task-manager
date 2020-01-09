<div class="table-cell-container">
	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
		aria-label="<?php esc_html_e( 'Add Task', 'task-manager' ); ?>"
		data-id="<?php echo $data['value']; ?>"
		data-action="create_point"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_point' ) ); ?>">
		<i class="fas fa-check-square"></i>
	</div>
</div>

