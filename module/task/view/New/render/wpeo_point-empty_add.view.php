<div class="table-cell-container">
	<div class="wpeo-dropdown dropdown-right">
		<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
		<div class="dropdown-content point-header-action">
			<?php \eoxia\View_Util::exec( 'task-manager', 'point', 'backend/toggle-content', array( 'point' => $data['point'] ) ); ?>
		</div>
	</div>

	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
		aria-label="<?php esc_html_e( 'Add Comment', 'task-manager' ); ?>"
		data-direction="left"
		data-post-id="<?php echo $data['task_id']; ?>"
		data-parent-id="<?php echo $data['point_id']; ?>"
		data-action="edit_comment"
		data-content="<?php esc_html_e( 'New comment', 'task-manager' ); ?>"
		data-toggle="false"
		data-time="15"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_comment' ) ); ?>">
		<i class="fas fa-comment-dots"></i>
	</div>
</div>

