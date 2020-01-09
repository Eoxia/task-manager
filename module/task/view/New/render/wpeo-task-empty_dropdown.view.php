<div class="table-cell-container">
	<div class="wpeo-dropdown dropdown-right">
		<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
		<ul class="dropdown-content">
			<li class="dropdown-item action-attribute wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Recompile the task', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="recompile_task"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'recompile_task' ) ); ?>">
				<i class="fas fa-redo"></i>
				<span><?php echo esc_html( 'Recompile the task' ); ?></span>
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Notify the team', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_notify_popup"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_notify_popup' ) ); ?>">
				<i class="fas fa-bell"></i>
				<span><?php echo esc_html( 'Notify the team' ); ?></span>
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Upload', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_export_popup"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_export_popup' ) ); ?>">
				<i class="fas fa-upload"></i>
				<span><?php echo esc_html( 'Upload' ); ?></span>
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Download', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_import_modal"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_import_modal' ) ); ?>">
				<i class="fas fa-download"></i>
				<span><?php echo esc_html( 'Download' ); ?></span>
			</li>

			<li class="dropdown-item action-attribute wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Archive', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="to_archive"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'to_archive' ) ); ?>">
				<i class="fas fa-archive"></i>
				<span><?php echo esc_html( 'Archive' ); ?></span>
			</li>

			<li class="dropdown-item action-delete wpeo-tooltip-event wpeo-button button-transparent"
			    data-direction="left"
			    aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="delete_task"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
			    data-message-delete="<?php echo esc_attr_e( 'Are you sure you want to remove this task ?', 'task-manager' ); ?>">
				<i class="fas fa-trash"></i>
				<span><?php echo esc_html( 'Delete' ); ?></span>
			</li>
		</ul>
	</div>
</div>
