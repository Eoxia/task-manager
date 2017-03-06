<div class="wrap">
	<h2><?php esc_html_e( 'Task Manager: Time Exceeded', 'task-manager' ); ?></h2>

	De <input type="text" class="date" value="<?php echo esc_attr( $date_start ); ?>" />
	a <input type="text" class="date" value="<?php echo esc_attr( $date_end ); ?>"  />

	<table style="width: 100%;">
		<tr>
			<th>ID</th>
			<th>Titre</th>
			<th>Tâche parent</th>
			<th>Temps (min)</th>
			<th>Temps dépassé (min)</th>
		</tr>

		<?php
		if ( ! empty( $tasks_exceed_time ) ) :
			foreach ( $tasks_exceed_time as $task ) :
				?>
				<tr>
					<td><?php echo esc_html( $task->id ); ?></td>
					<td><?php echo esc_html( $task->title ); ?></td>
					<td>
						<?php if ( ! empty( $task->task_parent->ID ) ) : ?>
							<a target="_blank" href="<?php echo esc_attr( admin_url( 'post.php?post=' . $task->task_parent->ID . '&action=edit' ) ); ?>">
								<?php echo esc_html( $task->task_parent->post_title ); ?>
							</a>
						<?php else : ?>
							<?php echo esc_html( $task->task_parent ); ?>
						<?php endif ; ?>
					</td>

					<td><?php echo esc_html( $task->option['time_info']['elapsed'] . '/' . $task->display_estimated ); ?></td>
					<td><?php echo esc_html( $task->option['time_info']['elapsed'] - $task->display_estimated ); ?></td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</table>
</div>
