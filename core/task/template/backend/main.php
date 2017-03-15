<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

echo apply_filters( 'task_list_title', '', ! empty( $name ) ? $name : '' ); ?>

<div class="list-task">
	<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) ); ?>
</div>
