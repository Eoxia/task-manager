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
	</td>
	<td class="min" data-title="<?php esc_html_e( 'min.', 'task-manager' ); ?>">
		<div>
			<i class="far fa-clock" aria-hidden="true"></i>
			<input type="hidden" class="time" name="comments[<?php echo esc_attr( $i ); ?>][time]" />
			<input type="text" class="displayed quick-time-edit-time" />
		</div>
	</td>
	<td class="action"><input type="checkbox" class="set_time" name="comments[<?php echo esc_attr( $i ); ?>][can_add]" /></td>
	<td class="actions">
		<?php if( ! $editline ) : ?>
			<div class="action-delete wpeo-button button-progress button-grey button-square-20 button-rounded"
				data-action="remove_config_quick_time"
				data-message-delete="<?php echo esc_attr_e( 'Delete this preset ?', 'task-manager' ); ?>"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'remove_config_quick_time' ) ); ?>"
				data-key="<?php echo esc_attr( $key ); ?>"
				data-task-id="<?php echo esc_attr( $quicktime['displayed']['task']->data['id'] ); ?>"
				data-point-id="<?php echo esc_attr( $quicktime['displayed']['point']->data['id'] ); ?>">
				<span class="button-icon fa fa-times" aria-hidden="true"></span>
			</div>

		<?php else : ?> <!-- data-action="edit_config_quick_time" -->
			<div class="action-input wpeo-button button-progress button-grey button-square-20 button-rounded"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_config_quick_time' ) ); ?>"
				data-key="<?php echo esc_attr( $key ); ?>"
				data-task-id="<?php echo esc_attr( $quicktime['displayed']['task']->data['id'] ); ?>"
				data-point-id="<?php echo esc_attr( $quicktime['displayed']['point']->data['id'] ); ?>">
				<span class="button-icon fa fa-refresh" aria-hidden="true"></span>
			</div>

		<?php endif; ?>

	</td>
	<td>
		<div class="wpeo-button button-main button-progress action-input tm_quickpoint_add_time"
			data-parent="item"
			data-action="quick_time_add_comment" style='visibility: hidden'>
			<span class="button-icon fa fa-plus" aria-hidden="true"></span>
		</div>
	</td>
</tr>
