<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="timeline-block month is-hidden">
	<section>
		<header>
			<ul>
				<li class="avatar"><?php echo get_avatar( $user_id, 32 ); ?></li>
				<li class="user-mail"><?php echo get_userdata( $user_id )->user_email; ?></li>
				<li class="date">
					<?php 
					if ( strlen( $month ) == 1 )
						$month = '0' . $month;
					
					echo mysql2date( 'F', $year . '-' . $month . '-01' ) . ' ' . $year;
					?>
				</li>
			</ul>
		</header>

		<div class="timeline-block-content">
		
			<ul class="dashboard-month">
				<li>
					<span class="dashicons dashicons-calendar-alt"></span>
					<?php _e( 'Working time', 'wpeotimeline-i18n' ); ?> : 
					<?php if ( !empty( $working_time ) ): ?>
						<strong><?php echo wpeo_util::convert_to_hours_minut( $working_time * 4 ); ?></strong>
					<?php else: ?>
						<a target="_blank" href="<?php echo get_edit_user_link( $user_id ); ?>#working-time"><?php _e( 'Setup working time', 'wpeotimeline-i18n' ); ?></a>
					<?php endif; ?>
				</li>
				<li>
					<span class="dashicons dashicons-clock"></span>
					<?php _e( 'Worked time', 'wpeotimeline-i18n' ); ?> : <strong><?php echo wpeo_util::convert_to_hours_minut( $worked_time ); ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-layout"></span>
					<?php _e( 'Task(s) created', 'wpeotimeline-i18n' ); ?> : <strong><?php echo $number_task_created; ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-exerpt-view"></span>
					<?php _e( 'Point(s) created', 'wpeotimeline-i18n' ); ?> : <strong><?php echo count( $list_point_created ); ?></strong>
				</li>
				<li>
					<span class="dashicons dashicons-yes"></span>
					<?php _e( 'Completed point(s)', 'wpeotimeline-i18n' ); ?> : <strong><?php echo count( $list_point_completed ); ?></strong>
				</li>
			</ul>
			
			
			<?php if ( !empty( $list_task ) ): ?>
				<ul class="task-month">
				<?php foreach( $list_task as $task ): ?>
					<li>
						<strong>#<?php echo $task->id?></strong> <?php echo $task->title; ?> : <span class="dashicons dashicons-clock"></span> <strong><?php echo wpeo_util::convert_to_hours_minut( $list_task_worked_time[$task->id] ); ?></strong>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div> <!-- timeline-block-content -->
	</section>
</div> <!-- timeline-block -->



<div class="content-day">
	<?php 
	for ( $day = $current_day; $day > 0; $day-- ):
		$task_timeline->render_day( $user_id, $year, $month, $day, $list_task_created, $list_point_created, $list_point_completed, $list_comment );
	endfor;
	?>
</div>


<?php 
