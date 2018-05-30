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
<table class="tm-indicator-time-exceed" >
	<thead>
		<tr>
			<th>#<?php esc_html_e( 'ID', 'task-manager' ); ?> - <?php esc_html_e( 'Title', 'task-manager' ); ?></th>
			<th><?php esc_html_e( 'Parent task', 'task-manager' ); ?></th>
			<th><?php esc_html_e( 'Time', 'task-manager' ); ?></th>
			<th><?php esc_html_e( 'Total time exceeded', 'task-manager' ); ?></th>
		</tr>
	</thead>

	<tbody>
	<?php if ( ! empty( $tasks_exceed_time ) ) : ?>
		<?php foreach ( $tasks_exceed_time as $task ) : ?>
			<?php \eoxia\View_Util::exec( 'task-manager', 'time-exceeded', 'backend/item', array( 'task' => $task ) ); ?>
		<?php endforeach; ?>
	<?php else : ?>
		<tr><td colspan="5" ><?php esc_html_e( 'There is no task with exceeded time corresponding to your search.', 'task-manager' ); ?></td></tr>
	<?php endif; ?>
	</tbody>
</table>
