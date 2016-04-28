<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<form style="padding: 0;"></form>

<header>
	<div class="wpeo-window-background-avatar" style="background: url(<?php echo $avatar_background; ?>) 50% 50%; background-size: cover;"></div>
	<h2><?php echo $task->title; ?></h2>
	<i class="dashicons dashicons-no-alt"></i>
</header>

<div id="wpeo-task-sub-header" data-id="<?php echo $task->id; ?>" >
	<?php echo apply_filters( 'window_task_owner', '' ); ?>

	<ul id="wpeo-task-action">
		<li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_archive_task_' . $task->id ); ?>" class="wpeo-task-action-list wpeo-send-task-to-archive dashicons dashicons-archive"></span></li>
		<!--  <li><a href="<?php echo get_post_permalink( $task->id ); ?>" title="<?php _e( 'View the task', 'task-manager' ); ?>" target="_blank" class="wpeo-task-action-list"><span class="dashicons dashicons-visibility"></span></a></li> -->
		<li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_export_task_' . $task->id ); ?>" class="wpeo-task-action-list wpeo-export dashicons dashicons-download"></span></li>
		<!-- <li><span style="color: red;" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_export_task_' . $task->id ); ?>" class="wpeo-task-action-list wpeo-export-comment dashicons dashicons-download"></span></li> -->
		<li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_task_' . $task->id ); ?>" class="wpeo-task-action-list wpeo-send-task-to-trash dashicons dashicons-trash" title="<?php _e( 'Move to trash', 'task-manager'); ?>" ></span></li>
	</ul>
</div>

<div id="wpeo-task-info">
	<h3><?php _e( 'Task informations', 'task-manager' ); ?></h3>
	<ul>
		<li>
			<?php $date_output_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' ); ?>
			<div><?php _e( 'Create', 'task-manager' ); ?> : <?php echo mysql2date( $date_output_format, $task->date, true ); ?></div>
		</li>
		<!-- Temps de la tâche / Time of the task -->
		<li>
			<span class="dashicons dashicons-clock"></span>
			<div><?php _e( 'Estimated time', 'task-manager' ); ?> : <strong><?php echo $task->option['time_info']['estimated']; ?></strong></div>
			<div><?php _e( 'Elapsed time', 'task-manager' ); ?> : <strong><?php echo $task->option['time_info']['elapsed']; ?></strong></div>
			<?php //_e( 'minute(s)', 'task-manager' ); ?>
		</li>

		<!--  Nombre de commentaires sur la tâche / Number of comments on the task -->
		<li>
			<span class="dashicons dashicons-admin-comments"></span>
			<?php _e( 'Number of comments on the task', 'task-manager' ); ?> : <strong><?php echo count( 0 ); ?></strong>
		</li>

		<!-- Nombre de personne associée à la tâche / Number users associate to the task -->
		<li>
			<span class="dashicons dashicons-groups"></span>
			<?php _e( 'Number users associate to the task', 'task-manager' ); ?> : <strong><?php echo count( $task->option['user_info']['affected_id'] ); ?></strong>
		</li>
	</ul>

	<!-- Réference -->
	<ul>
		<li>
			<?php _e( 'Ref', 'task-manager' ); ?>: <strong>#<?php echo $task->id; ?></strong>
		</li>
	</ul>
</div>

<div id="wpeo-task-option">
	<ul>
		<li>
			<i class="dashicons dashicons-migrate"></i><?php _e( 'Send the task to element <strong>#</strong>', 'task-manager' ); ?>
		</li>
		<li>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
				 <!-- For admin-ajax -->
			 	<?php wp_nonce_field( 'wpeo_nonce_send_to_task_' . $task->id ); ?>
				<input type="text" name="element_id" placeholder="162" />
				<input type="hidden" name="task_id" value="<?php echo $task->id; ?>" />
				<input type="button" class="wpeo-send-task-to-element" value="<?php _e( 'Send', 'task-manager' ); ?>" />
			</form>
		</li>
	</ul>

	<ul>
		<li>
			<?php _e( 'Due date', 'task-manager' ); ?>
		</li>
		<li>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
				 <!-- For admin-ajax -->
			 	<?php wp_nonce_field( 'wpeo_nonce_due_date_' . $task->id ); ?>
				<input type="text" class="isDate" name="due_date" value="<?php echo !empty( $task->option['date_info']['due'] ) ? $task->option['date_info']['due'] : current_time( 'Y-m-d' ); ?>" />
				<input type="hidden" name="task_id" value="<?php echo $task->id; ?>" />
				<input type="button" class="wpeo-update-due-date" value="<?php _e( 'Update', 'task-manager' ); ?>" />
			</form>
		</li>
	</ul>
</div>
