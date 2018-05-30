<?php
/**
 * La liste des tâches ayant dépassé.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.1
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
		<tr>
			<td>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->data['id'] ) ); ?>" target="wptm_view_activity_element" >
					<?php echo esc_html( '#' . $task->data['id'] ); ?> - <?php echo esc_html( $task->data['title'] ); ?>
				</a>
			</td>
			<td>
				<?php if ( ! empty( $task->data['task_parent']->ID ) ) : ?>
					<a target="_blank" href="<?php echo esc_attr( admin_url( 'post.php?post=' . $task->data['task_parent']->ID . '&action=edit' ) ); ?>">
						<?php echo esc_html( $task->data['task_parent']->post_title ); ?>
					</a>
				<?php else : ?>
					<?php echo esc_html( $task->data['task_parent'] ); ?>
				<?php endif; ?>
			</td>

			<td><?php echo esc_html( $task->data['time_displayed'] ); ?></td>
			<td><?php echo esc_html( $task->data['diff_time'] ); ?></td>
		</tr>
