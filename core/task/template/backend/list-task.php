<?php echo apply_filters( 'task_list_title', '', !empty( $name ) ? $name : '' ); ?>
<div class="list-task">
	<?php
	if ( !empty ( $list_task ) ):
		foreach ( $list_task as $key => $task ) :
			if( is_int($key) ):
				$task_controller->render_task( $task, !empty( $status ) ? $status : '' );
			else:
				echo apply_filters( 'task_window', '' );
				?></div><?php
				$task_controller->render_list_task( $key, $task );

				?></div><?php
			endif;
		endforeach;
	endif;
	echo apply_filters( 'task_window', '' );

	?>
