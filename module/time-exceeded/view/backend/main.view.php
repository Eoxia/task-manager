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

	<label>
		<i class="fa fa-calendar"></i><?php esc_html_e( 'Start date', 'task-manager' ); ?>
		<input type="date" value="<?php echo esc_attr( $start_date ); ?>" name="start_date" />
	</label>

	<label>
		<i class="fa fa-calendar"></i><?php esc_html_e( 'End date', 'task-manager' ); ?>
		<input type="date" value="<?php echo esc_attr( $end_date ); ?>" name="end_date" />
	</label>

	<label for="min_exceeded_time"><?php esc_html_e( 'Display task with time over', 'task-manager' ); ?></label>
	<input type="text" id="min_exceeded_time" name="min_exceeded_time" value="<?php echo esc_attr( $min_exceeded_time ); ?>" />

	<label for="require_time_history"><?php esc_html_e( 'Require time history', 'task-manager' ); ?></label>
	<input type="checkbox" id="require_time_history" name="require_time_history" />

	<button class="button-primary action-input" data-parent="filter-exceeded-time" ><?php echo esc_html_e( 'Display exceeded tasks', 'task-manager' ); ?></button>
</div>
<table style="width: 100%;">
	<tbody>
		<tr><td><?php esc_html_e( 'Please use form above in order to view task where time is exceeded', 'task-manager' ); ?></td></tr>
	</tbody>
</table>
