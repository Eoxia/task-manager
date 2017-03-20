<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-window-ask-task-container">

	<a href="#" class="wpeo-ask-task"><?php _e( 'Ask a ticket', 'task-manager' ); ?></a>

	<div id="wpeo-window-ask-task" >
		<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
			<input type="hidden" name="action" value="ask_task">
			<input type="text" name="point[content]" placeholder="<?php _e( 'Write your ticket', 'task-manager' ); ?>">
			<input type="button" value="<?php _e( 'Confirm', 'task-manager' ); ?>">
		</form>
	</div>
</div>
