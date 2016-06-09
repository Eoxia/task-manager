<?php if ( ! defined( 'ABSPATH' ) ) exit;
 require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time-add' ) ); ?>

<div id="wpeo-task-point-history">
	<?php echo apply_filters( 'window_point_content', '', $element->parent_id, $element->id, $list_time ); ?>
</div>
