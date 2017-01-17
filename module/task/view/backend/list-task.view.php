<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) exit;
 echo apply_filters( 'task_list_title', '', !empty( $name ) ? $name : '' ); ?>

<div class="list-task">
	<?php
	if ( !empty ( $list_task ) ):
		foreach ( $list_task as $key => $task ) :
			if( is_int($key) ):
				Task_Class::g()->render_task( $task, !empty( $status ) ? $status : '' );
			endif;
		endforeach;
	endif;
	echo apply_filters( 'task_window', '' );
	?>
</div>

<?php
// TODO Revoir l'utilité d'afficher les tâches sans ID...
if ( !empty ( $list_task ) ):
	foreach ( $list_task as $key => $task ) :
		if( !is_int($key) ):
			Task_Class::g()->render_list_task( $key, $task );
		endif;
	endforeach;
endif;
?>
