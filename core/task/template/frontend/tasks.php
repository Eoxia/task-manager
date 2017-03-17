<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

	<?php if( !empty ($tasks) && !empty( $tasks[0] ) ) : ?>
		<?php foreach ( $tasks as $task ) :?>
			<div class="grid-item wpeo-project-task-<?php echo $task->ID; ?> <?php echo $task->informations->my_task; ?>  <?php echo $task->informations->class_tags; ?>">
				<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'task' ) ); ?>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php _e( 'Start by creating a task', 'task-manager' )?>
	<?php endif; ?>
