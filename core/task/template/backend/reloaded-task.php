<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-project-task-container">
	<span class="task-marker"></span>
	<p class="wpeo-task-project-error"></p>

	<!--  Header  -->
	<?php require( wpeo_template_01::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'backend', 'task', 'header' ) ); ?>

	<!-- Point -->
	<?php
	if( class_exists( 'point_controller_01' ) ):
		point_controller_01::render_point( $task->id, !empty( $task->point ) ? $task->point : array() );
	endif;
	?>

	<!-- Footer -->
	<div class="wpeo-task-footer">
		<?php
		if	( class_exists( 'tag_controller_01' ) ):
			global $tag_controller;
			$tag_controller->display_tag_in_object( $task );
		endif;

		if ( class_exists( 'user_controller_01' ) ):
			global $wp_project_user_controller;
			$wp_project_user_controller->display_user_in_object( $task );
		endif;
		?>
	</div>

</div>
