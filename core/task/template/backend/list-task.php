<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( !empty ( $list_task ) ):
	foreach ( $list_task as $key => $task ) :
		if( is_int($key) ):
			$task_controller->render_task( $task, !empty( $status ) ? $status : '' );
		endif;
	endforeach;
endif;
echo apply_filters( 'task_window', '' );
