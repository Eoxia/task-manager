<?php
/**
 * Les propriétés d'une tâche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.1
 * @version 1.5.1
 * @copyright 2015-2018 Eoxia
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><div class="tm_export_form_container" >
	<input type="hidden" name="action" value="export_task" />
	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'export_task' ) ); ?>" />
	<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
	<div>
		<label>
			<input type="radio" name="export_type" id="export_all_date" value="all" />
			<?php esc_html_e( 'Export all', 'task-manager' ); ?>
		</label>
		<label>
			<input type="radio" name="export_type" id="export_by_date" value="by_date" checked="checked" />
			<?php esc_html_e( 'Choose date', 'task-manager' ); ?>
		</label>
	</div>
	<div class="tm_export_date_container" >
		<label class="group-date" data-namespace="taskManager" data-module="taskExport" data-after-method="afterTriggerChangeDate">
			<?php esc_html_e( 'From date', 'task-manager' ); ?>
			<div class="date" ><span class="dashicons dashicons-calendar-alt alignleft"></span><p class="date-display alignleft" ><?php echo esc_attr( $from_date['date'] ); ?></p></div>
			<input type="text" class="mysql-date" name="date_from" value="<?php echo esc_attr( substr( $from_date['mysql'], 0, 10 ) ); ?>" />
		</label>
		<label class="group-date" data-namespace="taskManager" data-module="taskExport" data-after-method="afterTriggerChangeDate">
			<?php esc_html_e( 'To date', 'task-manager' ); ?>
			<div class="date" ><span class="dashicons dashicons-calendar-alt alignleft"></span><p class="date-display alignleft" ><?php echo esc_attr( $to_date['date'] ); ?></p></div>
			<input type="text" class="mysql-date" name="date_to" value="<?php echo esc_attr( substr( $to_date['mysql'], 0, 10 ) ); ?>" />
		</label>
	</div>
		<label>
			<input type="checkbox" name="include_comments" value="true" />
			<?php esc_html_e( 'Include comments', 'task-manager' ); ?>
		</label>
		<br/>
		<label>
			<input type="checkbox" name="display_id" value="true" />
			<?php esc_html_e( 'Display points/comments ID', 'task-manager' ); ?>
		</label>
	<button class="button button-primary alignright action-input" data-parent="tm_export_form_container" id="tm_export_task_button" ><?php esc_html_e( 'Export', 'task-manager' ); ?></button>
</div>
<div class="tm_export_result_container">
	<?php esc_html_e( 'Export content', 'task-manager' ); ?>
	<textarea readonly></textarea>
</div>
