<?php
/**
 * La vue principale du tableau des temps dépassés.
 *
 * @author Eoxia <Eoxia>
 * @since 1.0.0
 * @version 1.6.1
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="filter-exceeded-time">
	<input type="hidden" name="action" value="load_time_exceeded" />
	<?php wp_nonce_field( 'load_time_exceeded' ); ?>

	<ul>
		<li>
			<label>
				<i class="fa fa-calendar"></i><?php esc_html_e( 'Start date', 'task-manager' ); ?>
				<input type="date" value="<?php echo esc_attr( $start_date ); ?>" name="start_date" />
			</label>
		</li>
		<li>
			<label>
				<i class="fa fa-calendar"></i><?php esc_html_e( 'End date', 'task-manager' ); ?>
				<input type="date" value="<?php echo esc_attr( $end_date ); ?>" name="end_date" />
			</label>
		</li>
		<li>
			<label for="require_time_history">
				<input type="radio" id="require_time_history" name="tm_filter_exceed_type" value="history_time" <?php checked( 'history_time', $filter_type, true ); ?>/>
				<?php esc_html_e( 'Tasks with an exceeded forecast', 'task-manager' ); ?>
			</label>
		</li>
		<li>
			<label for="min_exceeded_time">
				<input type="radio" id="min_exceeded_time" name="tm_filter_exceed_type" value="custom_time" <?php checked( 'custom_time', $filter_type, true ); ?>/>
				<?php esc_html_e( 'Tasks without forecast over time beyond', 'task-manager' ); ?>
			</label>
			<input type="text" name="min_exceeded_time" value="<?php echo esc_attr( $min_exceeded_time ); ?>" />
			<?php esc_html_e( 'minutes', 'task-manager' ); ?>
		</li>
		<li>
			<button class="button-primary action-input" data-parent="filter-exceeded-time" ><?php echo esc_html_e( 'Display exceeded tasks', 'task-manager' ); ?></button>
		</li>
	</ul>

</div>
<table class="tm-indicator-time-exceed no-result" >
	<tbody>
		<tr><td><?php esc_html_e( 'Please use form above in order to view task where time is exceeded', 'task-manager' ); ?></td></tr>
	</tbody>
</table>
