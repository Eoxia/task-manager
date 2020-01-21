<div class="table-cell-container">
	<div class="wpeo-dropdown dropdown-horizontal dropdown-right">
		<div class="dropdown-toggle wpeo-button button-square-50 button-light"><i class="fas fa-ellipsis-v"></i></div>
		<div class="dropdown-content point-header-action">
			<?php \eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/toggle-content', array( 'comment' => $data['comment'] ) ); ?>
		</div>
	</div>
</div>
