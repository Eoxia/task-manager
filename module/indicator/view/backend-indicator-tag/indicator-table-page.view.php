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

<?php // echo '<pre>'; print_r( $type ); echo '</pre>';exit; ?>
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
					<tr class="tm_client_indicator" style="cursor : pointer" data-id="<?php echo esc_html( $key_indicator ); ?>" data-show="false" data-type="<?php echo esc_html( $key_categorie ); ?>" >

						<?php
						\eoxia\View_Util::exec(
							'task-manager',
							'indicator',
							'backend-indicator-tag/table/client-title',
							array(
									'time_elapsed_readable'   => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ],
									'time_estimated_readable' => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ],
									'key_indicator'           => $key_indicator,
									'name'                    => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'name' ],
									'time_percent'            => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent' ],
									'time_estimated'          => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated' ],
									'time_elapsed'            => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed' ]
								)
						); ?>

						<?php foreach( $value_categorie as $key_month => $value_month ):?>
							<?php
							\eoxia\View_Util::exec(
								'task-manager',
								'indicator',
								'backend-indicator-tag/table/client-row',
								array(
										'time_elapsed'           => $value_month[ "total_time_elapsed" ],
										'time_estimated'         => $value_month[ "total_time_estimated" ],
										'time_elapsed_readable'  => $value_month[ "total_time_elapsed_readable" ],
										'time_estimated_readable'  => $value_month[ "total_time_estimated_readable" ],
										'time_percent'           => $value_month[ "total_time_percent" ]
									)
							); ?>
						<?php endforeach; ?>
				</tr>
				<?php if( ! empty( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] ) ):
					foreach( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] as $key_task => $value_task ):
						?>
						<tr class="tm_client_indicator_<?php echo esc_html( $key_indicator ) ?>_<?php echo esc_html( $key_categorie ) ?> tm-simple-task">
							<td class="wpeo-tooltip-event"
							data-title="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
							<?php echo esc_html( $value_task[ 'time_elapsed_readable' ] . '/' . $value_task[ 'time_estimated_readable' ] ) ?>"
							aria-label="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
							<?php echo esc_html( $value_task[ 'time_elapsed_readable' ] . '/' . $value_task[ 'time_estimated_readable' ] ) ?>">

								<p class="tag-title">
									<a style="color: inherit; text-decoration: none;"	target="_blank" href="<?php echo admin_url( 'admin.php?page=wpeomtm-dashboard&task_id=' . $key_task ); ?>">
										<strong>- <?php echo esc_html( $value_task[ 'title' ] . ' (#' . $key_task . ')' ) ?></strong>
									</a>
								</p>

								<p class="tag-time <?php echo esc_html( $value_task[ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
									<?php echo esc_html( $value_task[ 'time_elapsed' ] . '/' . $value_task[ 'time_estimated' ] ) ?>
									<?php echo esc_html( '(' . $value_task[ 'time_percent' ] . '%)' ); ?>
								</p>
							</td>

							<?php foreach( $value_categorie as $key_month => $value_month ):
										//	if( $value_month[ 'month_is_valid' ] ) : ?>
										<?php
										\eoxia\View_Util::exec(
											'task-manager',
											'indicator',
											'backend-indicator-tag/table/task-row',
											array(
													'time_elapsed'           => $value_month[ "total_time_elapsed" ],
													'time_estimated'         => $value_month[ "total_time_estimated" ],
													'time_elapsed_readable'  => $value_month[ 'task_list' ][ $key_task ][ "time_elapsed_readable" ],
													'time_estimated_readable'  => $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ],
													'task_time_elapsed'      => $value_month[ 'task_list' ][ $key_task ][ "time_elapsed" ],
													'task_time_estimated'    => $value_month[ 'task_list' ][ $key_task ][ "time_estimated" ],
													'task_percent'           => $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ]
												)
										); ?>

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
				<tr class="tm_client_indicator" style="cursor : pointer" data-id="<?php echo esc_html( $key_indicator ); ?>" data-show="false" data-type="<?php echo esc_html( $key_categorie ); ?>">
					<?php

					\eoxia\View_Util::exec(
						'task-manager',
						'indicator',
						'backend-indicator-tag/table/client-title',
						array(
								'time_estimated_readable' => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated_readable' ],
								'time_elapsed_readable'   => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed_readable' ],
								'key_indicator'           => $key_indicator,
								'name'                    => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'name' ],
								'time_elapsed'            =>  $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_elapsed' ],
								'time_estimated'          => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_estimated' ],
								'time_percent'            => $info[ $key_categorie ][ $key_indicator ][ 'info' ][ 'time_percent' ]
							)
					); ?>

					<?php foreach( $value_categorie as $key_month => $value_month ):?>
						<?php
						\eoxia\View_Util::exec(
							'task-manager',
							'indicator',
							'backend-indicator-tag/table/client-row',
							array(
									'time_elapsed'            => $value_month[ "total_time_elapsed" ],
									'time_estimated'          => $value_month[ "total_time_estimated" ],
									'time_percent'            => $value_month[ "total_time_percent" ],
									'time_elapsed_readable'   => $value_month[ "total_time_elapsed_readable" ],
									'time_estimated_readable' => $value_month[ "total_time_estimated_readable" ]
								)
						); ?>
				<?php endforeach; ?>
			</tr>

			<?php if( ! empty( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] ) ):
				foreach( $info[ $key_categorie ][ $key_indicator ][ 'task_list' ] as $key_task => $value_task ):
					?>
					<tr class="tm_client_indicator_<?php echo esc_html( $key_indicator ) ?>_<?php echo esc_html( $key_categorie ) ?> tm-simple-task">
						<td class="wpeo-tooltip-event"
			      data-title="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
						<?php echo esc_html( $value_task[ 'time_elapsed_readable' ] . '/' . $value_task[ 'time_estimated_readable' ] ) ?>"
			      aria-label="<?= esc_html__( 'Total : ', 'task-manager' ); ?>
			      <?php echo esc_html( $value_task[ 'time_elapsed_readable' ] . '/' . $value_task[ 'time_estimated_readable' ] ) ?>">
						<p class="tag-title">
							<a style="color: inherit; text-decoration: none;"	target="_blank" href="<?php echo admin_url( 'admin.php?page=wpeomtm-dashboard&task_id=' . $key_task ); ?>">
								<strong>- <?php echo esc_html( $value_task[ 'title' ] . ' (#' . $key_task . ')' ) ?></strong>
							</a>
						</p>

						<p class="tag-time <?php echo esc_html( $value_task[ 'time_percent' ] > 100 ? 'time-excedeed' : '' ); ?>">
							<?php echo esc_html( $value_task[ 'time_elapsed' ] ) ?>/
							<?php echo esc_html( $value_task[ 'time_estimated' ] ) ?>
							<?php echo esc_html( '(' . $value_task[ 'time_percent' ] . '%)' ); ?></p>
					</td>


						<?php foreach( $value_categorie as $key_month => $value_month ):?>
							<?php
							\eoxia\View_Util::exec(
								'task-manager',
								'indicator',
								'backend-indicator-tag/table/task-row',
								array(
										'time_elapsed'            => $value_month[ "total_time_elapsed" ],
										'time_estimated'          => $value_month[ "total_time_estimated" ],
										'time_deadline'           => $value_month[ "total_time_deadline" ],
										'time_elapsed_readable'   => $value_month[ 'task_list' ][ $key_task ][ "time_elapsed_readable" ],
										'time_deadline_readable'  => $value_month[ 'task_list' ][ $key_task ][ "time_deadline_readable" ],
										'time_estimated_readable' => $value_month[ 'task_list' ][ $key_task ][ "time_estimated_readable" ],
										'task_time_elapsed'       => $value_month[ 'task_list' ][ $key_task ][ "time_elapsed" ],
										'task_time_estimated'     => $value_month[ 'task_list' ][ $key_task ][ "time_estimated" ],
										'task_time_deadline'      => $value_month[ 'task_list' ][ $key_task ][ "time_deadline" ],
										'task_percent'            => $value_month[ 'task_list' ][ $key_task ][ 'time_percent' ]
									)
							); ?>
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
