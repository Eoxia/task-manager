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
<?php if( ! empty( $categories ) ) : ?>
<div class="tm-wrap wpeo-wrap tm_client_indicator_update_body">
	<table class="wpeo-table"> <?php // Recursive TASK ?>
  <thead>
    <tr>
			<th data-title="Category"><?php esc_html_e( 'Categories', 'task-manager' ); ?> (<?php esc_html_e( 'Recursive', 'task-manager' ); ?>)</th>
			<?php foreach( $everymonth as $key_month => $value_month ): ?>
      <th data-title="MonthName" style="cursor : pointer"><?= $value_month[ 'name_month' ] ?></th> <!-- . $value_month[ 'year' ] -->
		<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
		<?php foreach( $categories as $key_indicator => $value_indicator ): ?>
			<?php	if( $value_indicator[0][ 'is_recursive' ] ): ?>
				<tr>
					<td class="wpeo-tooltip-event"
					data-title="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_indicator[0][ 'all_month_elapsed_readable' ] . ' /' . $value_indicator[0][ 'all_month_estimated_readable' ] ?>"
					aria-label="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_indicator[0][ 'all_month_elapsed_readable' ] . ' /' . $value_indicator[0][ 'all_month_estimated_readable' ] ?>"
					style="background-color : <?= $value_indicator[0][ 'all_month_pourcent_color' ]?>">
						<b><i><?= $value_indicator[0][ 'name' ] ?></i></b><br>
						<?= $value_indicator[0][ 'all_month_elapsed' ]. ' /' . $value_indicator[0][ 'all_month_estimated' ] . ' (' . $value_indicator[0][ 'all_month_pourcent' ] . '%)' ?>
					</td>

					<?php foreach( $value_indicator as $key_indicator_month => $value_indicator_month ):?>
						<?php if( isset( $value_indicator_month[ "total_time_elapsed" ] ) && isset( $value_indicator_month[ "total_time_estimated" ] ) ) : ?>
							<?php if ( $value_indicator_month[ "total_time_elapsed" ] != 0 || $value_indicator_month[ "total_time_estimated" ] != 0 ) :?>

			      		<th class="wpeo-tooltip-event"
								<?php
								if( $value_indicator_month[ "total_time_elapsed" ] > 0 && $value_indicator_month[ "total_time_estimated" ] > 0 ) : ?>
								data-title="<?= $value_indicator_month[ 'total_time_elapsed_readable' ] . ' / ' . $value_indicator_month[ 'total_time_estimated_readable' ] ?>"
								aria-label="<?= $value_indicator_month[ 'total_time_elapsed_readable' ] . ' / ' . $value_indicator_month[ 'total_time_estimated_readable' ] ?>"
							<?php else: ?>
								data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
								aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
							<?php endif; ?>
								style="background-color : <?= $value_indicator_month[ 'purcent_color' ]?>; text-align : center">
								<span>
									<?= $value_indicator_month[ 'total_time_elapsed' ] . ' /' . $value_indicator_month[ 'total_time_estimated' ] ?>
								</span>
									<?php if( $value_indicator_month[ 'total_time_elapsed' ] > 0 && $value_indicator_month[ 'total_time_estimated' ] > 0 ) :
									 	echo ' (' .$value_indicator_month[ 'total_pourcent' ] . '%)';
										endif; ?>

							<?php else: ?>
								<th data-title="TimeElapsed"><?= '-' ?>
							<?php endif; ?>
							</th>
						<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
  </tbody>
</table>

<table class="wpeo-table"> <?php // Deadline TASK - - - - - - - - - - - - - - ?>
<thead>
	<tr>
		<th data-title="Category"><?php esc_html_e( 'Categories', 'task-manager' ); ?> (<?php esc_html_e( 'DeadLine', 'task-manager' ); ?>)</th>
		<?php foreach( $everymonth as $key_month => $value_month ): ?>
			<th data-title="MonthName" style="cursor : pointer"><?= $value_month[ 'name_month' ] ?></th> <!-- . $value_month[ 'year' ] -->
		<?php endforeach; ?>
	</tr>
</thead>
<tbody>
	<?php foreach( $categories as $key_indicator => $value_indicator ): ?>
		<?php	if( ! $value_indicator[0][ 'is_recursive' ] ):?>
			<tr>
				<td class="wpeo-tooltip-event"
				data-title="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_indicator[0][ 'all_month_deadline_readable' ] . ' /' . $value_indicator[0][ 'all_month_estimated_readable' ] ?>"
				aria-label="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_indicator[0][ 'all_month_deadline_readable' ] . ' /' . $value_indicator[0][ 'all_month_estimated_readable' ] ?>"
				style="background-color : <?= $value_indicator[0][ 'all_month_pourcent_color' ]?>">
					<b><i><?= $value_indicator[0][ 'name' ] ?></i></b><br>
					<?= $value_indicator[0][ 'all_month_deadline' ]. ' /' . $value_indicator[0][ 'all_month_estimated' ] . ' (' . $value_indicator[0][ 'all_month_pourcent' ] . '%)' ?>
				</td>

				<?php foreach( $value_indicator as $key_indicator_month => $value_indicator_month ): ?>
					<?php
					if( isset( $value_indicator_month[ "total_time_estimated" ] ) && isset( $value_indicator_month[ "total_time_deadline" ] ) ) : ?>
						<?php if ( $value_indicator_month[ "total_time_estimated" ] != 0 || $value_indicator_month[ "total_time_deadline" ] != 0 ) : ?>

							<th class="wpeo-tooltip-event"
							<?php if( $value_indicator_month[ "total_time_deadline" ] > 0 && $value_indicator_month[ "total_time_estimated" ] > 0 ) : ?>
							data-title="<?= $value_indicator_month[ 'total_time_deadline_readable' ] . ' / ' . $value_indicator_month[ 'total_time_estimated_readable' ] ?>"
							aria-label="<?= $value_indicator_month[ 'total_time_deadline_readable' ] . ' / ' . $value_indicator_month[ 'total_time_estimated_readable' ] ?>"
						<?php else: ?>
							data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
							aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
						<?php endif; ?>
							style="background-color : <?= $value_indicator_month[ 'purcent_color' ]?>; text-align : center" >
							<span>
								<?= $value_indicator_month[ 'total_time_deadline' ] . ' /' . $value_indicator_month[ 'total_time_estimated' ] ?>
							</span>
								<?php
									if( isset( $value_indicator_month[ 'total_pourcent_deadline' ] ) ) :
									echo ' (' .$value_indicator_month[ 'total_pourcent_deadline' ] . '%)';
									endif; ?>

						<?php else: ?>
							<th data-title="TimeElapsed"><?= '-' ?>
						<?php endif; ?>
						</th>


					<?php endif; ?>
			<?php endforeach; ?>
		</tr>

		<?php if( $value_indicator[ 0 ][ 'task_list' ] ) : ?>
			<?php foreach( $value_indicator[ 0 ][ 'task_list' ] as $key_list_task => $value_list_task ):?>
				<tr>
					<td class="wpeo-tooltip-event"
					data-title="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_list_task[ 'all_time_deadline_readable' ] . ' /' . $value_list_task[ 'all_time_estimated_readable' ] ?>"
					aria-label="<?= esc_html__( 'Total : ', 'task-manager' ) . $value_list_task[ 'all_time_deadline_readable' ] . ' /' . $value_list_task[ 'all_time_estimated_readable' ] ?>"
					style="background-color : <?= $value_list_task[ 'all_time_color' ] ?>">
						<b><i><?= ' - ' . $value_list_task[ 'task_title' ] ?></i></b><br>
						<?= $value_list_task[ 'all_time_deadline' ] . ' /' . $value_list_task[ 'all_time_estimated' ] . ' (' . $value_list_task[ 'all_time_percent' ] . '%)' ?>
					</td>

					<?php foreach( $value_indicator as $key_indicator_month => $value_indicator_month ):?>
						<?php if( isset( $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_elapsed" ] ) ) : ?>

							<th class="wpeo-tooltip-event"
								<?php if( $value_indicator_month[ 'task_list' ][ $key_list_task ][ "month_in_range" ] ): ?>
									data-title="<?= $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_deadline_readable" ] . ' / ' . $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_estimated_readable" ] ?>"
									aria-label="<?= $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_deadline_readable" ] . ' / ' . $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_estimated_readable" ] ?>"
									style="background-color:<?= ( isset( $value_indicator_month[ 'task_list' ][ $key_list_task ][ 'time_color' ] ) ) ? $value_indicator_month[ 'task_list' ][ $key_list_task ][ 'time_color' ]: ''; ?>;text-align : center">

									<span>
										<?= $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_deadline" ] . ' /' . $value_indicator_month[ 'task_list' ][ $key_list_task ][ "time_estimated" ] ?>

										<?= ( isset( $value_indicator_month[ 'task_list' ][ $key_list_task ][ 'time_percent' ] ) ) ? '(' . $value_indicator_month[ 'task_list' ][ $key_list_task ][ 'time_percent' ] . '%)': ''; ?>
									</span>

							<?php else: ?>
								<th data-title="TimeElapsed"><?= '-' ?>
								<?php endif; ?>
							</th>
						<?php endif; ?>


				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php endif;?>
	<?php endforeach; ?>


	<?php foreach( $categories as $key_indicator => $value_indicator ): ?>
		<?php	if( ! $value_indicator[0][ 'is_recursive' ] ) : ?>

		<?php endif; ?>
	<?php endforeach; ?>

</tbody>
</table>
</div>
<?php endif; ?>
