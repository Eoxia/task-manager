<?php
/**
 * Affichage d'un temps rapide.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
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
	<td class="task" data-title="<?php esc_html_e( 'Task ID', 'task-manager' ); ?>">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][task_id]" value="<?php echo esc_attr( $quicktime['displayed']['task']->id ); ?>" />
		<strong><?php echo esc_html( '#' . $quicktime['displayed']['task']->id . ' ' . $quicktime['displayed']['task']->title ); ?></strong>
	</td>
	<td class="point wpeo-tooltip-event" data-title="<?php esc_html_e( 'Point ID', 'task-manager' ); ?>" aria-label="<?php echo esc_attr( '#' . $quicktime['displayed']['point']->id . ' ' . $quicktime['displayed']['point']->content ); ?>">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][point_id]" value="<?php echo esc_attr( $quicktime['displayed']['point']->id ); ?>" />
		<?php echo esc_html( $quicktime['displayed']['point_fake_content'] ); ?>
	</td>
	<td class="content" data-title="<?php esc_html_e( 'Comment', 'task-manager' ); ?>">
		<textarea name="comments[<?php echo esc_attr( $i ); ?>][content]" rows="1"><?php echo $quicktime['content']; ?></textarea>
	</td>
	<td class="min" data-title="<?php esc_html_e( 'min.', 'task-manager' ); ?>">
		<div>
			<i class="fa fa-clock-o" aria-hidden="true"></i>
			<input type="hidden" class="time" name="comments[<?php echo esc_attr( $i ); ?>][time]" />
			<input type="text" class="displayed" />
		</div>
	</td>
	<td class="action"><input type="checkbox" class="set_time" name="comments[<?php echo esc_attr( $i ); ?>][can_add]" /></td>
</tr>
