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

<div class="wpeo-table table-flex table-4 tm-table-planning">
  <div class="table-row table-header">
		<div class="table-cell" data-title="Period"><?php esc_html_e( 'Period', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Monday"><?php esc_html_e( 'Monday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Tuesday"><?php esc_html_e( 'Tuesday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Wednesday"><?php esc_html_e( 'Wednesday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Thursday"><?php esc_html_e( 'Thursday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Friday"><?php esc_html_e( 'Friday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Saturday"><?php esc_html_e( 'Saturday', 'task-manager' ); ?></div>
    <div class="table-cell" data-title="Sunday"><?php esc_html_e( 'Sunday', 'task-manager' ); ?></div>
  </div>
  <?php	if( isset( $planning ) && ! empty( $planning ) ): ?>
		<?php foreach( $periods as $period ): ?>
			<div class="table-row">
				<div class="table-cell">
					<span><?php echo Follower_Class::g()->tradThisPeriod( $period ); ?></span>
				</div>
	    	<?php foreach( $planning as $key_d => $day ):?>
			      <div class="table-cell">
							<?php if( $edit ): ?>
								<input type="time"
								name="planning[<?php echo esc_attr( $key_d ); ?>][<?php echo esc_attr( $period ); ?>][<?php echo esc_attr( 'work_from' ); ?>]"
								value="<?php echo esc_attr( $day[ $period ]['work_from'] ); ?>">
								<input type="time"
								name="planning[<?php echo esc_attr( $key_d ); ?>][<?php echo esc_attr( $period ); ?>][<?php echo esc_attr( 'work_to' ); ?>]"
								value="<?php echo esc_attr( $day[ $period ][ 'work_to' ] ); ?>">
							<?php else: ?>
								<span><?php echo esc_attr( $day[ $period ]['work_from'] ); ?></span> -
								<span><?php echo esc_attr( $day[ $period ][ 'work_to' ] ); ?></span>
							<?php endif; ?>
			      </div>
				<?php endforeach; ?>
			</div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
