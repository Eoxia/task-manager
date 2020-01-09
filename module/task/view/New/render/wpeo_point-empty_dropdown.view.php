<div class="table-cell-container">
	<div class="wpeo-dropdown dropdown-right">
		<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
		<div class="dropdown-content point-header-action">
			<?php \eoxia\View_Util::exec( 'task-manager', 'point', 'backend/toggle-content', array( 'point' => $data['point'] ) ); ?>
		</div>
	</div>
</div>
