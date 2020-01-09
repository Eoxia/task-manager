<div class="table-cell-container">
	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
	     aria-label="<?php esc_html_e( 'Add Comment', 'task-manager' ); ?>"
	     data-direction="left"
	     data-post-id="<?php echo $data['task_id']; ?>"
	     data-parent-id="<?php echo $data['point_id']; ?>"
	     data-action="edit_comment"
	     data-content="tmp"
	     data-toggle="false"
	     data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_comment' ) ); ?>">
		<i class="fas fa-comment-dots"></i>
	</div>
</div>

