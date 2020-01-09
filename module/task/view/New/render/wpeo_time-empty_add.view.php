<div class="table-cell-container">
	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
	     aria-label="<?php esc_html_e( 'Add Comment', 'task-manager' ); ?>"
	     data-id="<?php echo $data['value']; ?>"
	     data-post-id="<?php echo $data['value1']; ?>"
	     data-parent-id="<?php echo $data['value2']; ?>"
	     data-action="edit_comment"
	     data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_comment' ) ); ?>">
		<i class="fas fa-comment-dots"></i>
	</div>
</div>
