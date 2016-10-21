<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="timeline-block week is-hidden">
	<section>
		<header>
			<ul>
				<li class="avatar"><?php echo get_avatar( $user_id, 32 ); ?></li>
				<li class="user-mail"><?php echo get_userdata( $user_id )->user_email; ?></li>
				<li class="date">
					<?php echo __( 'Week', 'wpeotimeline-i18n' ) . ' : ' . $week; ?>
				</li>
			</ul>
		</header>

		<div class="timeline-block-content">

			<ul class="dashboard-week">
				<li>
					<span class="dashicons dashicons-calendar-alt"></span>
					<?php _e( 'Working time', 'wpeotimeline-i18n' ); ?> :
					<?php if ( !empty( $working_time ) ): ?>
						<strong><?php echo taskmanager\util\wpeo_util::convert_to_hours_minut( $working_time ); ?></strong>
					<?php else: ?>
						<a target="_blank" href="<?php echo get_edit_user_link( $user_id ); ?>#working-time"><?php _e( 'Setup working time', 'wpeotimeline-i18n' ); ?></a>
					<?php endif; ?>
				</li>
				<li>
					<span class="dashicons dashicons-clock"></span>
					<?php _e( 'Worked time', 'wpeotimeline-i18n' ); ?> : <strong><?php echo taskmanager\util\wpeo_util::convert_to_hours_minut( $worked_time ); ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-layout"></span>
					<?php _e( 'Task(s) created', 'wpeotimeline-i18n' ); ?> : <strong><?php echo $number_task_created; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-exerpt-view"></span>
					<?php _e( 'Point(s) created', 'wpeotimeline-i18n' ); ?> : <strong><?php echo $number_point_created; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-yes"></span>
					<?php _e( 'Completed point(s)', 'wpeotimeline-i18n' ); ?> : <strong><?php echo $number_point_completed; ?></strong>
				</li>
				<?php if ( !empty( $working_time ) ): ?>
				<li>
					<span class="dashicons dashicons-trash"></span>
					<?php _e( 'Waste time', 'wpeotimeline-i18n' ); ?> : <strong><?php echo taskmanager\util\wpeo_util::convert_to_hours_minut( $waste_time ); ?></strong>
				</li>
				<?php endif; ?>
			</ul>


			<?php if ( !empty( $list_task ) ): ?>
				<ul class="task-week">
				<?php foreach( $list_task as $task ): ?>
					<li>
						<strong>#<?php echo $task->id?></strong> <?php echo $task->title; ?> : <span class="dashicons dashicons-clock"></span> <strong><?php echo taskmanager\util\wpeo_util::convert_to_hours_minut( $list_task_worked_time[$task->id] ); ?></strong>
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
		$task_timeline->render_day( $user_id, $day->format( 'Y' ), $day->format( 'm' ), $day->format( 'd' ), $list_task_created, $list_point_created, $list_point_completed, $list_comment );
	}
	?>
</div>
