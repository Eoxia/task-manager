<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="wpeo-task-info">
	<h3><?php _e( 'Task informations', 'task-manager' ); ?></h3>
	<ul>
		<li>
			<?php $date_output_format = get_option( 'date_format' ) . ' à ' . get_option( 'time_format' ); ?>
			<div><?php _e( 'Created ', 'task-manager' ); ?> <?php echo mysql2date( $date_output_format, $element->date, true ); ?></div>
		</li>
		<!-- Temps de la tâche / Time of the task -->
		<li>
			<span class="dashicons dashicons-clock"></span>
			<div><?php _e( 'Estimated time', 'task-manager' ); ?> : <strong><?php echo $element->option['time_info']['estimated']; ?></strong></div>
			<div><?php _e( 'Elapsed time', 'task-manager' ); ?> : <strong><?php echo $element->option['time_info']['elapsed']; ?></strong></div>
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
			<?php _e( 'Number users associate to the task', 'task-manager' ); ?> : <strong><?php echo count( $element->option['user_info']['affected_id'] ); ?></strong>
		</li>
	</ul>

	<!-- Réference -->
	<ul>
		<li>
			<?php _e( 'Ref', 'task-manager' ); ?>: <strong>#<?php echo $element->id; ?></strong>
		</li>
	</ul>
</div>
