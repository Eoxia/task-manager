<div class="table-cell-container wpeo-gridlayout grid-2 grid-gap-0">
	<div class="wpeo-dropdown dropdown-horizontal dropdown-right">
		<div class="dropdown-toggle wpeo-button button-square-50 button-light"><i class="fas fa-ellipsis-v"></i></div>
		<ul class="dropdown-content">
			<li class="dropdown-item action-attribute wpeo-tooltip-event"
			    aria-label="<?php esc_html_e( 'Recompile the task', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="recompile_task"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'recompile_task' ) ); ?>">
				<i class="fas fa-redo"></i>
<!--				<span>--><?php //echo esc_html( 'Recompile the task' ); ?><!--</span>-->
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event"
			    data-class="popup-notification"
			    aria-label="<?php esc_html_e( 'Notify the team', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_notify_popup"
			    data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Notify popup', 'task-manager' ), esc_attr( $data['value'] ) ) ); ?>"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_notify_popup' ) ); ?>">
				<i class="fas fa-bell"></i>
<!--				<span>--><?php //echo esc_html( 'Notify the team' ); ?><!--</span>-->
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event"
			    data-class="popup-export"
			    aria-label="<?php esc_html_e( 'Upload', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_export_popup"
			    data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Export task data', 'task-manager' ), esc_attr( $data['value'] ) ) ); ?>"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_export_popup' ) ); ?>">
				<i class="fas fa-upload"></i>
<!--				<span>--><?php //echo esc_html( 'Upload' ); ?><!--</span>-->
			</li>

			<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event"
			    aria-label="<?php esc_html_e( 'Download', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="load_import_modal"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_import_modal' ) ); ?>">
				<i class="fas fa-download"></i>
<!--				<span>--><?php //echo esc_html( 'Download' ); ?><!--</span>-->
			</li>

			<li class="dropdown-item action-delete wpeo-tooltip-event"
			    aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
			    data-id="<?php echo $data['value']; ?>"
			    data-action="delete_task"
			    data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
			    data-message-delete="<?php echo esc_attr_e( 'Are you sure you want to remove this task ?', 'task-manager' ); ?>">
				<i class="fas fa-trash"></i>
<!--				<span>--><?php //echo esc_html( 'Delete' ); ?><!--</span>-->
			</li>
		</ul>
	</div>

	<div class="wpeo-button wpeo-tooltip-event button-main button-square-50 action-attribute"
	     aria-label="<?php esc_html_e( 'Add Task', 'task-manager' ); ?>"
	     data-direction="left"
	     data-parent-id="<?php echo $data['value']; ?>"
	     data-action="edit_point"
	     data-toggle="false"
	     data-content="<?php esc_html_e( 'New task', 'task-manager' ); ?>"
	     data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_point' ) ); ?>">
		<i class="button-icon fas fa-check-square first-icon"></i>
		<i class="fas fa-plus-circle second-icon"></i>
	</div>
</div>
