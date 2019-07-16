<?php
/**
 * Options dans le profil utilisateur.
 *
 * @since 1.8.0
 * @version 1.8.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-table table-flex table-4 tm-table-planning" style="margin: 10px">
  <div class="table-row table-header">
		<?php foreach( $days as $day ): ?>
			<div class="table-cell" data-title="<?php echo esc_attr( strtolower( $day[ 'day_name' ] ) ); ?>" style="text-align : center">
				<?php echo esc_attr( $day[ 'day_name' ] ); ?>
				<div class="wpeo-tooltip-event tm-minute-per-day" aria-label="<?php echo esc_attr( $day[ 'readable' ] ); ?>" style="text-align : center">
					<span class"tm-minute-per-day"><?php echo esc_attr( $day[ 'duration'] ); ?></span>
					<?php esc_html_e( 'min', 'task-manager' ); ?>
				</div>
			</div>
		<?php endforeach; ?>
  </div>
  <?php	if( isset( $planning ) && ! empty( $planning ) ): ?>
		<?php foreach( $periods as $period ): ?>
			<div class="table-row">
				<div class="table-cell" style="text-align: center;">
					<span><?php echo Follower_Class::g()->tradThisPeriod( $period ); ?></span>
				</div>
	    	<?php foreach( $planning as $key_d => $day ):?>
			      <div class="table-cell" data-day="<?php echo esc_attr( $key_d ); ?>" style="text-align: center;">
							<?php if( $edit ): ?>
								<input type="time" class="tm-contract-planning-dynamic-update tm-contract-planning-from" data-work="from"
								name="planning[<?php echo esc_attr( $key_d ); ?>][<?php echo esc_attr( $period ); ?>][<?php echo esc_attr( 'work_from' ); ?>]"
								value="<?php echo esc_attr( $day[ $period ][ 'work_from' ] ); ?>">
								<input type="time" class="tm-contract-planning-dynamic-update" data-work="to"
								name="planning[<?php echo esc_attr( $key_d ); ?>][<?php echo esc_attr( $period ); ?>][<?php echo esc_attr( 'work_to' ); ?>]"
								value="<?php echo esc_attr( $day[ $period ][ 'work_to' ] ); ?>">
							<?php else: ?>
								<span><?php echo esc_attr( $day[ $period ][ 'work_from' ] ); ?></span> -
								<span><?php echo esc_attr( $day[ $period ][ 'work_to' ] ); ?></span>
							<?php endif; ?>
			      </div>
				<?php endforeach; ?>
			</div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
