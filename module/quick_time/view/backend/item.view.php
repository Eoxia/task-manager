<?php
/**
 * Affichage d'un temps rapide.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<tr class="item">
	<?php wp_nonce_field( 'quick_time_add_comment' ); ?>
	<td class="task" data-title="<?php esc_html_e( 'Task ID', 'task-manager' ); ?>">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][task_id]" value="<?php echo esc_attr( $quicktime['displayed']['task']->data['id'] ); ?>" />
		<strong><?php echo esc_html( '#' . $quicktime['displayed']['task']->data['id'] . ' ' . $quicktime['displayed']['task']->data['title'] ); ?></strong>
	</td>
	<td class="point wpeo-tooltip-event" data-title="<?php esc_html_e( 'Point ID', 'task-manager' ); ?>" aria-label="<?php echo esc_attr( '#' . $quicktime['displayed']['point']->data['id'] . ' ' . $quicktime['displayed']['point']->data['content'] ); ?>">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][point_id]" value="<?php echo esc_attr( $quicktime['displayed']['point']->data['id'] ); ?>" />
		<?php echo esc_html( $quicktime['displayed']['point_fake_content'] ); ?>
	</td>
	<td class="content" data-title="<?php esc_html_e( 'Comment', 'task-manager' ); ?>">
		<textarea name="comments[<?php echo esc_attr( $i ); ?>][content]" rows="1"><?php echo $quicktime['content']; ?></textarea>
		<input type='hidden' name="comments[<?php echo esc_attr( $i ); ?>][content_old]" value="<?= $quicktime['content']; ?>"/>
 	</td>
	<td class="min" data-title="<?php esc_html_e( 'min.', 'task-manager' ); ?>">
		<div>
			<i class="fas fa-clock" aria-hidden="true"></i>
			<input type="hidden" class="time" name="comments[<?php echo esc_attr( $i ); ?>][time]" />
			<input type="text" class="displayed quick-time-edit-time" style='min-width : 45px' placeholder=''/>
		</div>
	</td>
	<td class="action"><input type="checkbox" class="set_time" name="comments[<?php echo esc_attr( $i ); ?>][can_add]" /></td>
	<td>
		<input type='text' class="tm_quicktime_focus_url" value='<?= admin_url() . 'admin.php?page=wpeomtm-dashboard&quicktimemode=' . esc_attr( $key + 1 ); ?>' />
		<div class="wpeo-button button-progress button-yellow tm_quicktime_buttoncopytoclipboard wpeo-tooltip-event" id="tm_quicktime_copytoclipboard"
			aria-label="<?php esc_html_e( 'Copy to clipboard', 'task-manager' ); ?>"
			data-path="<?= admin_url() . 'admin.php?page=wpeomtm-dashboard&quicktimemode=' . esc_attr( $key + 1 ); ?>"
			data-key="<?php echo esc_attr( $key ); ?>">
			<span class="button-icon fa fa-copy" aria-hidden="true"></span>
		</div>
	</td>
	<td class="actions">
		<?php if( ! $editline ) : ?>
			<div class=" wpeo-button button-progress button-red action-delete"
				data-action="remove_config_quick_time"
				data-message-delete="<?php echo esc_attr_e( 'Delete this preset ?', 'task-manager' ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'remove_config_quick_time' ) ); ?>"
				data-key="<?php echo esc_attr( $key ); ?>"
				data-task-id="<?php echo esc_attr( $quicktime['displayed']['task']->data['id'] ); ?>"
				data-point-id="<?php echo esc_attr( $quicktime['displayed']['point']->data['id'] ); ?>">
				<span class="button-icon fa fa-times" aria-hidden="true"></span>
			</div>

		<?php else : ?> <!-- data-action="edit_config_quick_time" -->
			<div class="wpeo-button button-progress action-input"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_config_quick_time' ) ); ?>"
				data-key="<?php echo esc_attr( $key ); ?>"
				data-task-id="<?php echo esc_attr( $quicktime['displayed']['task']->data['id'] ); ?>"
				data-point-id="<?php echo esc_attr( $quicktime['displayed']['point']->data['id'] ); ?>">
				<span class="button-icon fa fa-refresh" aria-hidden="true"></span>
			</div>

		<?php endif; ?>
	</td>
</tr>
