<div class="table-cell-container">
	<?php if ( $data['value'] == 'archive' ) : ?>
		<div class="wpeo-button wpeo-tooltip-event button-transparent action-attribute button-square-50 project-archive"
			aria-label="<?php esc_attr_e( 'Unpack the Project', 'task-manager' ); ?>"
			data-id="<?php echo esc_attr( $data['task_id'] ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'tm_unpack_task' ) ); ?>"
			data-action="tm_unpack_task">
			<i class="fas fa-archive" style="font-size: 25px"></i>
		</div>
	<?php else : ?>
		<div class="wpeo-button wpeo-tooltip-event button-transparent button-square-50 action-attribute"
			aria-label="<?php esc_html_e( 'Archive the Project', 'task-manager' ); ?>"
			data-action="to_archive"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'to_archive' ) ); ?>"
			data-id="<?php echo $data['task_id']; ?>">
			<i class="fas fa-box-open" style="font-size: 25px"></i>
		</div>
	<?php endif; ?>
</div>
