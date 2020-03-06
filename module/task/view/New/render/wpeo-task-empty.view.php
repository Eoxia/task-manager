<div class="table-cell-container">
	<div class="wpeo-button button-square-50 button-transparent">
		<?php if ( get_user_meta( get_current_user_id(), '_tm_project_state', true ) == true  && $data['count_uncompleted_task'] > 0 ) : ?>
			<i class="button-icon fas fa-angle-down"></i>
		<?php else : ?>
			<i class="button-icon fas fa-angle-right"></i>
		<?php endif; ?>
	</div>
</div>
