<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap wpeo-project-wrap wpeo-project-wrap-custom">
	<input type="hidden" class="wpeo-task-post-parent" value="<?php echo $post->ID; ?>" />

	<div class="wpeo-project-dashboard" >
		<h2><?php _e('Tasks manager', 'task-manager'); ?> <a href="#" class="wpeo-project-new-task add-new-h2"><?php _e( 'New task', 'task-manager' ); ?></a></h2>
	</div> 

	<div class="wpeo-project-list-task">
		<div class="wpeo-project-task-size"></div>
		<?php require( wpeo_template_01::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list', 'task' ) ); ?>
	</div>
	
	<?php if( empty( $array_task ) ) :?>
		<span class='wpeo-tasks-no-task'><?php _e( 'No tasks, press the "New task" button for create a task', 'task-manager' );?></span>
	<?php endif; ?>	
</div><!-- wps-pos-dashboard-wrap -->