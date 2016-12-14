<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
if ( !empty( $list_time ) ):
	foreach ( $list_time as $time ):
		require( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time' ) );
	endforeach;
else:
	?>
	<p class="wpeo-point-no-comment"><?php _e( 'There is no comment on this point. Click above to add one.', 'task-manager' );?></p>
	<?php
endif;
?>
