<?php namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="timeline-block week">
	<section>
		<header>
			<ul>
				<li class="avatar"><?php echo get_avatar( get_current_user_id(), 32 ); ?></li>
				<li class="user-mail"><?php echo get_userdata( get_current_user_id() )->user_email; ?></li>
				<li class="date">
					<?php echo __( 'Week', 'task-manager' ) . ' : ' . $week->format( 'W' ); ?>
				</li>
			</ul>
		</header>

		<div class="timeline-block-content">

			<ul class="dashboard-week">
				<li>
					<span class="dashicons dashicons-calendar-alt"></span>
					<?php _e( 'Working time', 'task-manager' ); ?> :
					<?php if ( !empty( $working_time ) ): ?>
						<strong><?php echo $working_time; ?></strong>
					<?php else: ?>
						<a target="_blank" href="<?php echo get_edit_user_link( get_current_user_id() ); ?>#working-time"><?php _e( 'Setup working time', 'task-manager' ); ?></a>
					<?php endif; ?>
				</li>
				<li>
					<span class="dashicons dashicons-clock"></span>
					<?php _e( 'Worked time', 'task-manager' ); ?> : <strong><?php echo $results['worked_time']; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-layout"></span>
					<?php _e( 'Task(s) created', 'task-manager' ); ?> : <strong><?php echo 0; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-exerpt-view"></span>
					<?php _e( 'Point(s) created', 'task-manager' ); ?> : <strong><?php echo 0; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-yes"></span>
					<?php _e( 'Completed point(s)', 'task-manager' ); ?> : <strong><?php echo 0; ?></strong>
				</li>
				<?php if ( !empty( $working_time ) ): ?>
				<li>
					<span class="dashicons dashicons-trash"></span>
					<?php _e( 'Waste time', 'task-manager' ); ?> : <strong><?php echo 0; ?></strong>
				</li>
				<?php endif; ?>
			</ul>


			<?php if ( !empty( $list_task ) ): ?>
				<ul class="task-week">
				<?php foreach( $list_task as $task ): ?>
					<li>
						<strong>#<?php echo $task->id?></strong> <?php echo $task->title; ?> : <span class="dashicons dashicons-clock"></span> <strong><?php echo $list_task_worked_time[$task->id]; ?></strong>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div> <!-- timeline-block-content -->
	</section>
</div> <!-- timeline-block -->

<div class="content-day">
	<?php
	foreach ( $day_date_period as $day ) {
		Timeline_Class::g()->render_day( get_current_user_id(), $day->format( 'Y' ), $day->format( 'm' ), $day->format( 'd' ), $results );
	}
	?>
</div>
