<?php
/**
 * Formulaire pour ajouter une deadline.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="history-time-new">
	<div class="wpeo-grid grid-2 wpeo-form">
		<input type="hidden" name="action" value="create_history_time" />
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
		<?php wp_nonce_field( 'create_history_time' ); ?>

		<div>
			<div class="form-element">
				<span class="form-label"><i class="dashicons dashicons-clock"></i> <?php esc_html_e( 'Estimated time (min)', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input name="estimated_time" class="form-field" value="60" type="text" />
				</label>
			</div>
		</div>

		<div>
			<div class="form-element group-date">
				<span class="form-label"><i class="dashicons dashicons-calendar-alt"></i> <?php esc_html_e( 'Due date', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="hidden" class="mysql-date" name="due_date" value="<?php echo esc_attr( $history_time_schema->data['due_date']['raw'] ); ?>" />
					<input class="date form-field" type="text" value="<?php echo esc_attr( $history_time_schema->data['due_date']['rendered']['date'] ); ?>" />
				</label>
			</div>

			<div class="form-element form-align-horizontal">
				<span class="form-label"><i class="fas fa-redo"></i> <?php esc_html_e( 'Recursion', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<div class="form-field-inline">
						<input type="radio" class="form-field" id="recursive" name="custom" value="recursive">
						<label for="recursive"><?php esc_html_e( 'Recursive time per month', 'task-manager' ); ?></label>
					</div>
					<div class="form-field-inline">
						<input type="radio" class="form-field" id="due_date" name="custom" value="due_date" checked>
						<label for="due_date"><?php esc_html_e( 'Define due date', 'task-manager' ); ?></label>
					</div>
				</label>
			</div>
		</div>

	</div>

	<div data-parent="history-time-new" class="action-input wpeo-button button-blue">
		<span><?php echo esc_html_e( 'Update dead line', 'task-manager' ); ?></span>
	</div>
</div>
