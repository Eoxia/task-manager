<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<div data-id="<?php echo $task->id; ?>" data-affected-id="<?php echo implode( ',', $task->user_info['affected_id'] ); ?>" data-owner-id="<?php echo $task->user_info['owner_id']; ?>" data-affected-tag-id="<?php echo implode( ',', !empty( $task->taxonomy['wpeo_tag'] ) ? $task->taxonomy['wpeo_tag'] : array() ); ?>" class="<?php echo !empty( $class ) ? $class : ''; ?> wpeo-project-task <?php echo !empty( $task->front_info['display_color'] ) ? $task->front_info['display_color'] : ''; ?>">
	<div class="wpeo-project-task-container">
		<span class="task-marker"></span>

		<!--  Header  -->
		<?php View_Util::exec( 'task', 'backend/task-header', array( 'task' => $task ) ); ?>

		<!-- Content -->
		<?php echo apply_filters( 'task_content', '', $task ); ?>

		<!-- Footer -->
		<div class="wpeo-task-footer">
			<?php echo apply_filters( 'task_footer', '', $task ); ?>
		</div>
	</div>
</div>
