<?php if ( ! defined( 'ABSPATH' ) ) { exit;
} ?>

<!-- Task header : Pour modifier le titre, le temps estimé et ouvrir le dashboard à droite -->
<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
	<?php wp_nonce_field( 'wpeo_nonce_edit_task_' . $task->id ); ?>
	<input type="hidden" name="task[option][front_info][display_color]" value="<?php echo htmlspecialchars( ! empty( $task->option['front_info']['display_color'] ) ? $task->option['front_info']['display_color'] : '' ); ?>" />
	<ul class="wpeo-task-header">
		<li class="wpeo-task-author"><?php echo get_avatar( $task->author_id, 32 ); ?></li>

		<li class="wpeo-task-id">#<?php echo $task->id; ?></li>

		<li class="wpeo-task-title">
			<input <?php echo $disabled_filter; ?> data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_task_' . $task->id ); ?>" type="text" name="task[title]" class="wpeo-project-task-title" value="<?php echo htmlspecialchars( ! empty( $task->title ) ? $task->title : 'New task' ); ?>" />
		</li>

		<li class="wpeo-task-setting">
			<span class="wpeo-task-open-action" title="<?php _e( 'Options of task', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
			<div class="task-header-action">
				<?php echo apply_filters( 'task_header_action', '', $task ); ?>
			</div>
		</li>
	</ul>
	<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'task-header-information' ) ); ?>
</form>
