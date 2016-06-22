<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php 
if ( !empty( $list_point_time ) ):
	foreach ( $list_point_time as $point_time ):
		require( wpeo_template_01::get_template_part( WPEOMTM_POINT_DIR, WPEOMTM_POINT_TEMPLATES_MAIN_DIR, 'frontend', 'point', 'time' ) );
	endforeach;
else:
	?>
	<p class="wpeo-point-no-comment"><?php _e( 'There is no comment on this point. Click above to add one.', 'task-manager' );?></p>
	<?php 
endif;
?>