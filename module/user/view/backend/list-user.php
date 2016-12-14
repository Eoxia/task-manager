<?php if ( !defined( 'ABSPATH' ) ) exit;

if ( !empty( $list_user ) ) :
	foreach ( $list_user as $user ) :
		if ( $task->option['user_info']['owner_id' ] != $user->id ):
			$active = '';
			if( in_array( $user->id, $task->option['user_info']['affected_id'] ) ) :
				$active = 'active';
			endif;

			$nonce = 'wpeo_nonce_update_user_' . $user->id;
			require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
		endif;
	endforeach;
endif;
?>
