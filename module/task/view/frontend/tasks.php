<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-project-wrap">
	<?php if( !empty ($tasks) && !empty( $tasks[0] ) ) : ?>
		<?php foreach ( $tasks as $task ) :?>
			<div class="grid-item wpeo-project-task-<?php echo $task->ID; ?> <?php echo $task->informations->my_task; ?>  <?php echo $task->informations->class_tags; ?>">
				<?php require( wpeoTasksTemplate_ctr::get_template_part( WPEOMTM_TASK_DIR, WPEOMTM_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'task' ) ); ?>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php _e( 'Start by creating a task', 'task-manager' )?>
	<?php endif; ?>
</div>