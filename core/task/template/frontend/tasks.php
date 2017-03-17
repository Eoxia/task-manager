<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

	<?php if( !empty ($tasks) && !empty( $tasks[0] ) ) : ?>
		<div class="grid-item wpeo-project-task <?php echo $task->informations->my_task; ?>  <?php echo $task->informations->class_tags; ?>">
			<?php foreach ( $tasks as $task ) :?>
					<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'task' ) ); ?>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<?php _e( 'Start by creating a task', 'task-manager' )?>
	<?php endif; ?>
