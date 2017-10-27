<?php
/**
 * La vue principale du tableau des temps dépassés.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpeo-project-wrap">
	<h2><?php esc_html_e( 'Task Manager: Time Exceeded', 'task-manager' ); ?></h2>

	<div class="form">
		<input type="hidden" name="action" value="load_time_exceeded" />
		<?php wp_nonce_field( 'load_time_exceeded' ); ?>

		<label for="start_date"><?php esc_html_e( 'Start date', 'task-manager' ); ?></label>
		<input type="date" id="start_date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>" />

		<label for="end_date"><?php esc_html_e( 'End date', 'task-manager' ); ?></label>
		<input type="date" id="end_date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" />

		<label for="max_exceeded_time"><?php esc_html_e( 'Max exceeded time', 'task-manager' ); ?></label>
		<input type="text" id="max_exceeded_time" name="max_exceeded_time" value="<?php echo esc_attr( $max_exceeded_time ); ?>" />
		<input type="button" class="action-input" data-parent="form" value="<?php echo esc_html_e( 'Filter', 'task-manager' ); ?>" />
	</div>

	<table style="width: 100%;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'ID', 'task-manager' ); ?></th>
				<th><?php esc_html_e( 'Title', 'task-manager' ); ?></th>
				<th><?php esc_html_e( 'Parent task', 'task-manager' ); ?></th>
				<th><?php esc_html_e( 'Time', 'task-manager' ); ?></th>
				<th><?php esc_html_e( 'Time exceeded', 'task-manager' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php Time_Exceeded_Class::g()->display( $start_date, $end_date, $max_exceeded_time ); ?>
		</tbody>
	</table>
</div>
