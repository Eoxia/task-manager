<?php
/**
 * Options dans le profil utilisateur.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h2><?php esc_html_e( 'Task Manager settings', 'task-manager' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="_tm_task_per_page"><?php esc_html_e( 'Task Per Page', 'task-manager' ); ?></label></th>
			<td>
				<input type="text" name="_tm_task_per_page" id="_tm_task_per_page" value="<?php echo isset( $user->data['_tm_task_per_page'] ) ? $user->data['_tm_task_per_page'] : 10; ?>">
				<p class="description" ><?php esc_html_e( 'Set the number of task loaded by time', 'task-manager' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_tm_project_state"><?php esc_html_e( 'Display Project and Task', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_project_state" id="_tm_project_state" value="1" <?php checked( $user->data['_tm_project_state'], true, true ); ?>">
				<p class="description" ><?php esc_html_e( 'Display project and task for quickly access at task in project', 'task-manager' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_tm_auto_elapsed_time"><?php esc_html_e( 'Compil time automatically', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_auto_elapsed_time" id="_tm_auto_elapsed_time" value="1" <?php checked( $user->data['_tm_auto_elapsed_time'], true, true ); ?>>
				<p class="description" ><?php esc_html_e( 'Get the time of last comment you enter and fill elapsed time from this time. (You don\'t need to make hard calcul to get your elapsed time ;) ', 'task-manager' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_tm_advanced_display"><?php esc_html_e( 'Advanced display', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_advanced_display" id="_tm_advanced_display" value="1" <?php checked( $user->data['_tm_advanced_display'], true, true ); ?>>
				<p class="description" ><?php esc_html_e( 'Display advanced: time, task informations, task link)', 'task-manager' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_tm_quick_point"><?php esc_html_e( 'Quick point', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_quick_point" id="_tm_quick_point" value="1" <?php checked( $user->data['_tm_quick_point'], true, true ); ?>>
				<p class="description" ><?php esc_html_e( 'Display quick point button', 'task-manager' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="_tm_display_indicator"><?php esc_html_e( 'Display indicator', 'task-manager' ); ?></label></th>
			<td>
				<input type="checkbox" name="_tm_display_indicator" id="_tm_display_indicator" value="1" <?php checked( $user->data['_tm_display_indicator'], true, true ); ?>>
				<p class="description" ><?php esc_html_e( 'Display indicator box', 'task-manager' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>
