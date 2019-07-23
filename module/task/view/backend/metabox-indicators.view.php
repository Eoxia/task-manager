<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<?php if( ! empty( $type ) ) : ?>
<div class="tm-wrap wpeo-wrap tm_client_indicator_update_body">

	<h3><i class="fas fa-redo-alt"></i> <?php esc_html_e( 'Recursive', 'task-manager' ); ?></h3>

	<table class="wpeo-table"> <?php // Recursive TASK ?>
  <thead>
    <tr>
			<?php if( isset( $type_stats ) && $type_stats != "" ): ?>
				<th data-title="<?= $type_stats ?>"><?php echo esc_attr( $type_stats ); ?></th>
			<?php else: ?>
				<th data-title="Category"><?php esc_html_e( 'Categories', 'task-manager' ); ?></th>
			<?php endif; ?>
			<?php foreach( $everymonth as $key_month => $value_month ): ?>
      <th data-title="MonthName" style="cursor : pointer"><?= $value_month[ 'name_month' ] ?></th> <!-- . $value_month[ 'year' ] -->
		<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
		<?php foreach( $type as $key_categorie => $categories ): ?>
			<?php foreach( $categories as $key_indicator => $value_categorie ):?>
				<?php	if( $key_categorie == 'recursive' ): ?>
					<tr class="tm_client_indicator" style="cursor : pointer" data-id="<?php echo esc_html( $key_indicator ); ?>" data-show="true" data-type="<?php echo esc_html( $key_categorie ); ?>" >
						<td class="wpeo-tooltip-event"
						data-title="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ] . ' /' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ] ?>"
						aria-label="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ] . ' /' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ] ?>"
							<p class="tag-title"><strong><?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'name' ] ) ?></strong></p>
							<p class="tag-time <?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
								<?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed' ] . '/' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated' ] . ' (' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent'] . '%)' ); ?></p>
						</td>

						<?php foreach( $value_categorie as $key_month => $value_month ): ?>
							<?php if( $value_month[ 'month_is_valid' ] ) : ?>

			      		<td class="wpeo-tooltip-event"
								<?php
								if( $value_month[ "total_time_elapsed" ] > 0 && $value_month[ "total_time_estimated" ] > 0 ) : ?>
									data-title="<?php echo esc_html( $value_month[ 'total_time_elapsed_readable' ] . ' / ' . $value_month[ 'total_time_estimated_readable' ] ) ?>"
									aria-label="<?php echo esc_html( $value_month[ 'total_time_elapsed_readable' ] . ' / ' . $value_month[ 'total_time_estimated_readable' ] ) ?>"
								<?php else: ?>
									data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
									aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
								<?php endif; ?>
								>

									<p class="tag-time <?php echo esc_html( $value_month[ 'total_time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
										<?php echo esc_html( $value_month[ 'total_time_elapsed' ] . ' /' . $value_month[ 'total_time_estimated' ] ); ?>
										<?php echo esc_html( $value_month[ 'total_time_percent' ] > 0 ? '(' . $value_month[ 'total_time_percent' ] . '%)' : '' ); ?>
									</p>
								</td>
							<?php else: ?>
								<td data-title="TimeElapsed">
									<p class="tag-time"><?= '-' ?></p>
								</td>
							<?php endif; ?>
					<?php endforeach; ?>
				</tr>
				<?php if( ! empty( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] ) ):
					foreach( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] as $key_task => $value_task ):
						?>
						<tr class="tm_client_indicator_<?php echo esc_html( $key_indicator ) ?>_<?php echo esc_html( $key_categorie ) ?> tm-simple-task">
							<td class="wpeo-tooltip-event"
							data-title="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
							<?php echo esc_html( $value_task[ 'time_elapsed_readable' ] ) ?>/
							<?php echo esc_html( $value_task[ 'time_estimated_readable' ] ) ?>"
							aria-label="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
							<?php echo esc_html( $value_task[ 'time_elapsed_readable' ] ) ?>/
							<?php echo esc_html( $value_task[ 'time_estimated_readable' ] ) ?>">

								<p class="tag-title"><strong>- <?php echo esc_html( $value_task[ 'title' ] . ' (#' . $key_task . ')' ) ?></strong></p>

								<p class="tag-time <?php echo esc_html( $value_task[ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
									<?php echo esc_html( $value_task[ 'time_elapsed' ] ) ?>/
									<?php echo esc_html( $value_task[ 'time_estimated' ] ) ?>
									<?php echo esc_html( '(' . $value_task[ 'time_percent' ] . '%)' ); ?></p>
							</td>

							<?php foreach( $value_categorie as $key_month => $value_month ):
											if( $value_month[ 'month_is_valid' ] ) : ?>
									<?php if( $value_month[ 'task_list' ][ $key_task ][ 'month_is_valid' ] ): ?>
									<td class="wpeo-tooltip-event"
										<?php if( $value_month[ "total_time_elapsed" ] > 0 && $value_month[ "total_time_estimated" ] > 0 ) : ?>
											data-title="<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_elapsed_readable" ] . ' / ' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ] )?>"
											aria-label="<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_elapsed_readable" ] . ' / ' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ] )?>"
										<?php else: ?>
											data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
											aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
										<?php endif; ?>>


											<p class="tag-time <?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
												<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_elapsed" ] . ' /' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated" ] ) ?>
												<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] > 0 ? '(' . $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] . '%)' : '' ); ?>
											</p>

									<?php else: ?>
										<td data-title="TimeElapsed">
											<p class="tag-time"><?= '-' ?></p>
									<?php endif; ?>

									</td>
								<?php else: ?>
									<td data-title="TimeElapsed">
										<p class="tag-time"><?= '-' ?></p>
									</td>
								<?php endif ; ?>


							<?php endforeach; ?>

						</tr>
						<?php
					 endforeach;
					endif;?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>

  </tbody>
</table>

<h3><i class="fas fa-clock"></i> <?php esc_html_e( 'DeadLine', 'task-manager' ); ?></h3>

<table class="wpeo-table"> <?php // Deadline TASK - - - - - - - - - - - - - - ?>
<thead>
	<tr>
		<?php if( isset( $type_stats ) && $type_stats != "" ): ?>
			<th data-title="<?= $type_stats ?>"><?php echo esc_attr( $type_stats ); ?></th>
		<?php else: ?>
			<th data-title="Category"><?php esc_html_e( 'Categories', 'task-manager' ); ?></th>
		<?php endif; ?>
		<?php foreach( $everymonth as $key_month => $value_month ): ?>
			<th data-title="MonthName" style="cursor : pointer"><?= $value_month[ 'name_month' ] ?></th> <!-- . $value_month[ 'year' ] -->
		<?php endforeach; ?>
	</tr>
</thead>
<tbody>
	<?php foreach( $type as $key_categorie => $categories ): ?>
		<?php foreach( $categories as $key_indicator => $value_categorie ): ?>
			<?php	if( $key_categorie == 'deadline' ): ?>
				<tr class="tm_client_indicator" style="cursor : pointer" data-id="<?php echo esc_html( $key_indicator ); ?>" data-show="true" data-type="<?php echo esc_html( $key_categorie ); ?>">
					<td class="wpeo-tooltip-event"
					data-title="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ] . ' /' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ] ?>"
					aria-label="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ] . ' /' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ] ?>"
						<p class="tag-title"><strong><?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'name' ] ) ?></strong></p>
						<p class="tag-time <?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
							<?php echo esc_html( $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed' ] . '/' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated' ] . ' (' . $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent'] . '%)' ); ?></p>
					</td>


					<?php foreach( $value_categorie as $key_month => $value_month ):?>
						<?php if( $value_month[ 'month_is_valid' ] ) : ?>

							<td class="wpeo-tooltip-event"
							<?php
							if( $value_month[ "total_time_deadline" ] > 0 && $value_month[ "total_time_estimated" ] > 0  ) : ?>
								data-title="<?php echo esc_html( $value_month[ 'total_time_deadline_readable' ] . ' / ' . $value_month[ 'total_time_estimated_readable' ] ) ?>"
								aria-label="<?php echo esc_html( $value_month[ 'total_time_deadline_readable' ] . ' / ' . $value_month[ 'total_time_estimated_readable' ] ) ?>"
							<?php else: ?>
								data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
								aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
							<?php endif; ?>
							>
							<p class="tag-time <?php echo esc_html( $value_month[ 'total_time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
								<?php echo esc_html( $value_month[ 'total_time_deadline' ] . ' /' . $value_month[ 'total_time_estimated' ] ); ?>
								<?php echo esc_html( $value_month[ 'total_time_percent' ] > 0 ? '(' . $value_month[ 'total_time_percent' ] . '%)' : '' ); ?>
							</p>
						</td>
						<?php else: ?>
						<td data-title="TimeElapsed">
							<p class="tag-time"><?= '-' ?></p>
						</td>
						<?php endif; ?>
				<?php endforeach; ?>
			</tr>

			<?php if( ! empty( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] ) ):
				foreach( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] as $key_task => $value_task ):
					?>
					<tr class="tm_client_indicator_<?php echo esc_html( $key_indicator ) ?>_<?php echo esc_html( $key_categorie ) ?>">
						<td class="wpeo-tooltip-event"
			      data-title="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
			      <?php echo esc_html( $value_task[ 'time_elapsed_readable' ] ) ?>/
			      <?php echo esc_html( $value_task[ 'time_estimated_readable' ] ) ?>"
			      aria-label="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
			      <?php echo esc_html( $value_task[ 'time_elapsed_readable' ] ) ?>/
			      <?php echo esc_html( $value_task[ 'time_estimated_readable' ] ) ?>">
						<p class="tag-title"><strong>- <?php echo esc_html( $value_task[ 'title' ] . ' (#' . $key_task . ')' ) ?></strong></p>

						<p class="tag-time <?php echo esc_html( $value_task[ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
							<?php echo esc_html( $value_task[ 'time_elapsed' ] ) ?>/
							<?php echo esc_html( $value_task[ 'time_estimated' ] ) ?>
							<?php echo esc_html( '(' . $value_task[ 'time_percent' ] . '%)' ); ?></p>
					</td>


						<?php foreach( $value_categorie as $key_month => $value_month ):
										if( $value_month[ 'month_is_valid' ] ) : ?>
										<?php if( $value_month[ 'task_list' ][ $key_task ][ 'month_is_valid' ] ): ?>
								<td class="wpeo-tooltip-event"
								<?php if( $value_month[ "total_time_deadline" ] > 0 && $value_month[ "total_time_estimated" ] > 0 ) : ?>
									data-title="<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_deadline_readable" ] . ' / ' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ] )?>"
									aria-label="<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_deadline_readable" ] . ' / ' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ] )?>"
								<?php else: ?>
									data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
									aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
								<?php endif; ?>>

								<p class="tag-time <?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
									<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_deadline" ] . ' /' . $value_month[ 'task_list' ][ $key_task ][ "time_estimated" ] ) ?>
									<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] > 0 ? '(' . $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ] . '%)' : '' ); ?>
								</p>

									<?php else: ?>
										<td data-title="TimeElapsed">
											<p class="tag-time"><?= '-' ?></p>
									<?php endif; ?>

								</td>
							<?php else: ?>
								<?php if( $value_month[ 'task_list' ][ $key_task ][ "time_elapsed" ]  ):?>
									<td class="wpeo-tooltip-event"
										data-title="<?php echo esc_html_e( 'Time out of deadline', 'task-manager' ) ?>"
										aria-label="<?php echo esc_html_e( 'Time out of deadline', 'task-manager' ) ?>" style="color : orange">
										<p class="tag-time">
											<?php echo esc_html( $value_month[ 'task_list' ][ $key_task ][ "time_deadline" ] ); ?><?php echo esc_html_e( 'min (out)', 'task-manager' ) ?>
										</p>
									</td>
								<?php else: ?>
									<td data-title="TimeElapsed">
										<p class="tag-time"><?= '-' ?></p>
									</td>
								<?php endif; ?>
							<?php endif ; ?>
						<?php endforeach; ?>

					</tr>
					<?php
				 endforeach;
				endif;?>
			<?php endif;?>
		<?php endforeach; ?>
	<?php endforeach; ?>

</tbody>
</table>
</div>
<?php else: ?>
	<div class="wpeo-notice notice-warning">
		<div class="notice-content">
			<div class="notice-title"><?php esc_html_e( 'No data found !', 'task-manager' ); ?></div>
			<div class="notice-subtitle"><?php esc_html_e( 'Be sure you havn\'t made any mistakes in your search', 'task-manager' ); ?></div>
		</div>
		<div class="notice-close"><i class="fas fa-times"></i></div>
	</div>
<?php endif; ?>
