<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( !empty( $list_user ) ) :
	foreach ( $list_user as $user ) :
		if ( $_POST['owner_id'] != $user->id && !in_array( $user->id, $task->option['user_info']['affected_id'] ) ):
			$nonce 			= 'wpeo_nonce_edit_task_owner_user_' . $user->id;
			require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
		endif;
	endforeach;
endif;
?>
